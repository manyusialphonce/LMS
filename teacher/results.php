<?php
session_start();
include('../includes/db.php');

if(!isset($_SESSION['teacher_id'])){
    header("Location: login.php");
    exit();
}

$teacher_id = $_SESSION['teacher_id'];

// Fetch all exams by this teacher
$exams = $conn->query("SELECT exams.*, courses.title as course_title 
                       FROM exams 
                       JOIN courses ON exams.course_id = courses.id 
                       WHERE courses.teacher_id='$teacher_id'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Exam Results</title>
    <link rel="stylesheet" href="../includes/style.css">
    <style>
        table { width:100%; border-collapse: collapse; margin-top:20px; }
        th, td { padding:10px; border:1px solid #ccc; text-align:left; }
        th { background:#1f6de0; color:#fff; }
        tr:nth-child(even) { background:#f4f6f9; }
        a.view-btn { background:#1f6de0; color:#fff; padding:6px 12px; border-radius:6px; text-decoration:none; }
        a.view-btn:hover { background:#155ab6; }
    </style>
</head>
<body>
<div class="dashboard-container">
    <h2>Exam Results</h2>
    <?php if($exams->num_rows == 0): ?>
        <p>No exams found. <a href="create_exam.php">Create Exam</a></p>
    <?php else: ?>
        <table>
            <tr>
                <th>Course</th>
                <th>Exam</th>
                <th>View Results</th>
            </tr>
            <?php while($exam = $exams->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $exam['course_title']; ?></td>
                    <td><?php echo $exam['title']; ?></td>
                    <td><a class="view-btn" href="view_results.php?exam_id=<?php echo $exam['id']; ?>">View</a></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php endif; ?>
    <p><a class="view-btn" href="dashboard.php">Back to Dashboard</a></p>
</div>
</body>
</html>