<?php
session_start();
$conn = new mysqli("localhost", "root", "", "sahilha_db");
$conn->set_charset("utf8mb4");

// استقبال البيانات من الفورم   
$full_name = isset($_POST['full_name']) ? $_POST['full_name'] : '';
$user = isset($_POST['username']) ? $_POST['username'] : '';
$pass = isset($_POST['password']) ? $_POST['password'] : '';

// 1. الفحص: هل اليوزر نيم موجود؟
$check = $conn->prepare("SELECT * FROM site_users WHERE username = ?");
$check->bind_param("s", $user);
$check->execute();
$result = $check->get_result();

if ($row = $result->fetch_assoc()) {
    // 2. إذا موجود: نتحقق من كلمة السر
    if ($pass == $row['password']) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_name'] = $row['full_name'];
        header("Location: search-service.html");
        exit();
    } else {
        echo "كلمة السر خاطئة لهذا المستخدم!";
    }
} else {
    // 3. إذا مش موجود: ننشئ حساب جديد
    $insert = $conn->prepare("INSERT INTO site_users (full_name, username, password) VALUES (?, ?, ?)");
    $insert->bind_param("sss", $full_name, $user, $pass);
    
    if ($insert->execute()) {
        $_SESSION['user_id'] = $conn->insert_id; 
        $_SESSION['user_name'] = $full_name;
        echo "success";
    } else {
        echo "حدث خطأ أثناء إنشاء الحساب!";
    }
}
?>