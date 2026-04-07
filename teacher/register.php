<?php
include('../includes/db.php');

$message = '';

if(isset($_POST['register'])){
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = $conn->query("SELECT * FROM teachers WHERE email='$email'");
    if($check->num_rows > 0){
        $message = "Email already exists!";
    } else {
        $conn->query("INSERT INTO teachers(full_name, email, password) VALUES('$full_name','$email','$password')");
        $message = "Registration successful! <a href='login.php'>Login Now</a>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Teacher Register</title>
    <link rel="stylesheet" href="../includes/style.css">
</head>
<body>
<div class="container">
    <h2>Teacher Registration</h2>
    <?php if($message) echo "<p class='message'>$message</p>"; ?>
    <form method="POST" action="">
        <input type="text" name="full_name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="register">Register</button>
    </form>
    <p>Already have account? <a href="login.php">Login Here</a></p>
</div>
</body>
</html>