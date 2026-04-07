<?php
session_start();
include('../includes/db.php');

date_default_timezone_set("Africa/Dar_es_Salaam");

if(!isset($_SESSION['student_id'])){
    header("Location: ../login.php");
    exit();
}

$id = $_SESSION['student_id'];

$res = $conn->query("SELECT * FROM assignments ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Assignments</title>
    <link rel="stylesheet" href="../includes/style.css">
</head>
<body>

<h2 class="page-title">📚 Assignments</h2>

<?php 
if($res->num_rows == 0){
    
    echo '<div class="no-assignment"><h3>🚨🚨🚨No Assignments Available🚨🚨🚨</h3>
    <p>No assignments have been uploaded yet. Please check back later.</p></div>';
}

while($a=$res->fetch_assoc()):
    $deadline = strtotime($a['due_date']);
    $now = time();

    $check = $conn->query("SELECT * FROM assignment_submissions 
        WHERE assignment_id='{$a['id']}' AND student_id='$id'");
    $submitted = $check->num_rows > 0;
?>

<div class="assignment-card">
    <h3><?php echo htmlspecialchars($a['title']); ?></h3>
    <p><?php echo nl2br(htmlspecialchars($a['description'])); ?></p>
    <p><strong>Deadline:</strong> <?php echo date("d M Y H:i", $deadline); ?></p>

    <a href="../uploads/<?php echo $a['file']; ?>" class="btn-download" download>📥 Download Assignment</a>

    <hr>

    <?php if($submitted): 
        $sub = $check->fetch_assoc(); ?>
        <p class="status-submitted">✅ Submitted</p>

        <?php if($sub['status'] == "Graded"): ?>
            <p><strong>Marks:</strong> <?php echo htmlspecialchars($sub['marks']); ?></p>
            <p><strong>Feedback:</strong> <?php echo htmlspecialchars($sub['feedback']); ?></p>
        <?php else: ?>
            <p class="status-waiting">⏳ Waiting for grading</p>
        <?php endif; ?>

    <?php elseif($now > $deadline): ?>
        <p class="status-passed">❌ Deadline Passed</p>
        <p>Submission is closed.</p>

    <?php else: ?>
        <form action="submit_assignment.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="assignment_id" value="<?php echo $a['id']; ?>">
            <input type="text" name="title" placeholder="Your Assignment Title" required>
            <input type="file" name="file" required>
            <button name="submit">Submit Assignment</button>
        </form>
    <?php endif; ?>
</div>

<?php endwhile; ?>

</body>
</html>