<?php
session_start();
include('../includes/db.php');

if(!isset($_SESSION['teacher_id'])){
    header("Location: ../login.php");
    exit();
}

$teacher_id = $_SESSION['teacher_id'];

if(!isset($_GET['exam_id'])){
    echo "Invalid Exam!";
    exit();
}

$exam_id = (int)$_GET['exam_id'];

// Fetch exam info + course
$exam = $conn->query("SELECT exams.*, courses.title as course_title 
                      FROM exams 
                      JOIN courses ON exams.course_id = courses.id
                      WHERE exams.id='$exam_id' AND exams.teacher_id='$teacher_id'")->fetch_assoc();
if(!$exam){
    echo "Exam not found!";
    exit();
}

// Fetch all results
$results_query = $conn->query("SELECT results.*, students.full_name 
                               FROM results
                               JOIN students ON results.student_id = students.id
                               WHERE results.exam_id='$exam_id'
                               ORDER BY results.score DESC");

// Prepare arrays for stats & charts
$results_array = [];
$scores = [];
while($r = $results_query->fetch_assoc()){
    $results_array[] = $r;
    $scores[] = $r['score'];
}

// Calculate stats
$average = count($scores) ? round(array_sum($scores)/count($scores),2) : 0;
$max_score = count($scores) ? max($scores) : 0;
$min_score = count($scores) ? min($scores) : 0;

// Fetch violations
$violations_query = $conn->query("SELECT * FROM violations WHERE exam_id='$exam_id'");
$violations_array = [];
while($v = $violations_query->fetch_assoc()){
    $violations_array[$v['student_id']][] = $v['violation_type'];
}

// Prepare chart data for all exams by teacher
$exams_chart_query = $conn->query("SELECT exams.*, courses.title as course_title 
                                   FROM exams 
                                   JOIN courses ON exams.course_id = courses.id 
                                   WHERE courses.teacher_id='$teacher_id'");
$chart_labels = [];
$chart_avg_scores = [];
$chart_violations = [];
while($e = $exams_chart_query->fetch_assoc()){
    $eid = $e['id'];
    $chart_labels[] = $e['title'];
    $avg_score = $conn->query("SELECT AVG(score) as avg_score FROM results WHERE exam_id='$eid'")->fetch_assoc()['avg_score'];
    $chart_avg_scores[] = round($avg_score,2);
    $v_count = $conn->query("SELECT COUNT(*) as t FROM violations WHERE exam_id='$eid'")->fetch_assoc()['t'];
    $chart_violations[] = $v_count;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Full Exam Analytics - <?php echo $exam['title']; ?></title>
    <link rel="stylesheet" href="../includes/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family:'Segoe UI',sans-serif; background:#f4f6f9; margin:0; padding:20px;}
        h2, h3 { color:#1f6de0; }
        .container { max-width:1000px; margin:0 auto; }
        .stats { display:flex; gap:20px; margin-bottom:20px; }
        .stat-box { flex:1; padding:15px; background:#1f6de0; color:#fff; border-radius:12px; text-align:center; }
        .charts { background:#fff; padding:20px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); margin-bottom:30px; }
        table { width:100%; border-collapse:collapse; background:#fff; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.05); }
        th, td { padding:12px; border-bottom:1px solid #ddd; text-align:left; }
        th { background:#1f6de0; color:#fff; border-radius:12px 12px 0 0; }
        .top { background:#ffeeba; }
        .button { background:#1f6de0; color:#fff; padding:8px 15px; border-radius:8px; text-decoration:none; display:inline-block; margin-top:10px;}
        .button:hover { background:#155ab6; }
        .back-btn { display:inline-block; margin-bottom:15px; }
        canvas { margin-top:15px; }
    </style>
</head>
<body>
<div class="container">

    <a class="button back-btn" href="dashboard.php">← Back to Dashboard</a>
    <h2>Exam Analytics - <?php echo $exam['title']; ?> (<?php echo $exam['course_title']; ?>)</h2>

    <div class="stats">
        <div class="stat-box">Average Score: <?php echo $average; ?></div>
        <div class="stat-box">Max Score: <?php echo $max_score; ?></div>
        <div class="stat-box">Min Score: <?php echo $min_score; ?></div>
    </div>

    <div class="charts">
        <h3>Average Scores of All Your Exams</h3>
        <canvas id="avgScoreChart"></canvas>

        <h3>Violations of All Your Exams</h3>
        <canvas id="violationsChart"></canvas>
    </div>

    <a class="button" href="export_results.php?exam_id=<?php echo $exam_id; ?>">Export CSV</a>

    <h3>Student Results</h3>
    <table>
        <tr><th>#</th><th>Student</th><th>Score</th><th>Violations</th></tr>
        <?php 
        $count=1; 
        foreach($results_array as $r):
            $is_top = $count <= 3 ? "top" : "";
            $viol = isset($violations_array[$r['student_id']]) ? implode(", ", $violations_array[$r['student_id']]) : '-';
        ?>
            <tr class="<?php echo $is_top; ?>">
                <td><?php echo $count++; ?></td>
                <td><?php echo $r['full_name']; ?></td>
                <td><?php echo $r['score']; ?></td>
                <td><?php echo $viol; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

</div>

<script>
const labels = <?php echo json_encode($chart_labels); ?>;
const avgScores = <?php echo json_encode($chart_avg_scores); ?>;
const violations = <?php echo json_encode($chart_violations); ?>;

// Average Score Chart
const ctx1 = document.getElementById('avgScoreChart').getContext('2d');
new Chart(ctx1, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Average Score',
            data: avgScores,
            backgroundColor: 'rgba(31,109,224,0.7)',
            borderColor: 'rgba(31,109,224,1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: { y: { beginAtZero:true, max: 100 } }
    }
});

// Violations Chart
const ctx2 = document.getElementById('violationsChart').getContext('2d');
new Chart(ctx2, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Violations Detected',
            data: violations,
            fill: false,
            borderColor: 'rgba(255,0,0,0.8)',
            tension: 0.1
        }]
    },
    options: {
        scales: { y: { beginAtZero:true } }
    }
});
</script>

</body>
</html>