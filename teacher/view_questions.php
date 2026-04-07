<?php
session_start();
include('../includes/db.php');

if(!isset($_SESSION['teacher_id'])){
    header("Location: login.php");
    exit();
}

$teacher_id = $_SESSION['teacher_id'];
$exam_id = $_GET['exam_id'] ?? null;

if(!$exam_id){
    echo "No exam selected.";
    exit();
}

// Fetch exam info (to verify teacher owns it)
$exam_data = $conn->query("SELECT exams.*, courses.title as course_title 
                           FROM exams 
                           JOIN courses ON exams.course_id = courses.id
                           WHERE exams.id='$exam_id' AND courses.teacher_id='$teacher_id'")->fetch_assoc();

if(!$exam_data){
    echo "Exam not found or access denied.";
    exit();
}

// Fetch questions for this exam
$questions_result = $conn->query("SELECT * FROM questions WHERE exam_id='$exam_id'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Questions - <?php echo $exam_data['title']; ?></title>
    <link rel="stylesheet" href="../includes/style.css">
    <style>
        .container { max-width:900px; margin:50px auto; background:#fff; padding:30px; border-radius:12px; }
        table { width:100%; border-collapse:collapse; margin-top:20px; }
        th, td { border:1px solid #ccc; padding:10px; text-align:left; }
        th { background:#f0f0f0; }
        .correct { font-weight:bold; color:green; }
    </style>
</head>
<body>
<div class="container">
    <h2>Questions for Exam: <?php echo $exam_data['title']; ?></h2>
    <p>Course: <?php echo $exam_data['course_title']; ?> | Total Marks: <?php echo $exam_data['total_marks']; ?> | Duration: <?php echo $exam_data['duration_minutes']; ?> min</p>

    <?php if($questions_result->num_rows > 0): ?>
        <table>
            <tr>
                <th>#</th>
                <th>Question</th>
                <th>Options</th>
                <th>Correct Answer</th>
                <th>Marks</th>
            </tr>
            <?php $count=1; while($q = $questions_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $count++; ?></td>
                    <td><?php echo $q['question_text']; ?></td>
                    <td>
                        A. <?php echo $q['option_a']; ?><br>
                        B. <?php echo $q['option_b']; ?><br>
                        C. <?php echo $q['option_c']; ?><br>
                        D. <?php echo $q['option_d']; ?>
                    </td>
                    <td class="correct"><?php echo $q['correct_option']; ?></td>
                    <td><?php echo $q['marks']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No questions added yet for this exam.</p>
    <?php endif; ?>

    <p style="margin-top:20px;"><a href="add_question.php?exam_id=<?php echo $exam_id; ?>">Add More Questions</a></p>
    <p><a href="dashboard.php">Back to Dashboard</a></p>
</div>
</body>
</html>