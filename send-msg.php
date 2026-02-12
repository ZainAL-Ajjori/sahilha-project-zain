<?php
session_start();
$conn = new mysqli("localhost", "root", "", "sahilha_db");

if (isset($_POST['message'])) {
    $msg = $_POST['message'];
    $service_id = $_POST['service_id'];
    
    if (isset($_POST['is_reply']) && $_POST['is_reply'] == '1') {
        $sender_user_id = $_POST['user_id']; 
        $is_from_service = 1;
    } else {
        $sender_user_id = $_SESSION['user_id'];
        $is_from_service = 0;
    }

    $stmt = $conn->prepare("INSERT INTO messages (sender_user_id, service_id, message_text, is_from_service) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iisi", $sender_user_id, $service_id, $msg, $is_from_service);
    
    if($stmt->execute()) {
        echo "sent";
    } else {
        echo "error";
    }
}
?>