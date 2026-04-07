<?php
session_start();
include('../includes/db.php');

$course_id = $_GET['course_id'] ?? 0;

$res = $conn->query("SELECT * FROM tutorials WHERE course_id='$course_id'");
?>

<h2>📚 Tutorials</h2>

<?php while($t=$res->fetch_assoc()): ?>
<div>
    <h3><?php echo $t['title']; ?></h3>
    <p><?php echo $t['content']; ?></p>
</div>
<?php endwhile; ?>