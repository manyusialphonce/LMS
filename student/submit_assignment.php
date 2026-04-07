<?php
session_start();
include('../includes/db.php');

date_default_timezone_set("Africa/Dar_es_Salaam");

if(isset($_POST['submit'])){
    $assignment_id=$_POST['assignment_id'];
    $student_id=$_SESSION['student_id'];
    $title=$_POST['title'];

    $file=$_FILES['file']['name'];
    $tmp=$_FILES['file']['tmp_name'];

    // ✅ FILE TYPE SECURITY
    $allowed=['pdf','docx','pptx'];
    $ext=pathinfo($file,PATHINFO_EXTENSION);

    if(!in_array($ext,$allowed)){
        die("Invalid file type!");
    }

    move_uploaded_file($tmp,"../uploads/".$file);

    $conn->query("INSERT INTO assignment_submissions
    (assignment_id,student_id,title,file)
    VALUES('$assignment_id','$student_id','$title','$file')");

    header("Location: assignments.php");
}
?>