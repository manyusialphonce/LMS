<?php
session_start();
include('../includes/db.php');

$student_id = $_SESSION['student_id'];
$group_id = $_GET['group_id'] ?? 0;

// update online status
$conn->query("REPLACE INTO users_online(student_id) VALUES('$student_id')");
?>

<!DOCTYPE html>
<html>
<head>
<title>Group Chat</title>

<style>
body{font-family:'Segoe UI';background:#ece5dd;margin:0;}

.chat-container{
    max-width:700px;
    margin:20px auto;
    background:#e5ddd5;
    padding:15px;
    border-radius:10px;
    height:75vh;
    overflow-y:auto;
}

.message{
    max-width:70%;
    padding:10px;
    border-radius:10px;
    margin:10px 0;
}

.me{background:#dcf8c6;margin-left:auto;}
.other{background:#fff;}

.name{font-size:12px;color:#555;}
.time{font-size:10px;text-align:right;color:#888;}

.reply{
    background:#f0f0f0;
    padding:5px;
    border-left:3px solid #1f6de0;
    margin-bottom:5px;
    font-size:12px;
}

.input-box{
    position:fixed;
    bottom:0;
    width:100%;
    max-width:700px;
    left:50%;
    transform:translateX(-50%);
    background:#fff;
    display:flex;
    gap:5px;
    padding:10px;
}

input{
    flex:1;
    padding:10px;
    border-radius:20px;
    border:1px solid #ccc;
}

button{
    padding:10px;
    border:none;
    background:#1f6de0;
    color:#fff;
    border-radius:50%;
}

.online{color:green;font-size:12px;}
.offline{color:red;font-size:12px;}
</style>
</head>

<body>

<div id="chatBox" class="chat-container"></div>

<form id="chatForm" class="input-box" enctype="multipart/form-data">
<input type="hidden" id="reply_to">

<input type="text" id="message" placeholder="Message">

<input type="file" id="file">

<button type="button" onclick="recordVoice()">🎤</button>

<button type="submit">➤</button>
</form>

<script>
let replyId="";

// reply
function replyMsg(id){
    replyId=id;
}

// SEND
document.getElementById("chatForm").addEventListener("submit", e=>{
    e.preventDefault();

    let fd = new FormData();
    fd.append("message", message.value);
    fd.append("reply_to", replyId);
    fd.append("group_id", "<?php echo $group_id; ?>");

    if(file.files[0]) fd.append("file", file.files[0]);

    fetch("send_message.php",{method:"POST",body:fd})
    .then(()=>{message.value="";file.value="";load();});
});

// LOAD
function load(){
    fetch("fetch_messages.php?group_id=<?php echo $group_id; ?>")
    .then(r=>r.text())
    .then(d=>{
        chatBox.innerHTML=d;
        chatBox.scrollTop=chatBox.scrollHeight;
    });
}

setInterval(load,2000);
load();

// VOICE
let rec, chunks=[];
function recordVoice(){
navigator.mediaDevices.getUserMedia({audio:true}).then(s=>{
rec=new MediaRecorder(s);
rec.start();

rec.ondataavailable=e=>chunks.push(e.data);

rec.onstop=()=>{
let blob=new Blob(chunks);
let fd=new FormData();
fd.append("voice",blob);
fd.append("group_id","<?php echo $group_id; ?>");

fetch("send_message.php",{method:"POST",body:fd})
.then(()=>load());

chunks=[];
};

setTimeout(()=>rec.stop(),5000);
});
}

// typing
message.addEventListener("input",()=>{
fetch("typing.php?group_id=<?php echo $group_id; ?>");
});
</script>

</body>
</html>