<?php
session_start();
include('../includes/db.php');

if(!isset($_SESSION['student_id'])){
    header("Location: ../login.php");
    exit();
}

$res = $conn->query("SELECT * FROM students");
?>

<!DOCTYPE html>
<html>
<head>
<title>Classmates</title>
<link rel="stylesheet" href="../includes/style.css">
<style>
.container{max-width:900px;margin:40px auto;}
.card{
    background:#fff;padding:20px;border-radius:12px;
    margin-bottom:15px;
    box-shadow:0 4px 10px rgba(0,0,0,0.1);
}
</style>
</head>

<body>

<div class="container">
<h2>👨‍🎓 Classmates</h2>

<?php if($res->num_rows==0): ?>
<div class="card">No classmates found</div>
<?php endif; ?>

<?php while($s=$res->fetch_assoc()): ?>
<div class="card">
    <strong><?php echo $s['full_name']; ?></strong><br>
    <small><?php echo $s['email']; ?></small>
</div>
<?php endwhile; ?>

</div>

</body>
</html>