<?php
include('../includes/db.php');

$res=$conn->query("SELECT * FROM assignment_submissions ORDER BY id DESC");
?>

<link rel="stylesheet" href="../includes/style.css">

<h2>Submissions</h2>

<?php while($s=$res->fetch_assoc()): ?>

<div class="assignment-card">

<p><strong>Student:</strong> <?php echo $s['student_id']; ?></p>
<p><strong>Title:</strong> <?php echo $s['title']; ?></p>

<a href="../uploads/<?php echo $s['file']; ?>" download>Download</a>

<p><strong>Status:</strong> <?php echo $s['status']; ?></p>

<a href="grade_assignment.php?id=<?php echo $s['id']; ?>">Grade</a>

</div>


<?php endwhile; ?>
<p style="margin-top:20px;"><a href="dashboard.php">Back to Dashboard</a></p>