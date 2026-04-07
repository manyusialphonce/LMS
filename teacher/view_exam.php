<?php
session_start();
include('../includes/db.php');

if(!isset($_SESSION['teacher_id'])){
    header("Location: ../teacher/login.php");
    exit();
}

$teacher_id = $_SESSION['teacher_id'];
$exam_id = $_GET['exam_id'] ?? 0;

// Fetch exam info
$exam = $conn->query("SELECT exams.*, courses.title as course_title 
                      FROM exams 
                      JOIN courses ON exams.course_id = courses.id
                      WHERE exams.id='$exam_id' AND courses.teacher_id='$teacher_id'")->fetch_assoc();

if(!$exam){
    die("Exam not found or you do not have permission.");
}

// Fetch results & violations
$results = $conn->query("SELECT students.full_name, results.score, results.finished_at,
                         (SELECT COUNT(*) FROM violations WHERE student_id=students.id AND exam_id='$exam_id') as violations
                         FROM results
                         JOIN students ON results.student_id = students.id
                         WHERE results.exam_id='$exam_id'");
                         
$student_names = [];
$scores = [];
$violations = [];

while($res = $results->fetch_assoc()){
    $student_names[] = $res['full_name'];
    $scores[] = (int)$res['score'];
    $violations[] = (int)$res['violations'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Exam Analytics - <?php echo $exam['title']; ?></title>
    <link rel="stylesheet" href="../includes/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .container { max-width:900px; margin:50px auto; padding:20px; background:#fff; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1);}
        h2 { text-align:center; color:#1f6de0; margin-bottom:30px; }
        canvas { margin-top:30px; }
        table { width:100%; border-collapse:collapse; margin-top:20px;}
        th, td { border:1px solid #ccc; padding:10px; text-align:center; }
        th { background:#1f6de0; color:#fff; }
        .back-btn { display:inline-block; margin-top:20px; padding:8px 16px; background:#1f6de0; color:#fff; border-radius:6px; text-decoration:none; }
        .back-btn:hover { background:#155ab6; }
    </style>
</head>
<body>
<div class="container">
    <h2>Exam Analytics: <?php echo $exam['title'] . " (" . $exam['course_title'] . ")"; ?></h2>

    <h3>Scores Distribution</h3>
    <canvas id="scoresChart"></canvas>

    <h3>Violations per Student</h3>
    <canvas id="violationsChart"></canvas>

    <h3>Results Table</h3>
    <table>
        <tr>
            <th>Student</th>
            <th>Score</th>
            <th>Violations</th>
        </tr>
        <?php foreach($student_names as $i => $name): ?>
        <tr>
            <td><?php echo $name; ?></td>
            <td><?php echo $scores[$i]; ?></td>
            <td><?php echo $violations[$i]; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
</div>

<script>
const labels = <?php echo json_encode($student_names); ?>;
const scores = <?php echo json_encode($scores); ?>;
const violations = <?php echo json_encode($violations); ?>;

// Scores Chart
const ctx1 = document.getElementById('scoresChart').getContext('2d');
new Chart(ctx1, {
    type: 'bar',
    data: { labels: labels, datasets: [{ label: 'Score', data: scores, backgroundColor:'rgba(31,109,224,0.7)', borderColor:'rgba(31,109,224,1)', borderWidth:1 }] },
    options: { scales:{ y:{ beginAtZero:true, max:100 } } }
});

// Violations Chart
const ctx2 = document.getElementById('violationsChart').getContext('2d');
new Chart(ctx2, {
    type: 'bar',
    data: { labels: labels, datasets: [{ label: 'Violations', data: violations, backgroundColor:'rgba(255,0,0,0.7)', borderColor:'rgba(255,0,0,1)', borderWidth:1 }] },
    options: { scales:{ y:{ beginAtZero:true } } }
});
</script>

</body>
</html>