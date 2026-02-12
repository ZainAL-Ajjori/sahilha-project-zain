<?php
session_start();
$conn = new mysqli("localhost", "root", "", "sahilha_db");
$conn->set_charset("utf8mb4");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name    = $_POST['full_name'];
    $service_type = $_POST['service_type'];
    $location     = $_POST['location'];
    $price        = $_POST['price'];
    $work_days    = $_POST['work_days'];
    $notes        = $_POST['notes'];
    $username     = $_POST['username'];
    $password     = $_POST['password'];

    //التحقق هل ال username موجود في قاعدة البيانات
    $checkUser = $conn->prepare("SELECT id FROM site_users WHERE username = ?");
    $checkUser->bind_param("s", $username);
    $checkUser->execute();
    $res = $checkUser->get_result();

    if ($row = $res->fetch_assoc()) {
        $user_id = $row['id'];
    } else {
        $insertUser = $conn->prepare("INSERT INTO site_users (full_name, username, password) VALUES (?, ?, ?)");
        $insertUser->bind_param("sss", $full_name, $username, $password);
        $insertUser->execute();
        $user_id = $conn->insert_id;
    }

    //حفظ بيانات المستخدم في الجلسة
    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_name'] = $full_name;

    // وضع الخدمة في قاعدة البيانات
    $sql = "INSERT INTO services (full_name, service_type, location, price, work_days, notes, username, password, user_id) 
            VALUES ('$full_name', '$service_type', '$location', '$price', '$work_days', '$notes', '$username', '$password', '$user_id')";
            
    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('تم نشر خدمتك بنجاح وتفعيل حسابك!');
                window.location.href='category.php?type=$service_type';
              </script>";
    } else {
        echo "خطأ في الإرسال: " . $conn->error;
    }
}
$conn->close();
?>