<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الخدمات المتاحة - سهلها</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .services-list { max-width: 800px; margin: auto; }
        
        .service-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            cursor: pointer;
            transition: 0.3s;
            border: 1px solid #eee;
            text-decoration: none;
            color: inherit;
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        .service-name {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }

        .service-location {
            font-size: 1.1rem;
            color: #28a745; 
            font-weight: 500;
        }

        .service-meta {
            font-size: 0.9rem;
            color: #777;
            margin-top: 10px;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <h1 style="text-align:center; color: #2c3e50;">الخدمات المتاحة حالياً</h1>

    <div class="services-list">
        <?php
        //الاتصال بالقاعدة
        $conn = new mysqli("localhost", "root", "", "sahilha_db");
        $conn->set_charset("utf8mb4");

        if ($conn->connect_error) { die("فشل الاتصال: " . $conn->connect_error); }

        // جلب البيانات
        $sql = "SELECT * FROM services ORDER BY created_at DESC";
        $result = $conn->query($sql);

        // العرض Loop
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                // الكرت كامل عبارة عن رابط لصفحة التفاصيل
                echo '<a href="details.php?id=' . $row["id"] . '" class="service-card">';
                echo '    <div class="service-name">' . htmlspecialchars($row["full_name"]) . '</div>';
                echo '    <div class="service-location">' . htmlspecialchars($row["location"]) . '</div>';
                echo '    <div class="service-meta">';
                echo '        <span>نوع الخدمة: ' . htmlspecialchars($row["service_type"]) . '</span>';
                echo '    </div>';
                echo '</a>';
            }
        } else {
            echo "<p style='text-align:center;'>لا يوجد خدمات معروضة حالياً.</p>";
        }
        $conn->close();
        ?>
    </div>

</body>
</html>