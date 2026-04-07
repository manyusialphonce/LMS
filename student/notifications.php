<?php
session_start();
include('../includes/db.php');

if(!isset($_SESSION['student_id'])){
    header("Location: ../login.php");
    exit();
}

$id = $_SESSION['student_id'];

$res = $conn->query("
SELECT * FROM notifications 
WHERE student_id='$id' 
ORDER BY id DESC
");

// mark as read
$conn->query("UPDATE notifications SET is_read=1 WHERE student_id='$id'");
?>

<!DOCTYPE html>
<html>
<head>
<title>Notifications</title>
<link rel="stylesheet" href="../includes/style.css">
<style>
.container{max-width:800px;margin:40px auto;}
.card{
    background:#fff;
    padding:20px;
    border-radius:12px;
    margin-bottom:15px;
    box-shadow:0 4px 12px rgba(0,0,0,0.1);
}
.unread{border-left:5px solid #1f6de0;}
.title{font-weight:bold;font-size:18px;}
.time{color:#777;font-size:12px;}
.no-data{
    text-align:center;
    margin-top:100px;
    padding:40px;
    background:#fff;
    border-radius:12px;
}
</style>
</head>

<body>

<div class="container">
<h2>🔔 Notifications</h2>

<?php if($res->num_rows==0): ?>
<div class="no-data">No notifications yet</div>
<?php endif; ?>

<?php while($n=$res->fetch_assoc()): ?>
<div class="card <?php echo $n['is_read'] ? '' : 'unread'; ?>">
    <div class="title"><?php echo $n['title']; ?></div>
    <p><?php echo $n['message']; ?></p>
    <div class="time"><?php echo date("d M Y H:i",strtotime($n['created_at'])); ?></div>
</div>
<?php endwhile; ?>

</div>

</body>
</html>