<?php
session_start();
require('../server.php');

$sender_id = $_SESSION['id'];
$receiver_id = $_SESSION['receiver_id'];

$sql = "SELECT * FROM messages WHERE (receiver_id = '$receiver_id' AND sender_id = '$sender_id')
        OR (receiver_id = '$sender_id' AND sender_id = '$receiver_id')
        ORDER BY timestamp ASC";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $message = nl2br($row['message']);
    $time = $row['timestamp'];

    if ($sender_id == $row['sender_id']) {
        echo "
        <div class='message message-sent'>
            <div class='message-content'>$message</div>
            <div class='message-time'>$time</div>
        </div>
        ";
    } else {
        echo "
        <div class='message message-received'>
            <div class='message-content'>$message</div>
            <div class='message-time'>$time</div>
        </div>
        ";
    }
}
?>


