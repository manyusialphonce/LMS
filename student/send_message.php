<?php
session_start();
include('../includes/db.php');

$id=$_SESSION['student_id'];

$msg=$_POST['message'] ?? '';
$gid=$_POST['group_id'];
$reply=$_POST['reply_to'] ?: NULL;

$file=NULL;
$voice=NULL;

if(!empty($_FILES['file']['name'])){
$file=time().$_FILES['file']['name'];
move_uploaded_file($_FILES['file']['tmp_name'],"../uploads/".$file);
}

if(!empty($_FILES['voice']['name'])){
$voice=time().".ogg";
move_uploaded_file($_FILES['voice']['tmp_name'],"../uploads/".$voice);
}

$conn->query("INSERT INTO group_messages(group_id,student_id,message,reply_to,file,voice)
VALUES('$gid','$id','$msg','$reply','$file','$voice')");