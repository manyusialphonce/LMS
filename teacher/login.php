<?php
include('../includes/db.php');
session_start();

$message = '';

if(isset($_POST['login'])){
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM teachers WHERE email='$email'");
    if($result->num_rows == 1){
        $row = $result->fetch_assoc();
        if(password_verify($password, $row['password'])){
            $_SESSION['teacher_id'] = $row['id'];
            $_SESSION['teacher_name'] = $row['full_name'];
            header("Location: dashboard.php");
            exit();
        } else {
            $message = "Incorrect Password!";
        }
    } else {
        $message = "Email not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Teacher Login</title>
    <link rel="stylesheet" href="../includes/style.css">
</head>
<body>
<div class="container">
    <h2>Teacher Login</h2>
    <?php if($message) echo "<p class='message'>$message</p>"; ?>
    <form method="POST" action="">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>
    <p>Don't have account? <a href="register.php">Register Here</a></p>
</div>
</body>
</html>