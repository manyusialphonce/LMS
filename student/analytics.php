<?php
session_start();
include('../includes/db.php');

if(!isset($_SESSION['student_id'])){
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Fetch exams taken by student
$results = $conn->query("SELECT results.*, exams.title as exam_title, courses.title as course_title
                         FROM results
                         JOIN exams ON results.exam_id = exams.id
                         JOIN courses ON exams.course_id = courses.id
                         WHERE results.student_id='$student_id'
                         ORDER BY results.finished_at ASC");

$exam_labels = [];
$scores = [];
$violations = [];

while($res = $results->fetch_assoc()){
    $exam_labels[] = $res['exam_title'] . " (" . $res['course_title'] . ")";
    $scores[] = (int)$res['score'];
    
    $violation_count = $conn->query("SELECT COUNT(*) as t FROM violations WHERE student_id='$student_id' AND exam_id='{$res['exam_id']}'")->fetch_assoc()['t'];
    $violations[] = (int)$violation_count;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Analytics</title>
    <link rel="stylesheet" href="../includes/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .dashboard-container { max-width:900px; margin:50px auto; background:#fff; padding:30px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1);}
        h2 { color:#1f6de0; text-align:center; margin-bottom:30px; }
        canvas { margin-top:30px; }
        .back-btn { background:#1f6de0; color:#fff; padding:6px 12px; border-radius:6px; text-decoration:none; display:inline-block; margin-top:20px; }
        .back-btn:hover { background:#155ab6; }
    </style>
</head>
<body>
<div class="dashboard-container">
    <h2>Student Analytics Dashboard</h2>

    <h3>Scores Trend</h3>
    <canvas id="scoresChart"></canvas>

    <h3>Violations History</h3>
    <canvas id="violationsChart"></canvas>

    <a class="back-btn" href="dashboard.php">Back to Dashboard</a>
</div>

<script>
const labels = <?php echo json_encode($exam_labels); ?>;
const scores = <?php echo json_encode($scores); ?>;
const violations = <?php echo json_encode($violations); ?>;

// Scores Trend Chart
const ctx1 = document.getElementById('scoresChart').getContext('2d');
new Chart(ctx1, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Score',
            data: scores,
            fill: false,
            borderColor: 'rgba(31,109,224,0.8)',
            tension: 0.2,
            pointBackgroundColor: 'rgba(31,109,224,1)'
        }]
    },
    options: {
        scales: { y: { beginAtZero:true, max: 100 } }
    }
});

// Violations Chart
const ctx2 = document.getElementById('violationsChart').getContext('2d');
new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Violations',
            data: violations,
            backgroundColor: 'rgba(255,0,0,0.7)',
            borderColor: 'rgba(255,0,0,1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: { y: { beginAtZero:true } }
    }
});
</script>
</body>
</html>