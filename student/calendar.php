<?php
session_start();
include('../includes/db.php');

$res=$conn->query("SELECT * FROM calendar_events ORDER BY event_date ASC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Calendar</title>
<style>
.container{max-width:800px;margin:40px auto;}
.event{
    background:#fff;
    padding:20px;
    border-radius:10px;
    margin-bottom:10px;
    box-shadow:0 4px 10px rgba(0,0,0,0.1);
}
.date{color:#1f6de0;font-weight:bold;}
.no-data{
    text-align:center;
    margin-top:100px;
    background:#fff;
    padding:40px;
    border-radius:12px;
}
</style>
</head>

<body>

<div class="container">
<h2>📅 Calendar</h2>

<?php if($res->num_rows==0): ?>
<div class="no-data">No events available</div>
<?php endif; ?>

<?php while($e=$res->fetch_assoc()): ?>
<div class="event">
    <div class="date"><?php echo date("d M Y H:i",strtotime($e['event_date'])); ?></div>
    <h3><?php echo $e['title']; ?></h3>
    <p>Type: <?php echo $e['type']; ?></p>
</div>
<?php endwhile; ?>

</div>

</body>
</html>