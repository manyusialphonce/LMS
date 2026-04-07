<?php
session_start();
include('../includes/db.php');

$id=$_SESSION['student_id'];
$gid=$_GET['group_id'];

$res=$conn->query("
SELECT gm.*,s.full_name FROM group_messages gm
JOIN students s ON gm.student_id=s.id
WHERE group_id='$gid' ORDER BY id ASC
");

while($m=$res->fetch_assoc()):

if($m['student_id']!=$id){
$conn->query("UPDATE group_messages SET seen=1 WHERE id='{$m['id']}'");
}
?>

<div class="message <?php echo $m['student_id']==$id?'me':'other'; ?>">

<div class="name"><?php echo $m['full_name']; ?></div>

<?php if($m['reply_to']):
$r=$conn->query("SELECT message FROM group_messages WHERE id='{$m['reply_to']}'")->fetch_assoc();
echo "<div class='reply'>↪ ".$r['message']."</div>";
endif; ?>

<div><?php echo htmlspecialchars($m['message']); ?></div>

<?php if($m['file']): ?>
<a href="../uploads/<?php echo $m['file']; ?>">📎 File</a>
<?php endif; ?>

<?php if($m['voice']): ?>
<audio controls src="../uploads/<?php echo $m['voice']; ?>"></audio>
<?php endif; ?>

<div class="time">
<?php echo date("H:i",strtotime($m['created_at'])); ?>

<?php if($m['student_id']==$id){
echo $m['seen']?" ✔✔":" ✔";
} ?>
</div>

<div onclick="replyMsg(<?php echo $m['id']; ?>)">Reply</div>

</div>

<?php endwhile; ?>