<?php
include('../includes/db.php');

$course_id = $_GET['course_id'];

$res = $conn->query("SELECT * FROM lecture_rooms WHERE course_id='$course_id'");
?>

<div class="container">
<h2 class="page-title">🎥 Lecture Rooms</h2>

<?php if($res->num_rows == 0): ?>
<div class="empty">No live sessions scheduled</div>
<?php endif; ?>

<?php while($l=$res->fetch_assoc()): ?>
<div class="card">
    <h3><?php echo $l['title']; ?></h3>
    <p><strong>Time:</strong> <?php echo $l['scheduled_at']; ?></p>
    <a href="<?php echo $l['meeting_link']; ?>" target="_blank" class="btn">
        Join Lecture
    </a>
</div>
<?php endwhile; ?>

</div>