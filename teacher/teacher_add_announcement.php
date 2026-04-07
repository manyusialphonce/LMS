<?php
session_start();
include('../includes/db.php');

if(isset($_POST['submit'])){

    $course_id = $_POST['course_id'];
    $title = $_POST['title'];
    $message = $_POST['message'];

    // Save announcement
    $conn->query("INSERT INTO course_announcements(course_id,title,message)
                  VALUES('$course_id','$title','$message')");

    // 🔔 Send notifications to students of that course
    $students = $conn->query("SELECT student_id FROM enrollments 
                             WHERE course_id='$course_id'");

    while($s = $students->fetch_assoc()){
        $sid = $s['student_id'];

        $msg = "📢 New Announcement: $title";

        $conn->query("INSERT INTO notifications(student_id,message)
                      VALUES('$sid','$msg')");
    }

    echo "Announcement posted successfully!";
}
?>
<div class="container">
<link rel="stylesheet" href="../includes/style.css">
<form method="POST">
    <h2>📢 Add Announcement</h2>

    <input type="number" name="course_id" placeholder="Course ID" required><br><br>

    <input type="text" name="title" placeholder="Title" required><br><br>

    <textarea name="message" placeholder="Message" required></textarea><br><br>

    <button name="submit">Post Announcement</button>
</form>
</div>