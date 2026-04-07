<?php
session_start();
include('../includes/db.php');

$course_id = $_GET['course_id'] ?? 0;

$res = $conn->query("SELECT * FROM course_resources 
WHERE course_id='$course_id'");
?>

<!DOCTYPE html>
<html>
<head>
<title>Resources</title>
<style>
.container{max-width:800px;margin:40px auto;}
.card{
    background:#fff;
    padding:20px;
    border-radius:10px;
    margin-bottom:15px;
}
</style>
</head>
<body>

<div class="container">
<h2>📂 Course Materials</h2>

<?php while($r=$res->fetch_assoc()): ?>
<div class="card">
    <h3><?php echo $r['title']; ?></h3>
    <a href="../uploads/<?php echo $r['file']; ?>" download>
        Download
    </a>
</div>
<?php endwhile; ?>

</div>

</body>
</html>