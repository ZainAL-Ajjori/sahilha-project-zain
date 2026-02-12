<?php
session_start();
$conn = new mysqli("localhost", "root", "", "sahilha_db");
$conn->set_charset("utf8mb4");

if (!isset($_SESSION['service_id'])) {
    die("يرجى تسجيل الدخول أولاً كصاحب خدمة.");
}

$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
$service_id = $_SESSION['service_id'];

//اسم الزبون
$user_res = $conn->query("SELECT full_name FROM site_users WHERE id = $user_id");
$user_data = $user_res->fetch_assoc(); 

//المحادثة كاملة
$msgs = $conn->query("SELECT * FROM messages 
                      WHERE sender_user_id = $user_id 
                      AND service_id = $service_id 
                      ORDER BY created_at ASC");
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الرد على <?php echo htmlspecialchars($user_data['full_name'] ?? 'مستخدم'); ?></title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>

    body { background: #f4f7f6; font-family: 'Cairo', sans-serif; margin: 0; }
    .chat-container { max-width: 500px; margin: 0 auto; height: 100vh; display: flex; flex-direction: column; background: #fff; }

    .chat-header { 
        background: #0e9d47ff; 
        color: white; padding: 20px; display: flex; align-items: center; gap: 15px;
        border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;
    }

    .messages-area { 
        flex: 1; padding: 20px; overflow-y: auto; display: flex; flex-direction: column; 
        gap: 15px; background: #fdfdfd; 
    }

    .msg { 
        padding: 12px 18px; font-size: 0.95rem; max-width: 85%; line-height: 1.6; 
        position: relative; box-shadow: 0 4px 6px rgba(0,0,0,0.05); 
    }

    .msg.sent { 
        background: #0e9d47ff; color: #fff; align-self: flex-end; 
        border-radius: 20px 20px 0px 20px; 
    }

    .msg.received { 
        background: #333333; 
        color: #ffffff;     
        align-self: flex-start; 
        border-radius: 20px 20px 20px 0px; 
        box-shadow: 0 4px 6px rgba(0,0,0,0.1); 
    }

    .chat-footer { padding: 15px; background: #fff; display: flex; gap: 10px; border-top: 1px solid #eee; }
    .chat-footer input { flex: 1; padding: 14px; border-radius: 12px; border: 1px solid #e0e0e0; outline: none; background: #f9f9f9; }
    .chat-footer button { background: #0e9d47ff; color: white; border: none; padding: 12px 20px; border-radius: 12px; cursor: pointer; }

   //للموبايل
    @media (max-width: 600px) {
        body {
            zoom: 1.0 !important;
            -moz-transform: scale(1.0) !important;
            width: 100% !important;
            overflow-x: hidden;
        }
    }
</style>
</head>
<body>
<div class="chat-container">
    <div class="chat-header">
        <a href="dashboard.php" style="color:white;"><i class="fas fa-arrow-right"></i></a>
        <h4>المحادثة مع: <?php echo htmlspecialchars($user_data['full_name'] ?? 'غير معروف'); ?></h4>
    </div>
    
    <div class="messages-area" id="chatBox">
        <?php while($m = $msgs->fetch_assoc()): ?>
            <div class="msg <?php echo ($m['is_from_service'] == 1) ? 'sent' : 'received'; ?>">
                <?php echo htmlspecialchars($m['message_text']); ?>
            </div>
        <?php endwhile; ?>
    </div>

    <div class="chat-footer">
        <input type="text" placeholder="اكتب ردك هنا..." id="replyMsg">
        <button onclick="sendReply()"><i class="fas fa-paper-plane"></i></button>
    </div>
</div>

<script>
    const chatBox = document.getElementById('chatBox');
    chatBox.scrollTop = chatBox.scrollHeight;

    function sendReply() {
        let input = document.getElementById('replyMsg');
        let message = input.value.trim();
        if(message == "") return;

        let formData = new FormData();
        formData.append('message', message);
        formData.append('service_id', <?php echo $service_id; ?>);
        formData.append('is_reply', '1');
        formData.append('user_id', <?php echo $user_id; ?>);

        fetch('send-msg.php', { method: 'POST', body: formData })
        .then(res => res.text())
        .then(data => {
            if(data.trim() === "sent") {
                let msgDiv = document.createElement('div');
                msgDiv.className = 'msg sent';
                msgDiv.innerText = message;
                chatBox.appendChild(msgDiv);
                input.value = "";
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        });
    }
</script>
</body>
</html>