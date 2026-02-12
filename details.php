<?php 
session_start(); 
$conn = new mysqli("localhost", "root", "", "sahilha_db");
$conn->set_charset("utf8mb4");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

//  الحذف 
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $current_user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
  

$conn->query("DELETE FROM messages WHERE service_id = $id");

$conn->query("DELETE FROM services WHERE id = $id AND user_id = $current_user_id");    echo "<script>alert('تم الحذف نهائياً من قاعدة البيانات'); window.location.href='search-service.html';</script>";
    exit;
}

if (isset($_POST['new_rating'])) {
    $rating = intval($_POST['new_rating']);
    $conn->query("UPDATE services SET total_rating = total_rating + $rating, rating_count = rating_count + 1 WHERE id = $id");
    echo "<script>alert('شكراً لثقتك.. تم تسجيل تقييمك!'); window.location.href='details.php?id=$id';</script>";
}

$result = $conn->query("SELECT * FROM services WHERE id = $id");
$service = $result->fetch_assoc();

if (!$service) { die("الخدمة غير موجودة!"); }

$average = 0;
if ($service['rating_count'] > 0) {
    $average = round($service['total_rating'] / $service['rating_count'], 1);
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل الخدمة - سهّلها</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; font-family: 'Cairo', sans-serif; }
        
        .header-spacing {
            text-align: center; 
            margin-top: 10px !important; 
            margin-bottom: 5px;
        }

        .details-card { 
            background: white; 
            border-radius: 20px; 
            padding: 30px; 
            margin: 15px auto 50px auto; 
            max-width: 650px; 
            border-right: 10px solid #2ECC71; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.08); 
        }

        .rating-row {
            display: flex;
            align-items: center;
            background: #fdfdfd;
            padding: 12px 15px;
            border-radius: 12px;
            border: 1px dashed #ddd;
            margin-top: 25px;
            width: 100%; 
        }

        .stars-input { margin-right: 15px; display: flex; gap: 5px; }
        .stars-input i { cursor: pointer; color: #ddd; font-size: 1.4rem; }
        .stars-input i.active { color: #f1c40f; }

        .btn-submit-rating { 
            background: #34495e; 
            color: white; 
            border: none; 
            padding: 8px 20px; 
            border-radius: 50px; 
            cursor: pointer; 
            font-size: 0.9rem;
            margin-right: auto; 
            margin-left: 0;
        }

        .rating-display { color: #f1c40f; font-size: 1.3rem; }
        .detail-item { font-size: 1.1rem; margin-bottom: 12px; color: #34495e; display: flex; align-items: center; }
        .detail-item i { color: #2ECC71; margin-left: 10px; width: 25px; }

        #authOverlay div {
            animation: slideDown 0.4s ease-out;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
        }
        @keyframes slideDown {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="nav-container">
            <a href="index.html" class="logo">سهّلها</a>
            <ul class="nav-links">
                <li><a href="search-service.html">الرئيسية</a></li>
                <li><a href="about.html">عن الموقع</a></li>
                <li><a href="contact.html">تواصل معنا</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="header-spacing">
            <h1 style="color: #2C3E50; font-size: 2rem;">بوابتك للخدمة الموثوقة</h1>
            <p style="color: #7f8c8d;">تعرف على التفاصيل،احجز موعدك وقيم التجربة.</p>
        </div>

        <div class="details-card">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2 style="color: #2C3E50; margin: 0;"><?php echo htmlspecialchars($service['full_name']); ?></h2>
                <div class="rating-display">
                    <?php 
                    for($i=1; $i<=5; $i++) {
                        echo ($i <= $average) ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                    }
                    ?>
                    <small style="color: #7f8c8d;">(<?php echo $average; ?>)</small>
                </div>
            </div>

            <hr style="border: 0.5px solid #eee; margin: 20px 0;">

            <div class="detail-item">
                <i class="fas fa-map-marker-alt"></i> 
                <strong>الموقع:</strong> 
                <span style="margin-right: 6px;"><?php echo htmlspecialchars($service['location']); ?></span>
            </div>

            <div class="detail-item">
                <i class="fas fa-money-bill-wave"></i> 
                <strong>السعر:</strong> 
                <span style="margin-right: 6px;"><?php echo htmlspecialchars($service['price']); ?></span>
            </div>

            <div class="detail-item">
                <i class="fas fa-calendar-alt"></i>
                <strong>أيام العمل:</strong>
                <span style="margin-right: 6px;"><?php echo htmlspecialchars($service['work_days']); ?></span>
            </div>
            
            <div style="background: #f9f9f9; padding: 15px; border-radius: 10px; border-right: 4px solid #2ECC71; margin-top: 15px;">
                <p><strong><i class="fas fa-info-circle"></i> ملاحظات:</strong></p>
                <p><?php echo nl2br(htmlspecialchars($service['notes'])); ?></p>
            </div>

            <form method="POST" id="ratingForm" class="rating-row">
                <span style="font-weight: bold;">قيم الخدمة:</span>
                <div class="stars-input" id="starsContainer">
                    <i class="far fa-star" data-val="1"></i>
                    <i class="far fa-star" data-val="2"></i>
                    <i class="far fa-star" data-val="3"></i>
                    <i class="far fa-star" data-val="4"></i>
                    <i class="far fa-star" data-val="5"></i>
                </div>
                <input type="hidden" name="new_rating" id="ratingValue">
                <button type="submit" class="btn-submit-rating">إرسال التقييم</button>
            </form>

            <button onclick="openBooking()" class="btn-primary" style="width: 100%; margin-top: 25px; padding: 15px; font-size: 1.2rem; border-radius: 50px;">احجز الآن / مراسلة</button>
        </div>
    </div>

    <div id="authOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index:1000;">
        <div style="background:white; width:90%; max-width:400px; margin:100px auto; padding:30px; border-radius:15px; text-align:center; position:relative;">
            <span onclick="closePopup()" style="position:absolute; left:20px; top:10px; cursor:pointer; font-size:25px;">&times;</span>
            <h3 style="color:#2c3e50;">ابدأ المحادثة الآن</h3>
            <p style="color:#7f8c8d; font-size:0.9rem;">أدخل بياناتك ليتم حفظ موعدك ورسائلك</p>
            
            <input type="text" id="reg_fullname" placeholder="اسمك الحقيقي" style="width:100%; padding:12px; margin:10px 0; border:1px solid #ddd; border-radius:8px;">
            <input type="text" id="reg_username" placeholder="اسم المستخدم (Username)" style="width:100%; padding:12px; margin:10px 0; border:1px solid #ddd; border-radius:8px;">
            <input type="password" id="reg_password" placeholder="كلمة السر" style="width:100%; padding:12px; margin:10px 0; border:1px solid #ddd; border-radius:8px;">
            
            <button onclick="handleAuth()" style="width:100%; padding:15px; background:#2ecc71; color:white; border:none; border-radius:8px; font-weight:bold; cursor:pointer; margin-top:10px;">
                تأكيد والدخول للشات
            </button>
            <div id="authError" style="color:#e74c3c; margin-top:10px; font-size:0.9rem;"></div>
        </div>
    </div>

    <script>
        function openBooking() {
            let isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
            if (isLoggedIn) {
                window.location.href = "chat.php?service_id=<?php echo $id; ?>";
            } else {
                document.getElementById('authOverlay').style.display = 'block';
            }
        }

        function closePopup() {
            document.getElementById('authOverlay').style.display = 'none';
        }

        function handleAuth() {
            let fName = document.getElementById('reg_fullname').value;
            let uName = document.getElementById('reg_username').value;
            let uPass = document.getElementById('reg_password').value;
            let errorDiv = document.getElementById('authError');

            if(uName == "" || uPass == "") {
                errorDiv.innerText = "يرجى إدخال اسم المستخدم وكلمة السر!";
                return;
            }

            let formData = new FormData();
            formData.append('full_name', fName);
            formData.append('username', uName);
            formData.append('password', uPass);

            fetch('process-auth.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
              if(data.trim() === "success") {
                window.location.href = "chat.php?service_id=<?php echo $id; ?>";
              } else {
                errorDiv.innerText = data;
              }
            });
        }

        const stars = document.querySelectorAll('#starsContainer i');
        stars.forEach(star => {
            star.onclick = function() {
                let val = this.getAttribute('data-val');
                document.getElementById('ratingValue').value = val;
                stars.forEach(s => s.classList.remove('fas', 'active'));
                stars.forEach(s => s.classList.add('far'));
                for(let i=0; i<val; i++) {
                    stars[i].classList.remove('far');
                    stars[i].classList.add('fas', 'active');
                }
            }
        });
    </script>

    <footer>
        <div class="footer-content">
            <h3>سهّلها</h3>
            <p>دليلك الأول للوصول للخدمات في غزة.. من قلب الصعاب نبتكر الحلول.</p>
            <ul class="footer-links">
                <li><a href="search-service.html">الرئيسية</a></li>
                <li><a href="about.html">عن الموقع</a></li>
                <li><a href="contact.html">تواصل معنا</a></li>
            </ul>
        </div>
        <div class="copyright">
           &copy; 2026 منصة سهّلها - صُنع بكل فخر في غزة 🇵🇸
        </div>
    </footer>
    <script src="script.js"></script>
</body>
</html>