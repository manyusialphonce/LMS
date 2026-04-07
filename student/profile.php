<?php
session_start();
include('../includes/db.php');



if(!isset($_SESSION['student_id'])){
    header("Location: ../login.php");
    exit();
}

$id = $_SESSION['student_id'];

// FETCH USER
$user = $conn->query("SELECT * FROM students WHERE id='$id'")->fetch_assoc();

// UPDATE PROFILE
if(isset($_POST['update'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $bio = $_POST['bio'];

    // IMAGE UPLOAD
    if(!empty($_FILES['photo']['name'])){
        $photo = time().$_FILES['photo']['name'];
        move_uploaded_file($_FILES['photo']['tmp_name'], "../uploads/".$photo);

        $conn->query("UPDATE students SET 
        name='$name', email='$email', phone='$phone', bio='$bio', photo='$photo'
        WHERE id='$id'");
    }else{
        $conn->query("UPDATE students SET 
        full_name='$name', email='$email', phone='$phone', bio='$bio'
        WHERE id='$id'");
    }

    header("Location: profile.php?success=1");
    exit();
}
if(isset($_POST['change_pass'])){
    $old = $_POST['old'];
    $new = $_POST['new'];

    $check = $conn->query("SELECT * FROM students WHERE id='$id' AND password='$old'");

    if($check->num_rows > 0){
        $conn->query("UPDATE students SET password='$new' WHERE id='$id'");
        echo "<div class='success'>Password changed</div>";
    }else{
        echo "<div class='error'>Wrong old password</div>";
    }
}
?>

<link rel="stylesheet" href="../includes/style.css">


<div class="content">

<h2>👤 My Profile</h2>

<?php if(isset($_GET['success'])): ?>
<div class="success">✅ Profile updated successfully</div>
<?php endif; ?>

<div class="profile-container">

<!-- LEFT: INFO -->
<div class="card profile-view">

<img src="../uploads/<?php echo $user['photo'] ?: 'default.png'; ?>" class="profile-img">

<h3><?php echo $user['full_name']; ?></h3>
<p>📧 <?php echo $user['email']; ?></p>
<p>📱 <?php echo $user['phone'] ?: 'No phone'; ?></p>
<p><?php echo $user['bio'] ?: 'No bio added'; ?></p>

</div>

<!-- RIGHT: EDIT -->
<div class="card profile-form">

<h3>✏️ Edit Profile</h3>

<form method="POST" enctype="multipart/form-data">

<input type="text" name="name" value="<?php echo $user['full_name']; ?>" required>

<input type="email" name="email" value="<?php echo $user['email']; ?>" required>

<input type="text" name="phone" value="<?php echo $user['phone']; ?>" placeholder="Phone">

<textarea name="bio" placeholder="About you..."><?php echo $user['bio']; ?></textarea>

<input type="file" name="photo">

<button name="update">Update Profile</button>

</form>

</div>

</div>

</div>


<div class="card">
<h3>🔒 Change Password</h3>

<form method="POST">
<input type="password" name="old" placeholder="Old Password" required>
<input type="password" name="new" placeholder="New Password" required>
<button name="change_pass">Change</button>
</form>
</div>