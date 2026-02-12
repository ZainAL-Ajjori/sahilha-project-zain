<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول - سهّلها</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { background: #f4f7f6; font-family: 'Segoe UI', sans-serif; }
        .login-container { 
            max-width: 400px; 
            margin: 80px auto; 
            background: white; 
            padding: 40px; 
            border-radius: 20px; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.1); 
            border-top: 10px solid #2ecc71; 
        }
        .form-group { margin-bottom: 20px; text-align: right; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #2c3e50; }
        input { 
            width: 100%; 
            padding: 12px; 
            border: 2px solid #eee; 
            border-radius: 10px; 
            font-size: 1rem; 
            box-sizing: border-box; 
            transition: 0.3s;
        }
        input:focus { border-color: #2ecc71; outline: none; }
        .btn-login { 
            background: #2ecc71; 
            color: white; 
            border: none; 
            padding: 15px; 
            width: 100%; 
            border-radius: 10px; 
            font-size: 1.1rem; 
            font-weight: bold;
            cursor: pointer; 
            transition: 0.3s; 
        }
        .btn-login:hover { background: #27ae60; transform: translateY(-2px); }
        .back-home { display: block; text-align: center; margin-top: 20px; color: #7f8c8d; text-decoration: none; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 style="text-align: center; color: #2c3e50;">تسجيل الدخول</h2>
        <p style="text-align: center; color: #7f8c8d; margin-bottom: 25px;">أهلاً بك مجدداً في سهّلها</p>
        
        <form action="process-auth.php" method="POST">
            <div class="form-group">
                <label>اسم المستخدم:</label>
                <input type="text" name="username" required placeholder="اسم المستخدم الخاص بخدمتك">
            </div>
            <div class="form-group">
                <label>كلمة السر:</label>
                <input type="password" name="password" required placeholder="كلمة السر">
            </div>
            <button type="submit" class="btn-login">دخول للمنصة</button>
        </form>
        
        <a href="search-service.html" class="back-home">← العودة للرئيسية</a>
    </div>
</body>
</html>