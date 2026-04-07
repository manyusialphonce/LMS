
<?php
session_start();
include('../includes/db.php');

if(!isset($_SESSION['student_id'])){
    header("Location: ../login.php");
    exit();
}

$id = $_SESSION['student_id'];
$gid = $_GET['id'] ?? 0;

if($gid == 0){
    echo "<h2 style='text-align:center;color:red;margin-top:100px;'>Invalid Group</h2>";
    exit();
}

// Prevent duplicate request
$check = $conn->query("
SELECT * FROM group_members 
WHERE group_id='$gid' AND student_id='$id'
");

if($check->num_rows == 0){
    $conn->query("
    INSERT INTO group_members(group_id,student_id,status)
    VALUES('$gid','$id','pending')
    ");
    $message = "✅ Request sent successfully!";
}else{
    $message = "⚠️ You have already requested or joined this group.";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Join Group</title>
<link rel="stylesheet" href="../includes/style.css">

<style>
.box{
    max-width:500px;
    margin:120px auto;
    background:#fff;
    padding:40px;
    border-radius:12px;
    text-align:center;
    box-shadow:0 6px 18px rgba(0,0,0,0.15);
}

.box h2{
    color:#1f6de0;
}

.message{
    font-size:18px;
    margin:20px 0;
}

a{
    display:inline-block;
    margin-top:15px;
    padding:10px 20px;
    background:#1f6de0;
    color:#fff;
    border-radius:8px;
    text-decoration:none;
}
</style>

</head>
<body>

<div class="box">

<h2>👥 Group Request</h2>

<div class="message">
<?php echo $message; ?>
</div>

<a href="my_groups.php">Back to Groups</a>

</div>

</body>
</html>