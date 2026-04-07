<?php
session_start();
include('../includes/db.php');



$student_id = $_SESSION['student_id'];


// Fetch result
$result = $conn->query("SELECT * FROM results WHERE student_id='$student_id' AND exam_id='$exam_id'")->fetch_assoc();

// Count violations
$violations_count = $conn->query("SELECT COUNT(*) as t FROM violations WHERE student_id='$student_id' AND exam_id='$exam_id'")->fetch_assoc()['t'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Exam Result</title>
    <link rel="stylesheet" href="../includes/style.css">
</head>
<body>
<div class="container">
    <h2>Exam Result</h2>
    <p>Score: <?php echo $result['score']; ?> / <?php echo $result['score'] + $violations_count; ?></p>
    <p>Violations Detected: <?php echo $violations_count; ?></p>
    <p><a href="dashboard.php">Back to Dashboard</a></p>
</div>
</body>
</html>