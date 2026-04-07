<?php
session_start();
include('../includes/db.php');

if(!isset($_SESSION['teacher_id'])){
    header("Location: login.php");
    exit();
}

$message = '';
$teacher_id = $_SESSION['teacher_id'];

// Fetch teacher's courses
$courses_result = $conn->query("SELECT * FROM courses WHERE teacher_id='$teacher_id'");

// Handle exam creation
if(isset($_POST['create_exam'])){
    $course_id = $_POST['course_id'];
    $title = $conn->real_escape_string($_POST['title']);
    $total_marks = (int)$_POST['total_marks'];
    $duration = (int)$_POST['duration'];

    // NEW
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    $conn->query("INSERT INTO exams(course_id, title, total_marks, duration_minutes, start_time, end_time, is_published) 
                  VALUES('$course_id', '$title', '$total_marks', '$duration', '$start_time', '$end_time', 0)");

    $message = "Exam created successfully!";
}

// Handle publish/unpublish
if(isset($_GET['action']) && isset($_GET['exam_id'])){
    $exam_id = (int)$_GET['exam_id'];
    if($_GET['action'] == 'publish'){
        $conn->query("UPDATE exams SET is_published=1 WHERE id=$exam_id");
    } elseif($_GET['action'] == 'unpublish'){
        $conn->query("UPDATE exams SET is_published=0 WHERE id=$exam_id");
    }
    header("Location: create_exam.php");
    exit();
}

// Fetch all exams
$exams_result = $conn->query("SELECT exams.*, courses.title as course_title 
                              FROM exams 
                              JOIN courses ON exams.course_id = courses.id
                              WHERE courses.teacher_id='$teacher_id'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Exam</title>
    <link rel="stylesheet" href="../includes/style.css">
    <style>
        .container { max-width:900px; margin:50px auto; background:#fff; padding:30px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1);}
        h2 { color:#1f6de0; text-align:center; margin-bottom:20px; }
        input, select { width:100%; padding:10px; margin:6px 0; border-radius:6px; border:1px solid #ccc; }
        button { background:#1f6de0; color:#fff; padding:10px 20px; border:none; border-radius:8px; cursor:pointer; margin-top:10px;}
        button:hover { background:#155ab6; }

        table { width:100%; border-collapse:collapse; margin-top:30px;}
        table, th, td { border:1px solid #ccc; }
        th, td { padding:10px; text-align:center; }
        th { background:#f0f0f0; }

        a.action-btn { background:#28a745; color:#fff; padding:5px 10px; border-radius:5px; text-decoration:none; margin:2px; display:inline-block;}
        a.action-btn:hover { background:#1e7e34; }

        .btn-publish { background:#007bff; color:#fff; padding:5px 10px; border-radius:5px; text-decoration:none;}
        .btn-unpublish { background:#dc3545; color:#fff; padding:5px 10px; border-radius:5px; text-decoration:none;}

        .status-upcoming { color:orange; font-weight:bold; }
        .status-live { color:green; font-weight:bold; }
        .status-closed { color:red; font-weight:bold; }

        .message { background:#d4edda; color:#155724; padding:10px; border-radius:6px; margin-bottom:10px; }
    </style>
</head>

<body>
<div class="container">
    <h2>Create New Exam</h2>

    <?php if($message) echo "<p class='message'>$message</p>"; ?>

    <!-- FORM -->
    <form method="POST" action="">
        <select name="course_id" required>
            <option value="">Select Course</option>
            <?php while($course = $courses_result->fetch_assoc()): ?>
                <option value="<?php echo $course['id']; ?>"><?php echo $course['title']; ?></option>
            <?php endwhile; ?>
        </select>

        <input type="text" name="title" placeholder="Exam Title" required>
        <input type="number" name="total_marks" placeholder="Total Marks" required>
        <input type="number" name="duration" placeholder="Duration (minutes)" required>

        <!-- NEW -->
        <label>Start Time</label>
        <input type="datetime-local" name="start_time" required>

        <label>End Time</label>
        <input type="datetime-local" name="end_time" required>

        <button type="submit" name="create_exam">Create Exam</button>
    </form>

    <!-- TABLE -->
    <h3>Your Exams</h3>
    <table>
        <tr>
            <th>Course</th>
            <th>Title</th>
            <th>Total Marks</th>
            <th>Duration (min)</th>
            <th>Schedule</th> <!-- NEW -->
            <th>Status</th>   <!-- NEW -->
            <th>Published</th>
            <th>Questions</th>
            <th>Actions</th>
        </tr>

        <?php while($exam = $exams_result->fetch_assoc()): 
        
        $now = date("Y-m-d H:i:s");

        if($now < $exam['start_time']){
            $status = "<span class='status-upcoming'>Upcoming</span>";
        } elseif($now > $exam['end_time']){
            $status = "<span class='status-closed'>Closed</span>";
        } else {
            $status = "<span class='status-live'>Live</span>";
        }
        ?>

        <tr>
            <td><?php echo $exam['course_title']; ?></td>
            <td><?php echo $exam['title']; ?></td>
            <td><?php echo $exam['total_marks']; ?></td>
            <td><?php echo $exam['duration_minutes']; ?></td>

            <!-- NEW -->
            <td>
                <?php echo $exam['start_time']; ?><br>
                <?php echo $exam['end_time']; ?>
            </td>

            <!-- NEW -->
            <td><?php echo $status; ?></td>

            <td><?php echo $exam['is_published'] ? 'Yes' : 'No'; ?></td>

            <td>
                <a class="action-btn" href="add_question.php?exam_id=<?php echo $exam['id']; ?>">Add Questions</a>
                <a class="action-btn" href="view_questions.php?exam_id=<?php echo $exam['id']; ?>">View Questions</a>
            </td>

            <td>
                <?php if(!$exam['is_published']): ?>
                    <a href="create_exam.php?action=publish&exam_id=<?php echo $exam['id'];?>" class="btn-publish">Publish</a>
                <?php else: ?>
                    <a href="create_exam.php?action=unpublish&exam_id=<?php echo $exam['id'];?>" class="btn-unpublish">Unpublish</a>
                <?php endif; ?>
            </td>
        </tr>

        <?php endwhile; ?>
    </table>

    <p style="margin-top:20px;"><a href="dashboard.php">Back to Dashboard</a></p>
</div>
</body>
</html>