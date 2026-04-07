<?php
session_start();
include('../includes/db.php');

if(!isset($_SESSION['student_id'])){
    header("Location: ../login.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$course_id = $_GET['course_id'] ?? 0;


// ================= POST QUESTION =================
if(isset($_POST['post'])){
    $title = $_POST['title'];
    $content = $_POST['content'];

    $conn->query("INSERT INTO forum_posts(student_id, course_id, title, content, created_at)
    VALUES('$student_id','$course_id','$title','$content',NOW())");
}


// ================= REPLY =================
if(isset($_POST['reply'])){
    $post_id = $_POST['post_id'];
    $reply = $_POST['reply_text'];

    $conn->query("INSERT INTO forum_replies(post_id, student_id, reply, created_at)
    VALUES('$post_id','$student_id','$reply',NOW())");
}


// ================= FETCH POSTS =================
$posts = $conn->query("SELECT * FROM forum_posts 
WHERE course_id='$course_id' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Forum</title>
<link rel="stylesheet" href="../includes/style.css">

<style>
.container{
    max-width:900px;
    margin:30px auto;
}

.card{
    background:#fff;
    padding:20px;
    border-radius:10px;
    margin-bottom:20px;
    box-shadow:0 4px 12px rgba(0,0,0,0.1);
}

h2{color:#1f6de0;}

input, textarea{
    width:100%;
    padding:10px;
    margin:8px 0;
    border-radius:6px;
    border:1px solid #ccc;
}

button{
    background:#1f6de0;
    color:#fff;
    border:none;
    padding:8px 15px;
    border-radius:6px;
    cursor:pointer;
}

.reply-box{
    margin-top:10px;
    padding:10px;
    background:#f4f6f9;
    border-radius:6px;
}

.reply{
    padding:8px;
    border-bottom:1px solid #ddd;
}
</style>

</head>

<body>

<div class="container">

<h2>💬 Class Forum</h2>

<!-- ================= POST FORM ================= -->
<div class="card">
<form method="POST">
    <input type="text" name="title" placeholder="Post Title" required>
    <textarea name="content" placeholder="Write something..." required></textarea>
    <button name="post">Post</button>
</form>
</div>


<!-- ================= POSTS ================= -->
<?php if($posts->num_rows == 0): ?>
<div class="card">No discussions yet</div>
<?php endif; ?>

<?php while($p = $posts->fetch_assoc()): ?>

<div class="card">

<h3><?php echo $p['title']; ?></h3>
<p><?php echo $p['content']; ?></p>
<small><?php echo date("d M Y H:i", strtotime($p['created_at'])); ?></small>

<hr>

<!-- ================= REPLIES ================= -->
<?php
$replies = $conn->query("SELECT * FROM forum_replies WHERE post_id='{$p['id']}'");
while($r = $replies->fetch_assoc()):
?>
<div class="reply">
    💬 <?php echo $r['reply']; ?>
</div>
<?php endwhile; ?>


<!-- ================= REPLY FORM ================= -->
<div class="reply-box">
<form method="POST">
    <input type="hidden" name="post_id" value="<?php echo $p['id']; ?>">
    <input type="text" name="reply_text" placeholder="Write reply..." required>
    <button name="reply">Reply</button>
</form>
</div>

</div>

<?php endwhile; ?>

</div>

</body>
</html>