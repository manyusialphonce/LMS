<?php
session_start();
include('../includes/db.php');

$id=$_SESSION['student_id'];

if(isset($_POST['create'])){
    $name=$_POST['name'];

    $conn->query("INSERT INTO groups(name,leader_id) VALUES('$name','$id')");
    $gid=$conn->insert_id;

    $conn->query("INSERT INTO group_members(group_id,student_id,status)
    VALUES('$gid','$id','approved')");

    header("Location: my_groups.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Create Group</title>

<link rel="stylesheet" href="../includes/style.css">

<style>
.container{
    max-width:500px;
    margin:100px auto;
    background:#fff;
    padding:30px;
    border-radius:12px;
    box-shadow:0 6px 18px rgba(0,0,0,0.15);
    text-align:center;
}

.container h2{
    color:#1f6de0;
    margin-bottom:20px;
}

.form-group{
    margin-bottom:15px;
    text-align:left;
}

.form-group input{
    width:100%;
    padding:10px;
    border-radius:8px;
    border:1px solid #ccc;
    font-size:15px;
}

button{
    width:100%;
    padding:12px;
    background:#1f6de0;
    color:#fff;
    border:none;
    border-radius:8px;
    font-size:16px;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    background:#155bb5;
}

.note{
    margin-top:15px;
    font-size:13px;
    color:#777;
}
</style>

</head>

<body>

<div class="container">

<h2>👥 Create Group</h2>

<form method="POST">

<div class="form-group">
<input type="text" name="name" placeholder="Enter Group Name" required>
</div>

<button name="create">➕ Create Group</button>

</form>

<div class="note">
Create a group and invite classmates to collaborate.
</div>

</div>

</body>
</html>