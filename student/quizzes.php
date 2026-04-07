<?php
session_start();
include('../includes/db.php');

$course_id = $_GET['course_id'] ?? 0;

$res = $conn->query("SELECT * FROM exams WHERE course_id='$course_id'");
?>

<h2>❓ Quizzes</h2>

<?php while($q=$res->fetch_assoc()): ?>
<div>
    <h3><?php echo $q['title']; ?></h3>
    <a href="take_exam.php?exam_id=<?php echo $q['id']; ?>">
        Start Quiz
    </a>
</div>
<?php endwhile; ?>