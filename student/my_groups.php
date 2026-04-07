<?php
session_start();
include('../includes/db.php');

$id = $_SESSION['student_id'];

// Groups joined
$my = $conn->query("
SELECT g.* FROM groups g
JOIN group_members gm ON g.id = gm.group_id
WHERE gm.student_id='$id' AND gm.status='approved'
");

// Available groups
$all = $conn->query("
SELECT * FROM groups 
WHERE id NOT IN (
    SELECT group_id FROM group_members WHERE student_id='$id'
)");
?>

<!DOCTYPE html>
<html>
<head>
<title>My Groups</title>
<link rel="stylesheet" href="../includes/style.css">
<style>
.container{max-width:900px;margin:40px auto;}
.card{
    background:#fff;padding:20px;border-radius:12px;
    margin-bottom:15px;
    box-shadow:0 4px 10px rgba(0,0,0,0.1);
}
.btn{
    padding:6px 12px;background:#1f6de0;color:#fff;
    border-radius:6px;text-decoration:none;
}
</style>
</head>

<body>

<div class="container">

<h2>👥 My Groups</h2>

<a href="create_group.php" class="btn">➕ Create Group</a>

<h3>Joined Groups</h3>

<?php if($my->num_rows==0): ?>
<div class="card">You have not joined any group</div>
<?php endif; ?>

<?php while($g=$my->fetch_assoc()): ?>
<div class="card">
    <strong><?php echo $g['name']; ?></strong><br>
    <a href="manage_group.php?group_id=<?php echo $g['id']; ?>">Open</a>
</div>
<?php endwhile; ?>


<h3>Available Groups</h3>

<?php while($g=$all->fetch_assoc()): ?>
<div class="card">
    <strong><?php echo $g['name']; ?></strong><br>
    <a href="join_group.php?id=<?php echo $g['id']; ?>" class="btn">Join</a>
    <a href="group_chat.php?group_id=<?php echo $g['id']; ?>" class="btn">
💬 Open Chat
</a>
</div>
<?php endwhile; ?>

</div>

</body>
</html>