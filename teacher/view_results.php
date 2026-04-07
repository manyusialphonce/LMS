<?php
session_start();
include('../includes/db.php');

if(!isset($_SESSION['teacher_id']) || !isset($_GET['exam_id'])){
    header("Location: results.php");
    exit();
}

$teacher_id = $_SESSION['teacher_id'];
$exam_id = (int)$_GET['exam_id'];

// Verify that this exam belongs to teacher
$exam_check = $conn->query("SELECT exams.*, courses.teacher_id 
                            FROM exams 
                            JOIN courses ON exams.course_id = courses.id 
                            WHERE exams.id='$exam_id' AND courses.teacher_id='$teacher_id'");

if($exam_check->num_rows == 0){
    echo "You are not authorized to view this exam.";
    exit();
}

// Fetch results
$results = $conn->query("SELECT results.*, students.full_name 
                         FROM results 
                         JOIN students ON results.student_id = students.id 
                         WHERE results.exam_id='$exam_id'");

// Fetch violations per student
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Exam Results</title>
    <link rel="stylesheet" href="../includes/style.css">
    <style>
        table { width:100%; border-collapse: collapse; margin-top:20px; }
        th, td { padding:10px; border:1px solid #ccc; text-align:left; }
        th { background:#1f6de0; color:#fff; }
        tr:nth-child(even) { background:#f4f6f9; }
        .back-btn { background:#1f6de0; color:#fff; padding:6px 12px; border-radius:6px; text-decoration:none; }
        .back-btn:hover { background:#155ab6; }
    </style>
</head>
<body>
<div class="dashboard-container">
    <h2>Exam Results</h2>
    <?php if($results->num_rows == 0): ?>
        <p>No results submitted yet.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Student</th>
                <th>Score</th>
                <th>Violations</th>
            </tr>
            <?php while($res = $results->fetch_assoc()): 
                $violations = $conn->query("SELECT COUNT(*) as t FROM violations WHERE student_id='{$res['student_id']}' AND exam_id='$exam_id'")->fetch_assoc()['t'];
            ?>
                <tr>
                    <td><?php echo $res['full_name']; ?></td>
                    <td><?php echo $res['score']; ?></td>
                    <td><?php echo $violations; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php endif; ?>
    <p><a class="back-btn" href="results.php">Back to Exams</a></p>
</div>
</body>
</html>