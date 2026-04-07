<?php
session_start();
include('../includes/db.php');

if(!isset($_SESSION['teacher_id'])){
    header("Location: login.php");
    exit();
}

$message = '';

if(isset($_POST['create_course'])){
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $teacher_id = $_SESSION['teacher_id'];

    $conn->query("INSERT INTO courses(title, description, teacher_id) VALUES('$title', '$description', '$teacher_id')");
    $message = "Course created successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Course</title>
    <link rel="stylesheet" href="../includes/style.css">
</head>
<body>
<div class="container">
    <h2>Create New Course</h2>
    <?php if($message) echo "<p class='message'>$message</p>"; ?>
    <form method="POST" action="">
        <input type="text" name="title" placeholder="Course Title" required>
        <textarea name="description" placeholder="Course Description" rows="4"></textarea>
        <button type="submit" name="create_course">Create Course</button>
    </form>
    <p><a href="dashboard.php">Back to Dashboard</a></p>
</div>
</body>
</html>