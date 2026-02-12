<?php
session_start(); 
$conn = new mysqli("localhost", "root", "", "sahilha_db");
$conn->set_charset("utf8mb4");

$messages = [];
$service_info = null;
$error = "";

if (isset($_POST['login'])) {
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    
    // البحث باستخدام اليوزر نيم وكلمة السر 
    $stmt = $conn->prepare("SELECT * FROM services WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $user, $pass);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $service_info = $row;
        $_SESSION['service_id'] = $row['id']; 

        // احضار الرسائل  من قاعدة البيانات 
        $my_id = $row['id'];
        $msg_query = $conn->query("SELECT messages.*, site_users.full_name as sender_name 
                                   FROM messages 
                                   JOIN site_users ON messages.sender_user_id = site_users.id 
                                   WHERE messages.service_id = $my_id 
                                   ORDER BY messages.created_at DESC");
        
        while($msg_row = $msg_query->fetch_assoc()) {
            $messages[] = $msg_row;
        }

    } else {
        $error = "اسم المستخدم أو كلمة السر غير صحيحة!";
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سهّلها - اكتشف الخدمات</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">

    <nav class="navbar">
    <div class="nav-container">
        <a href="index.html" class="logo">سهّلها</a>
        <ul class="nav-links">
            <li><a href="search-service.html">الرئيسية</a></li>
            <li><a href="about.html">عن الموقع</a></li>
            <li><a href="contact.html">تواصل معنا</a></li>
            <li><a href="dashboard.php" class="dashboard-link"><i class="fas fa-tasks"></i> الرسائل</a></li>
    </div>

    <title>لوحة تحكم مقدم الخدمة</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: whites; min-height: 100vh; margin: 0; font-family: 'Cairo', sans-serif; }
        .dashboard-container { max-width: 600px; margin: 50px auto; margin-top: 20px; padding: 20px; }
        .login-box { background: white; padding: 30px; border-radius: 15px; text-align: center; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .message-card { background: white; padding: 15px; border-radius: 10px; margin-bottom: 15px; border-right: 5px solid #2ecc71; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        .msg-time { font-size: 0.8rem; color: #999; }
        .btn-primary { background: #2ecc71; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 1rem; }
        .btn-primary:hover { background: #27ae60; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <?php if (!$service_info): ?>
            <div class="login-box">
                <h2><i class="fas fa-user-shield"></i> دخول مقدم الخدمة</h2>
                <p>أدخل اسم المستخدم وكلمة السر الخاصة بخدمتك لمشاهدة رسائل الزبائن</p>
                <?php if($error) echo "<p style='color:red'>$error</p>"; ?>
                <form method="POST">
                    <input type="text" name="user" required placeholder="اسم المستخدم" style="width:80%; padding:10px; margin-bottom:10px; border:1px solid #ddd; border-radius:5px;">
                    <input type="password" name="pass" required placeholder="كلمة السر" style="width:80%; padding:10px; margin-bottom:20px; border:1px solid #ddd; border-radius:5px;">
                    <button type="submit" name="login" class="btn-primary" style="width:80%;">دخول</button>
                </form>
            </div>
        <?php else: ?>
            <h2 style="margin-bottom:20px;">أهلاً <?php echo $service_info['full_name']; ?> 👋</h2>
            <h3>الرسائل الواردة (<?php echo count($messages); ?>)</h3>
            <hr>
            
            <?php if(empty($messages)): ?>
                <p style="text-align:center; padding:20px; color:#7f8c8d;">لا توجد رسائل جديدة حالياً.</p>
            <?php endif; ?>

            <?php foreach($messages as $m): ?>
               <a href="service_chat.php?user_id=<?php echo $m['sender_user_id']; ?>" style="text-decoration: none; color: inherit;">
               <div class="message-card" style="cursor: pointer; transition: 0.3s;" onmouseover="this.style.background='#f0fdf4'" onmouseout="this.style.background='white'">
               <div style="display:flex; justify-content:space-between;">
               <strong><i class="fas fa-user"></i> <?php echo htmlspecialchars($m['sender_name']); ?></strong>
               <span class="msg-time"><?php echo $m['created_at']; ?></span>
            </div>
            <p style="margin-top:10px; color:#555;"><?php echo nl2br(htmlspecialchars($m['message_text'])); ?></p>
            <div style="text-align: left; color: #2ecc71; font-size: 0.8rem;">
                <i class="fas fa-reply"></i> اضغط للرد على الزبون
            </div>
        </div>
    </a>
<?php endforeach; ?>

            <a href="dashboard.php" style="display:inline-block; margin-top:20px; color:#e74c3c; text-decoration:none;"><i class="fas fa-sign-out-alt"></i> تسجيل الخروج</a>
        <?php endif; ?>
    </div>
</body>
</html>