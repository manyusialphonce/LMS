<?php
session_start();
include('../includes/db.php');

if(isset($_POST['create'])){
    $course_id=$_POST['course_id'];
    $title=$_POST['title'];
    $link=$_POST['link'];
    $time=$_POST['start_time'];

    $conn->query("INSERT INTO lecture_rooms(course_id,title,meeting_link,start_time)
    VALUES('$course_id','$title','$link','$time')");

    echo "Lecture created successfully";
}
?>

<link rel="stylesheet" href="../includes/style.css">

<div class="content">
<div class="card">
<h2>🎥 Create Lecture Room</h2>

<form method="POST">
<input type="text" name="title" placeholder="Lecture Title" required>
<input type="text" name="link" placeholder="Zoom/Meet Link" required>
<input type="datetime-local" name="start_time" required>
<input type="hidden" name="course_id" value="1">

<button class="btn" name="create">Create Lecture</button>
</form>
</div>
</div>