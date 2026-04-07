<?php
session_start();
include('../includes/db.php');

$id=$_SESSION['student_id'];

// stats
$assignments=$conn->query("SELECT COUNT(*) c FROM assignments")->fetch_assoc()['c'];
$submissions=$conn->query("SELECT COUNT(*) c FROM assignment_submissions WHERE student_id='$id'")->fetch_assoc()['c'];
$quizzes=$conn->query("SELECT COUNT(*) c FROM exams")->fetch_assoc()['c'];
$groups=$conn->query("SELECT COUNT(*) c FROM group_members WHERE student_id='$id' AND status='approved'")->fetch_assoc()['c'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>
<style>
.container{max-width:1000px;margin:30px auto;}
.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
    gap:20px;
}
.card{
    background:#fff;
    padding:25px;
    border-radius:12px;
    text-align:center;
    box-shadow:0 4px 12px rgba(0,0,0,0.1);
}
.num{font-size:28px;color:#1f6de0;font-weight:bold;}
</style>
</head>

<body>

<div class="container">
<h2>📊 Dashboard Overview</h2>

<div class="grid">
    <div class="card">
        <div class="num"><?php echo $assignments; ?></div>
        Assignments
    </div>

    <div class="card">
        <div class="num"><?php echo $submissions; ?></div>
        My Submissions
    </div>

    <div class="card">
        <div class="num"><?php echo $quizzes; ?></div>
        Quizzes
    </div>

    <div class="card">
        <div class="num"><?php echo $groups; ?></div>
        My Groups
    </div>
</div>

</div>

</body>
</html>