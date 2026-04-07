<?php
session_start();
include('../includes/db.php');

if(!isset($_SESSION['teacher_id'])){
    header("Location: ../teacher/login.php");
    exit();
}

$teacher_id = $_SESSION['teacher_id'];

// Fetch courses by this teacher
$courses = $conn->query("SELECT * FROM courses WHERE teacher_id='$teacher_id'");

// Fetch exams created by this teacher
$exams = $conn->query("SELECT exams.*, courses.title as course_title 
                       FROM exams 
                       JOIN courses ON exams.course_id = courses.id
                       WHERE exams.teacher_id='$teacher_id'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="../includes/style.css">
    <style>
        body { margin:0; font-family:'Segoe UI',sans-serif; display:flex; background:#f4f6f9; }
        .sidebar { width:220px; background:#1f6de0; color:#fff; height:100vh; position:fixed; padding-top:20px; overflow-y: auto; }
        .sidebar h2 { text-align:center; margin-bottom:20px; font-size:22px; }
        .sidebar a { display:block; padding:12px 20px; color:#fff; text-decoration:none; margin:5px 0; border-radius:6px; }
        .sidebar a:hover { background:#155ab6; }
        .content { margin-left:220px; padding:30px; width:100%; }
        h2 { color:#1f6de0; margin-bottom:20px; }
        table { width:100%; border-collapse:collapse; margin-bottom:30px; background:#fff; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.05); }
        th, td { padding:12px; border-bottom:1px solid #ddd; text-align:left; }
        th { background:#1f6de0; color:#fff; border-radius:12px 12px 0 0; }
        .button { background:#1f6de0; color:#fff; padding:8px 15px; border-radius:8px; text-decoration:none; }
        .button:hover { background:#155ab6; }
        .section { margin-bottom:40px; }
        .section h3 { color:#333; margin-bottom:15px; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>Teacher Panel</h2>
        <a href="dashboard.php">Dashboard</a>
        <a href="create_course.php">Create A New Course</a>
        <a href="#courses">My Courses</a>
        <a href="create_exam.php">create Exams</a>
        <a href="view_exam.php">Exams Created</a>
        <a href="add_assignment.php">Assignments</a>
        <a href="view_submissions.php">Assignment Submissions</a>
        <a href="results.php">Results</a>
        <a href="analytics.php">Analytics</a>
        <a href="../logout.php">Logout</a>
    </div>

    <div class="content">
        <h2>Welcome to Your Dashboard</h2>

        <div class="section" id="courses">
            <h3>My Courses</h3>
            <table>
                <tr><th>#</th><th>Course</th><th>Action</th></tr>
                <?php $c=1; while($course=$courses->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $c++; ?></td>
                        <td><?php echo $course['title']; ?></td>
                        <td><a class="button" href="course_exams.php?course_id=<?php echo $course['id']; ?>">View Exams</a></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <div class="section" id="exams">
            <h3>Exams Created</h3>
            <table>
                <tr><th>#</th><th>Exam</th><th>Course</th><th>Duration</th><th>Action</th></tr>
                <?php $c=1; while($exam=$exams->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $c++; ?></td>
                        <td><?php echo $exam['title']; ?></td>
                        <td><?php echo $exam['course_title']; ?></td>
                        <td><?php echo $exam['duration_minutes']; ?> min</td>
                        <td><a class="button" href="analytics.php?exam_id=<?php echo $exam['id']; ?>">View Analytics</a></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>

    </div>

</body>
</html>