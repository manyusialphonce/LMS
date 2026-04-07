<?php
session_start();
include('../includes/db.php');

$id=$_GET['id'];

if(isset($_POST['grade'])){
    $marks=$_POST['marks'];
    $feedback=$_POST['feedback'];

    $conn->query("UPDATE assignment_submissions 
    SET marks='$marks',feedback='$feedback',status='Graded'
    WHERE id='$id'");

    echo "<p style='color:green;'>Graded</p>";
}

$res=$conn->query("SELECT * FROM assignment_submissions WHERE id='$id'");
$row=$res->fetch_assoc();
?>
<link rel="stylesheet" href="../includes/style.css">
<form method="POST">

<input type="number" name="marks" placeholder="Marks" required>

<textarea name="feedback" placeholder="Feedback"></textarea>

<button name="grade">Submit</button>

<p style="margin-top:20px;"><a href="dashboard.php">Back to Dashboard</a></p>
</form>