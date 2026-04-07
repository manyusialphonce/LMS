<?php
session_start();
include('../includes/db.php');

if(!isset($_SESSION['student_id'])) exit();
$student_id = $_SESSION['student_id'];

if(isset($_POST['exam_id'], $_POST['violation_type'])){
    $exam_id = (int)$_POST['exam_id'];
    $violation_type = $conn->real_escape_string($_POST['violation_type']);
    $conn->query("INSERT INTO violations(student_id, exam_id, violation_type) VALUES('$student_id','$exam_id','$violation_type')");
}
?>