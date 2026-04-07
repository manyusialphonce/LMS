<?php
include('../includes/db.php');

if(isset($_POST['upload'])){
    $title=$_POST['title'];
    $desc=$_POST['description'];
    $due=$_POST['due_date'];

    $file=$_FILES['file']['name'];
    $tmp=$_FILES['file']['tmp_name'];

    move_uploaded_file($tmp,"../uploads/".$file);

    $conn->query("INSERT INTO assignments(title,description,file,due_date)
                  VALUES('$title','$desc','$file','$due')");

    echo "<p style='color:green;text-align:center;'>Assignment Uploaded</p>";
}
?>

<link rel="stylesheet" href="../includes/style.css">

<div class="assignment-card">
<h2>Upload Assignment</h2>

<form method="POST" enctype="multipart/form-data">
<input type="text" name="title" placeholder="Title" required>
<textarea name="description" placeholder="Description"></textarea>
<input type="datetime-local" name="due_date" required>
<input type="file" name="file" required>
<button name="upload">Upload</button>
<p><a class="view-btn" href="dashboard.php">Back to Dashboard</a></p>
</form>
</div>