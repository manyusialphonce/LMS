<?php
session_start();
include('../includes/db.php');
include('../includes/header.php');



// COUNTS
$ann_count = $conn->query("SELECT COUNT(*) as t FROM course_announcements")->fetch_assoc()['t'];

$ass_count = $conn->query("SELECT COUNT(*) as t FROM assignments WHERE due_date >= NOW()")->fetch_assoc()['t'];

$group_count = $conn->query("SELECT COUNT(*) as t FROM group_assignments WHERE due_date >= NOW()")->fetch_assoc()['t'];

$tutorial_count = $conn->query("SELECT COUNT(*) as t FROM tutorials")->fetch_assoc()['t'];

$resource_count = $conn->query("SELECT COUNT(*) as t FROM course_resources")->fetch_assoc()['t'];

$quiz_count = $conn->query("SELECT COUNT(*) as t FROM quizzes")->fetch_assoc()['t'];

if(!isset($_SESSION['student_id'])){
    header("Location: ../login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Fetch available courses
$courses = $conn->query("SELECT * FROM courses");

// Fetch exams
$exams = $conn->query("SELECT exams.*, courses.title as course_title
                       FROM exams 
                       JOIN courses ON exams.course_id = courses.id");

// Fetch student's results
$results = $conn->query("SELECT results.*, exams.title as exam_title, courses.title as course_title
                         FROM results
                         JOIN exams ON results.exam_id = exams.id
                         JOIN courses ON exams.course_id = courses.id
                         WHERE results.student_id='$student_id' ORDER BY results.finished_at DESC");

// Fetch student's violations
$violations = $conn->query("SELECT violations.*, exams.title as exam_title 
                            FROM violations
                            JOIN exams ON violations.exam_id = exams.id
                            WHERE violations.student_id='$student_id' ORDER BY violations.created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../includes/style.css">
    <script src="../includes/script.js"></script>
    <style>
        body { margin:0; font-family:'Segoe UI',sans-serif; background:#f4f6f9; display:flex; }
        .sidebar { width:220px; background:#1f6de0; overflow-y: auto; color:#fff; height:100vh; position:fixed; padding-top:20px; }
        
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
    <h2>🎓 Student Panel</h2>

    <a href="dashboard.php" class="menu-item">🏠 Dashboard</a>

    <!-- COURSES -->
    <div class="menu-group">
        <div class="menu-title" onclick="toggleMenu(this)">
            📚 Courses <span class="arrow">▶</span>
        </div>

        <div class="submenu">

            <a href="assignments.php">
                📝 Individual Assignments
                <?php if($ass_count > 0): ?>
                    <span class="notif-badge"><?php echo $ass_count; ?></span>
                <?php endif; ?>
            </a>

            <a href="group_assignments.php">
                👥 Group Assignments
                <?php if($group_count > 0): ?>
                    <span class="notif-badge"><?php echo $group_count; ?></span>
                <?php endif; ?>
            </a>

            <a href="tutorials.php">
                📚 Tutorials
                <?php if($tutorial_count > 0): ?>
                    <span class="notif-badge"><?php echo $tutorial_count; ?></span>
                <?php endif; ?>
            </a>

            <a href="resources.php">
                📂 Course Materials
                <?php if($resource_count > 0): ?>
                    <span class="notif-badge"><?php echo $resource_count; ?></span>
                <?php endif; ?>
            </a>

            <a href="submitted.php">📤 My Submitted</a>

        </div>
    </div>

    <!-- EXAMS -->
    <div class="menu-group">
        <div class="menu-title" onclick="toggleMenu(this)">
            📝 Exams & Quizzes <span class="arrow">▶</span>
        </div>

        <div class="submenu">

            <a href="quizzes.php">
                ❓ Quizzes
                <?php if($quiz_count > 0): ?>
                    <span class="notif-badge"><?php echo $quiz_count; ?></span>
                <?php endif; ?>
            </a>

            <a href="result.php">📊 Results</a>

        </div>
    </div>

    <!-- COMMUNICATION -->
    <div class="menu-group">
        <div class="menu-title" onclick="toggleMenu(this)">
            💬 Communication <span class="arrow">▶</span>
        </div>

        <div class="submenu">

            <a href="announcements.php">
                📢 Announcements
                <?php if($ann_count > 0): ?>
                    <span class="notif-badge"><?php echo $ann_count; ?></span>
                <?php endif; ?>
            </a>

            <a href="forum.php">💬 Forum</a>
            <a href="classmates.php">👨‍🎓 Classmates</a>

        </div>
    </div>

    <!-- GROUPS -->
    <div class="menu-group">
        <div class="menu-title" onclick="toggleMenu(this)">
            👥 Groups <span class="arrow">▶</span>
        </div>

        <div class="submenu">
            <a href="my_groups.php">👥 My Groups</a>
            <a href="create_group.php">➕ Create Group</a>
        </div>
    </div>

    <!-- LEARNING TOOLS -->
    <div class="menu-group">
        <div class="menu-title" onclick="toggleMenu(this)">
            🚀 Learning Tools <span class="arrow">▶</span>
        </div>

        <div class="submenu">
            <a href="lecture_room.php">🎥 Lecture Rooms</a>
            <a href="calendar.php">📅 Calendar</a>
            <a href="ai_assistant.php">🤖 AI Assistant</a>
        </div>
    </div>

    <!-- OTHER -->
    <a href="violations.php" class="menu-item">⚠️ Violations</a>
    <a href="../logout.php" class="menu-item">🚪 Logout</a>
</div>
    



    <div class="content">
         <h2>Welcome to Your Dashboard</h2>
        <div class="card welcome">
    <h2>👋 Welcome, <?php echo $_SESSION['student_name'] ?? 'Student'; ?></h2>
    <p>
        Welcome to your Learning Management System (LMS). 
        Here you can access assignments, quizzes, learning materials, 
        and communicate with your classmates and instructors.
    </p>
</div>

<div class="card">
    <h3>📘 How to Use This System</h3>
    <ul>
        <li>📝 Check assignments and submit before deadline</li>
        <li>❓ Attempt quizzes on time</li>
        <li>📂 Download course materials</li>
        <li>💬 Participate in forum discussions</li>
        <li>👥 Join or create groups</li>
    </ul>
</div>

 <div class="card">
    <h3>💡 Tips for Success</h3>
    <ul>
        <li>✔ Check dashboard daily</li>
        <li>✔ Submit work before deadline</li>
        <li>✔ Join group discussions</li>
        <li>✔ Attend live classes</li>
    </ul>
</div>

<div class="card notice">
    <h3>📢 System Notice</h3>
    <p>
        Always ensure your internet connection is stable before 
        attempting quizzes or submitting assignments.
    </p>
</div>
        <div class="section" id="courses">
            <h3>Available Courses</h3>
            <table>
                <tr><th>#</th><th>Course</th></tr>
                <?php $c=1; while($course=$courses->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $c++; ?></td>
                        <td><?php echo $course['title']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <div class="section" id="exams">
            <h3>Available Exams</h3>
            <table>
                <tr><th>#</th><th>Exam</th><th>Course</th><th>Duration</th><th>Action</th></tr>
                <?php $c=1; while($exam=$exams->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $c++; ?></td>
                        <td><?php echo $exam['title']; ?></td>
                        <td><?php echo $exam['course_title']; ?></td>
                        <td><?php echo $exam['duration_minutes']; ?> min</td>
                        <td><a class="button" href="../student/exam.php?exam_id=<?php echo $exam['id']; ?>">Start Exam</a></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <div class="section" id="results">
            <h3>Your Results</h3>
            <table>
                <tr><th>#</th><th>Exam</th><th>Course</th><th>Score</th><th>Date</th></tr>
                <?php $c=1; while($r=$results->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $c++; ?></td>
                        <td><?php echo $r['exam_title']; ?></td>
                        <td><?php echo $r['course_title']; ?></td>
                        <td><?php echo $r['score']; ?></td>
                        <td><?php echo $r['finished_at']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>

        
<div class="card">
    <h3>🤖 Need Help?</h3>
    <p>Use AI Assistant to get help with your studies anytime.</p>
    <a href="ai_assistant.php" class="btn">Ask AI</a>
</div>

        <div class="section" id="violations">
            <h3>Anti-Cheating Violations</h3>
            <table>
                <tr><th>#</th><th>Exam</th><th>Type</th><th>Time</th></tr>
                <?php $c=1; while($v=$violations->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $c++; ?></td>
                        <td><?php echo $v['exam_title']; ?></td>
                        <td><?php echo $v['violation_type']; ?></td>
                        <td><?php echo $v['created_at']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>

    </div>

</body>
</html>