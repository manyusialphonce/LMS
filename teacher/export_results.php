<?php
session_start();
include('../includes/db.php');

if(!isset($_SESSION['teacher_id'])){
    header("Location: ../teacher/login.php");
    exit();
}

$teacher_id = $_SESSION['teacher_id'];

if(!isset($_GET['exam_id'])){
    echo "Invalid Exam!";
    exit();
}

$exam_id = (int)$_GET['exam_id'];

// Verify exam belongs to teacher
$exam = $conn->query("SELECT * FROM exams WHERE id='$exam_id' AND teacher_id='$teacher_id'")->fetch_assoc();
if(!$exam){
    echo "Exam not found or you don't have permission!";
    exit();
}

// Fetch results with student info
$results = $conn->query("SELECT results.*, students.full_name 
                         FROM results
                         JOIN students ON results.student_id = students.id
                         WHERE results.exam_id='$exam_id'
                         ORDER BY results.score DESC");

// Fetch violations
$violations = $conn->query("SELECT * FROM violations WHERE exam_id='$exam_id'");
$violations_array = [];
while($v = $violations->fetch_assoc()){
    $violations_array[$v['student_id']][] = $v['violation_type'];
}

// Prepare CSV
$filename = "exam_".$exam_id."_results.csv";
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="'.$filename.'"');

$output = fopen('php://output', 'w');
fputcsv($output, ['Student Name', 'Score', 'Violations']);

// Write data
while($r = $results->fetch_assoc()){
    $viol = isset($violations_array[$r['student_id']]) ? implode(", ", $violations_array[$r['student_id']]) : '-';
    fputcsv($output, [$r['full_name'], $r['score'], $viol]);
}

fclose($output);
exit();