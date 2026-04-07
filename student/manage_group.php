<?php
session_start();
include('../includes/db.php');

$gid=$_GET['group_id'] ?? 0;

// ✅ APPROVE LOGIC (iwe juu kabisa)
if(isset($_GET['approve'])){
    $id=$_GET['approve'];
    $conn->query("UPDATE group_members SET status='approved' WHERE id='$id'");
    header("Location: manage_group.php?group_id=$gid");
    exit();
}

// Pending requests
$res=$conn->query("
SELECT gm.*, s.full_name FROM group_members gm
JOIN students s ON gm.student_id=s.id
WHERE gm.group_id='$gid' AND gm.status='pending'
");

// Save WhatsApp link
if(isset($_POST['save_link'])){
    $link=$_POST['link'];
    $conn->query("UPDATE groups SET whatsapp_link='$link' WHERE id='$gid'");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Group</title>
<link rel="stylesheet" href="../includes/style.css">

<style>
.container{
    max-width:700px;
    margin:50px auto;
    background:#fff;
    padding:25px;
    border-radius:12px;
    box-shadow:0 6px 18px rgba(0,0,0,0.15);
}

h2{
    color:#1f6de0;
    margin-bottom:15px;
}

.form-group input{
    width:100%;
    padding:10px;
    border-radius:8px;
    border:1px solid #ccc;
    margin-bottom:10px;
}

button{
    padding:10px 20px;
    background:#1f6de0;
    color:#fff;
    border:none;
    border-radius:8px;
    cursor:pointer;
}

.request{
    padding:12px;
    margin:10px 0;
    background:#f9f9f9;
    border-radius:8px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.approve-btn{
    padding:6px 12px;
    background:green;
    color:#fff;
    border-radius:6px;
    text-decoration:none;
}
</style>

</head>
<body>

<div class="container">

<h2>🔗 WhatsApp Group Link</h2>

<form method="POST">
<div class="form-group">
<input type="text" name="link" placeholder="Paste WhatsApp group link">
</div>
<button name="save_link">Save Link</button>
</form>

<hr>

<h2>👥 Pending Requests</h2>

<?php if($res->num_rows == 0): ?>
<p>No pending requests</p>
<?php endif; ?>

<?php while($r=$res->fetch_assoc()): ?>
<div class="request">
    <span><?php echo $r['full_name']; ?></span>

    <a class="approve-btn"
    href="?group_id=<?php echo $gid; ?>&approve=<?php echo $r['id']; ?>">
    Approve
    </a>
</div>
<?php endwhile; ?>

</div>

</body>
</html>