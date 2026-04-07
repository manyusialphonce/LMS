<?php
include('includes/db.php');

$message = '';

if(isset($_POST['register'])){
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $admission_no = $conn->real_escape_string($_POST['admission_no']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = $conn->query("SELECT * FROM students WHERE email='$email' OR admission_no='$admission_no'");
    if($check->num_rows > 0){
        $message = "Email or Admission Number already exists!";
    } else {
        $conn->query("INSERT INTO students(full_name, admission_no, email, password) VALUES('$full_name','$admission_no','$email','$password')");
        $message = "Registration successful! <a href='login.php'>Login Now</a>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Register</title>
    <link rel="stylesheet" href="includes/style.css">
</head>
<body>
<div class="container">
    <h2>Student Registration</h2>
    <?php if($message) echo "<p class='message'>$message</p>"; ?>
    <form method="POST" action="">
        <input type="text" name="full_name" placeholder="Full Name" required>
        <input type="text" name="admission_no" placeholder="Admission No" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="register">Register</button>
    </form>
    <p>Already have account? <a href="login.php">Login Here</a></p>
</div>
</body>
</html>