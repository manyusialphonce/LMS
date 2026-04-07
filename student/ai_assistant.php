<?php
session_start();
include('../includes/db.php');
include('../includes/ai_config.php');
include('../includes/header.php');

$id = $_SESSION['student_id'];

// SEND MESSAGE
if(isset($_POST['send'])){
    $user_msg = $_POST['message'];

    // Save user message
    $conn->query("INSERT INTO ai_chat(student_id,message,role)
    VALUES('$id','$user_msg','user')");

    // GET LAST 10 MESSAGES (context)
    $history = [];
    $res = $conn->query("
    SELECT message,role FROM ai_chat 
    WHERE student_id='$id' 
    ORDER BY id DESC LIMIT 10
    ");

    while($row = $res->fetch_assoc()){
        $history[] = [
            "role" => $row['role'],
            "content" => $row['message']
        ];
    }

    $history = array_reverse($history);

    // CALL OPENAI API
    $data = [
        "model" => "gpt-4o-mini",
        "messages" => $history
    ];

    $ch = curl_init("https://api.openai.com/v1/chat/completions");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer $OPENAI_API_KEY"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
    $ai_msg = $result['choices'][0]['message']['content'] ?? "AI error";

    // Save AI response
    $conn->query("INSERT INTO ai_chat(student_id,message,role)
    VALUES('$id','$ai_msg','assistant')");
}

// FETCH CHAT
$res = $conn->query("SELECT * FROM ai_chat WHERE student_id='$id'");
?>

<link rel="stylesheet" href="../includes/style.css">

<div class="content">

<h2>🤖 AI Assistant</h2>

<div class="card chat-box">

<?php while($c=$res->fetch_assoc()): ?>

<?php if($c['role']=="user"): ?>
<div class="chat-msg user-msg">
<b>You:</b> <?php echo htmlspecialchars($c['message']); ?>
</div>
<?php else: ?>
<div class="chat-msg ai-msg">
<b>AI:</b> <?php echo nl2br(htmlspecialchars($c['message'])); ?>
</div>
<?php endif; ?>

<hr>

<?php endwhile; ?>

</div>

<form method="POST" class="card">
<div class="form-group">
<input type="text" name="message" placeholder="Ask anything (Swahili / English / any language)..." required>
</div>

<button class="btn" name="send">Send</button>
</form>

</div>