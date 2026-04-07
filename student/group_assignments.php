<?php
session_start();
include('../includes/db.php');

if(!isset($_SESSION['student_id'])){
    header("Location: ../login.php");
    exit();
}

$course_id = $_GET['course_id'] ?? 0;

$res = $conn->query("SELECT * FROM assignments 
WHERE course_id='$course_id' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Assignments</title>
<link rel="stylesheet" href="../includes/style.css">
<style>
.container{max-width:800px;margin:40px auto;}
.card{
    background:#fff;
    padding:20px;
    border-radius:10px;
    margin-bottom:15px;
    box-shadow:0 4px 10px rgba(0,0,0,0.1);
}
.btn{
    display:inline-block;
    padding:8px 15px;
    background:#1f6de0;
    color:#fff;
    border-radius:6px;
    text-decoration:none;
}
</style>
</head>

<body>

<div class="container">
<h2>📝 Assignments</h2>

<?php if($res->num_rows == 0): ?>
<p>No assignments available</p>
<?php endif; ?>

<?php while($a=$res->fetch_assoc()): ?>
<div class="card">
    <h3><?php echo $a['title']; ?></h3>
    <p><?php echo $a['description']; ?></p>

    <p><strong>Deadline:</strong> 
    <?php echo date("d M Y H:i", strtotime($a['due_date'])); ?></p>

    <a href="../uploads/<?php echo $a['file']; ?>" class="btn" download>
        📥 Download
    </a>
</div>
<?php endwhile; ?>

</div>

</body>
</html>