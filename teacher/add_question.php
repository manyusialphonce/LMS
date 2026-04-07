<?php
session_start();
include('../includes/db.php');

if(!isset($_SESSION['teacher_id'])){
    header("Location: login.php");
    exit();
}

$message = '';
$teacher_id = $_SESSION['teacher_id'];

// Fetch teacher's exams
$exams_result = $conn->query("SELECT exams.* FROM exams 
                              JOIN courses ON exams.course_id = courses.id
                              WHERE courses.teacher_id='$teacher_id'");

// Handle question addition (store in session first)
if(isset($_POST['add_question']) || isset($_POST['finish_exam'])){
    $exam_id = $_POST['exam_id'];
    $question_text = $conn->real_escape_string($_POST['question_text']);
    $option_a = $conn->real_escape_string($_POST['option_a']);
    $option_b = $conn->real_escape_string($_POST['option_b']);
    $option_c = $conn->real_escape_string($_POST['option_c']);
    $option_d = $conn->real_escape_string($_POST['option_d']);
    $correct_option = $_POST['correct_option'];

    // Store questions in session until finished
    if(!isset($_SESSION['exam_questions'][$exam_id])){
        $_SESSION['exam_questions'][$exam_id] = [];
    }

    $_SESSION['exam_questions'][$exam_id][] = [
        'question_text' => $question_text,
        'option_a' => $option_a,
        'option_b' => $option_b,
        'option_c' => $option_c,
        'option_d' => $option_d,
        'correct_option' => $correct_option
    ];

    if(isset($_POST['finish_exam'])){
        // Fetch total marks from exam
        $exam_data = $conn->query("SELECT total_marks FROM exams WHERE id='$exam_id'")->fetch_assoc();
        $total_marks = $exam_data['total_marks'];
        $questions = $_SESSION['exam_questions'][$exam_id];
        $num_questions = count($questions);
        $marks_per_question = $num_questions ? round($total_marks / $num_questions,2) : 0;

        // Insert all questions into database
        $stmt = $conn->prepare("INSERT INTO questions (exam_id, question_text, option_a, option_b, option_c, option_d, correct_option, marks) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        foreach($questions as $q){
            $stmt->bind_param("issssssi", $exam_id, $q['question_text'], $q['option_a'], $q['option_b'], $q['option_c'], $q['option_d'], $q['correct_option'], $marks_per_question);
            $stmt->execute();
        }
        $stmt->close();

        // Clear session
        unset($_SESSION['exam_questions'][$exam_id]);

        $message = "All questions uploaded successfully!";
    } else {
        $message = "Question added! Add another question or finish exam.";
    }
}

// If exam is selected, fetch how many questions already added in session
$selected_exam_id = $_POST['exam_id'] ?? null;
$added_count = ($selected_exam_id && isset($_SESSION['exam_questions'][$selected_exam_id])) ? count($_SESSION['exam_questions'][$selected_exam_id]) : 0;

?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Questions</title>
    <link rel="stylesheet" href="../includes/style.css">
    <style>
        .container { max-width:700px; margin:50px auto; background:#fff; padding:30px; border-radius:12px; }
        textarea, input, select { width:100%; padding:10px; margin:6px 0; border-radius:6px; border:1px solid #ccc; }
        button { background:#1f6de0; color:#fff; padding:10px 20px; border:none; border-radius:8px; cursor:pointer; margin-top:10px;}
        button:hover { background:#155ab6; }
        .message { background:#d4edda; color:#155724; padding:10px; border-radius:6px; margin-bottom:10px; }
        .added-count { font-weight:bold; margin-bottom:10px; }
    </style>
</head>
<body>
<div class="container">
    <h2>Add Questions</h2>
    <?php if($message) echo "<p class='message'>$message</p>"; ?>

    <form method="POST" action="">
        <select name="exam_id" required onchange="this.form.submit()">
            <option value="">Select Exam</option>
            <?php 
            // Reset result pointer to reuse
            $exams_result->data_seek(0);
            while($exam = $exams_result->fetch_assoc()): ?>
                <option value="<?php echo $exam['id']; ?>" <?php if($selected_exam_id==$exam['id']) echo 'selected'; ?>>
                    <?php echo $exam['title']; ?>
                </option>
            <?php endwhile; ?>
        </select>

        <?php if($selected_exam_id): ?>
            <p class="added-count">Questions added so far: <?php echo $added_count; ?></p>

            <textarea name="question_text" placeholder="Question Text" rows="3" required></textarea>
            <input type="text" name="option_a" placeholder="Option A" required>
            <input type="text" name="option_b" placeholder="Option B" required>
            <input type="text" name="option_c" placeholder="Option C" required>
            <input type="text" name="option_d" placeholder="Option D" required>
            <select name="correct_option" required>
                <option value="">Select Correct Option</option>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="D">D</option>
            </select>
            <button type="submit" name="add_question">Add Another Question</button>
            <button type="submit" name="finish_exam">Finish & Upload All Questions</button>
        <?php endif; ?>
    </form>

    <p><a href="dashboard.php">Back to Dashboard</a></p>
</div>
</body>
</html>