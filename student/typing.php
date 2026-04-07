<?php
session_start();
include('../includes/db.php');

$id=$_SESSION['student_id'];

$conn->query("REPLACE INTO users_online(student_id) VALUES('$id')");