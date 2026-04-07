<?php
session_start();
include('../includes/db.php');

if(!isset($_SESSION['student_id'])){
    header("Location: ../login.php");
    exit();
}

$course_id = $_GET['course_id'] ?? 0;

$res = $conn->query("SELECT * FROM course_announcements 
WHERE course_id='$course_id' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Announcements</title>
<link rel="stylesheet" href="../includes/style.css">
<style>
.container{
    max-width:800px;
    margin:40px auto;
}

.card{
    background:#fff;
    padding:20px;
    border-radius:10px;
    margin-bottom:15px;
    box-shadow:0 4px 10px rgba(0,0,0,0.1);
}

.title{color:#1f6de0;font-size:20px;}
.date{color:#777;font-size:13px;}
.no-data{
    text-align:center;
    margin-top:100px;
    background:#fff;
    padding:40px;
    border-radius:10px;
}
</style>
</head>

<body>

<div class="container">
<h2>📢 Announcements</h2>

<?php if($res->num_rows == 0): ?>
<div class="no-data">No announcements available</div>
<?php endif; ?>

<?php while($a=$res->fetch_assoc()): ?>
<div class="card">
    <div class="title"><?php echo $a['title']; ?></div>
    <p><?php echo $a['message']; ?></p>
    <div class="date"><?php echo date("d M Y H:i", strtotime($a['created_at'])); ?></div>
</div>
<?php endwhile; ?>

</div>

</body>
</html>