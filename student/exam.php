<?php
session_start();
include('../includes/db.php');

date_default_timezone_set("Africa/Dar_es_Salaam");

if(!isset($_SESSION['student_id'])){
    header("Location: ../login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

if(!isset($_GET['exam_id'])){
    echo "Invalid Exam!";
    exit();
}

$exam_id = (int)$_GET['exam_id'];

// ================= ALREADY SUBMITTED =================
$res = $conn->query("SELECT * FROM results WHERE student_id='$student_id' AND exam_id='$exam_id'");
if($res->num_rows > 0){
    $row = $res->fetch_assoc();
    echo "
    <div class='card'>
        <h2>📌 Exam Already Submitted</h2>
        <p>Your Score: <strong>{$row['score']}</strong></p>
        <a class='btn' href='dashboard.php'>Back to Dashboard</a>
    </div>

    <style>
    .card{
        max-width:700px;
        margin:100px auto;
        padding:40px;
        background:#fff;
        border-radius:15px;
        text-align:center;
        box-shadow:0 8px 25px rgba(0,0,0,0.2);
        font-family:'Segoe UI';
    }
    .card h2{
        font-size:36px;
        color:#1f6de0;
        margin-bottom:20px;
    }
    .card p{
        font-size:24px;
        margin-bottom:25px;
    }
    .card .btn{
        display:inline-block;
        padding:12px 25px;
        background:#1f6de0;
        color:#fff;
        text-decoration:none;
        font-size:20px;
        border-radius:10px;
    }
    </style>
    ";
    exit();
}

// ================= FETCH EXAM =================
$exam = $conn->query("SELECT * FROM exams WHERE id='$exam_id'")->fetch_assoc();
if(!$exam){
    echo "Exam not found!";
    exit();
}

$current_time = time();
$start_time = strtotime($exam['start_time']);
$end_time = strtotime($exam['end_time']);

// ================= BEFORE START =================
if($current_time < $start_time){
?>
<!DOCTYPE html>
<html>
<head>
<title>Upcoming Exam</title>
<style>
body{font-family:'Segoe UI';background:#f4f6f9;}
.box{
    max-width:700px;
    margin:100px auto;
    padding:40px;
    background:#fff;
    border-radius:15px;
    text-align:center;
    box-shadow:0 8px 25px rgba(0,0,0,0.2);
}
.box h2{
    font-size:38px;
    color:#1f6de0;
    margin-bottom:20px;
}
.box p{
    font-size:22px;
    margin-bottom:15px;
}
.count{
    font-size:32px;
    font-weight:bold;
    color:green;
    margin-bottom:15px;
}
.waiting{
    font-size:20px;
    color:#555;
    margin-top:20px;
}
</style>
</head>
<body>

<div class="box">
<h2>📢 Upcoming Exam</h2>
<p><?php echo $exam['announcement'] ?: "Your exam will be available soon. Please wait..."; ?></p>
<p><strong>Start Time:</strong> <?php echo date("d M Y H:i",$start_time); ?></p>
<div class="count">⏳ Starts in: <span id="cd"></span></div>
<div class="waiting">Please stay on this page. Exam will start automatically.</div>
</div>

<script>
let start = <?php echo $start_time * 1000; ?>;
let interval = setInterval(()=>{
    let now = new Date().getTime();
    let diff = start - now;

    if(diff <= 0){
        clearInterval(interval);
        location.reload();
    }

    let m = Math.floor(diff/(1000*60));
    let s = Math.floor((diff%(1000*60))/1000);

    document.getElementById("cd").innerHTML = m+"m "+s+"s";
},1000);
</script>

</body>
</html>
<?php exit(); }

// ================= AFTER END =================
if($current_time >= $end_time){
    echo "
    <div class='card expired'>
        <h2>❌ Exam Expired</h2>
        <p>This exam is no longer available.</p>
        <a class='btn' href='dashboard.php'>Back to Dashboard</a>
    </div>

    <style>
    .card.expired{
        max-width:700px;
        margin:100px auto;
        padding:40px;
        background:#fff;
        border-radius:15px;
        text-align:center;
        box-shadow:0 8px 25px rgba(0,0,0,0.2);
        font-family:'Segoe UI';
    }
    .card.expired h2{
        font-size:36px;
        color:red;
        margin-bottom:20px;
    }
    .card.expired p{
        font-size:24px;
        margin-bottom:25px;
    }
    .card.expired .btn{
        display:inline-block;
        padding:12px 25px;
        background:#1f6de0;
        color:#fff;
        text-decoration:none;
        font-size:20px;
        border-radius:10px;
    }
    </style>
    ";
    exit();
}

// ================= ACTIVE EXAM =================
$qres = $conn->query("SELECT * FROM questions WHERE exam_id='$exam_id'");
$questions = [];
while($q = $qres->fetch_assoc()){ $questions[] = $q; }

$remaining_seconds = $end_time - time();

if(isset($_POST['submit_exam'])){
    $score = 0;
    foreach($questions as $q){
        $ans = $_POST['answer_'.$q['id']] ?? '';
        if($ans === $q['correct_option']) $score++;
    }

    $conn->query("INSERT INTO results(student_id, exam_id, score, finished_at)
                  VALUES('$student_id','$exam_id','$score',NOW())");

    header("Location: result.php?exam_id=$exam_id");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title><?php echo $exam['title']; ?></title>
<style>
body{font-family:'Segoe UI';background:#f4f6f9;}
.container{
    max-width:800px;margin:40px auto;
    background:#fff;padding:25px;border-radius:12px;
    box-shadow:0 6px 18px rgba(0,0,0,0.15);
}
.container h2{
    font-size:32px;color:#1f6de0;margin-bottom:15px;text-align:center;
}
.timer{color:red;font-size:22px;text-align:right;margin-bottom:15px;}
.warning{
    background:#ffc107;
    padding:10px;
    border-radius:8px;
    margin-bottom:15px;
    display:none;
    font-size:18px;
}
.q{
    margin-bottom:20px;
    padding:15px;
    border:1px solid #ddd;
    border-radius:10px;
    box-shadow:0 3px 10px rgba(0,0,0,0.1);
}
.opt label{display:block;margin:5px 0;font-size:18px;}
.opt span{
    padding:6px;
    border-radius:5px;
    display:inline-block;
}
input:checked + span{background:#1f6de0;color:#fff;}
button{
    padding:12px 25px;background:#1f6de0;color:#fff;border:none;border-radius:8px;
    font-size:20px;margin-top:10px;cursor:pointer;
}
</style>
</head>
<body>

<div class="container">
<h2><?php echo $exam['title']; ?></h2>
<div class="timer">⏱ <span id="timer"></span></div>
<div class="warning" id="warnBox">⚠️ Only 1 minute remaining!</div>

<form method="POST" id="examForm">
<?php foreach($questions as $q): ?>
<div class="q">
    <h4><?php echo $q['question_text']; ?></h4>
    <div class="opt">
        <label><input type="radio" name="answer_<?php echo $q['id']; ?>" value="A" required> <span>A. <?php echo $q['option_a']; ?></span></label>
        <label><input type="radio" name="answer_<?php echo $q['id']; ?>" value="B" required> <span>B. <?php echo $q['option_b']; ?></span></label>
        <label><input type="radio" name="answer_<?php echo $q['id']; ?>" value="C" required> <span>C. <?php echo $q['option_c']; ?></span></label>
        <label><input type="radio" name="answer_<?php echo $q['id']; ?>" value="D" required> <span>D. <?php echo $q['option_d']; ?></span></label>
    </div>
</div>
<?php endforeach; ?>
<button type="submit" name="submit_exam">Submit</button>
</form>
</div>

<script>
let timer = <?php echo $remaining_seconds; ?>;
let warned = false;

let interval = setInterval(()=>{
    if(timer <= 60 && !warned){
        warned = true;
        document.getElementById("warnBox").style.display = "block";
        alert("⚠️ Only 1 minute remaining!");
    }

    if(timer <= 0){
        clearInterval(interval);
        alert("⏰ Time is up!");
        document.getElementById("examForm").submit();
    }

    let m = Math.floor(timer/60);
    let s = timer%60;
    document.getElementById("timer").innerHTML = m + ":" + (s<10?'0':'') + s;
    timer--;
},1000);

// Anti-cheating
let warnings = 0;
document.addEventListener("visibilitychange", ()=>{
    if(document.hidden){
        warnings++;
        alert("⚠️ Tab switch detected ("+warnings+")");
        if(warnings >= 3){
            alert("❌ Exam submitted due to cheating!");
            document.getElementById("examForm").submit();
        }
    }
});

// Disable actions
document.addEventListener("copy", e=>e.preventDefault());
document.addEventListener("paste", e=>e.preventDefault());
document.addEventListener("contextmenu", e=>e.preventDefault());

// Prevent refresh
window.onbeforeunload = function(){ return "Leaving will submit exam!"; };

// Idle submit
let idle = 0;
setInterval(()=>{
    idle++;
    if(idle > 120){ alert("Inactive! Submitting..."); document.getElementById("examForm").submit(); }
},1000);
document.onmousemove = document.onkeypress = ()=>{ idle=0; };
</script>

</body>
</html>