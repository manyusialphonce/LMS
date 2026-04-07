<?php
session_start();
include('../includes/db.php');

if(isset($_POST['submit'])){

    $course_id = $_POST['course_id'];
    $title = $_POST['title'];
    $start = $_POST['start_time'];
    $end = $_POST['end_time'];

    // Save quiz
    $conn->query("INSERT INTO exams(course_id,title,start_time,end_time)
                  VALUES('$course_id','$title','$start','$end')");

    // 🔔 Send notifications
    $students = $conn->query("SELECT student_id FROM enrollments 
                             WHERE course_id='$course_id'");

    while($s = $students->fetch_assoc()){
        $sid = $s['student_id'];

        $msg = "❓ New Quiz Available: $title";

        $conn->query("INSERT INTO notifications(student_id,message)
                      VALUES('$sid','$msg')");
    }

    echo "Quiz uploaded successfully!";
}
?>
<div class="container">
<link rel="stylesheet" href="../includes/style.css">
<form method="POST">
    <h2>❓ Create Quiz</h2>

    <input type="number" name="course_id" placeholder="Course ID" required><br><br>

    <input type="text" name="title" placeholder="Quiz Title" required><br><br>

    <label>Start Time</label><br>
    <input type="datetime-local" name="start_time" required><br><br>

    <label>End Time</label><br>
    <input type="datetime-local" name="end_time" required><br><br>

    <button name="submit">Create Quiz</button>
</form>
</div>