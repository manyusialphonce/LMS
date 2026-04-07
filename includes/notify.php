<?php
function notify($conn, $user_id, $message, $link){
    $conn->query("
        INSERT INTO notifications(user_id,message,link)
        VALUES('$user_id','$message','$link')
    ");
}
?>