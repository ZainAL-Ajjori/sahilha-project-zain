<?php
session_start(); 
if (!isset($_SESSION['user_id'])) { $_SESSION['user_id'] = 0; }
$conn = new mysqli("localhost", "root", "", "sahilha_db");
$conn->set_charset("utf8mb4");

$type = isset($_GET['type']) ? $_GET['type'] : '';
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$location_filter = isset($_GET['location']) ? $_GET['location'] : '';

//بناء الاستعلام مع فلتر 
$sql = "SELECT * FROM services WHERE service_type = '$type'";

if (!empty($search_query)) {
    $sql .= " AND (full_name LIKE '%$search_query%' OR notes LIKE '%$search_query%')";
}

if (!empty($location_filter)) {
    $sql .= " AND location = '$location_filter'";
}

$sql .= " ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الخدمات المتاحة - سهّلها</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* قسم البحث المضاف حديثاً */
        .search-container {
            background: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .search-input, .location-select {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-family: 'Cairo', sans-serif;
            flex: 1;
            min-width: 150px;
        }
        .btn-filter {
            background: #2ECC71;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
        }
        .btn-filter:hover { background: #27ae60; }

        .services-list { display: flex; flex-direction: column; gap: 20px; margin-top: 30px; width: 100%; }
        .provider-card {
            display: flex !important; justify-content: space-between; align-items: center;
            background: white; padding: 20px; border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); cursor: pointer; transition: 0.3s;
            width: 100%; border-right: 5px solid #2ECC71;
        }
        .provider-card:hover { transform: scale(1.01); }
    </style>
</head>
<body>
<nav class="navbar">
    <div class="nav-container">
        <a href="search-service.html" class="logo">سهّلها</a>
        <ul class="nav-links">
            <li><a href="search-service.html">الرئيسية</a></li>
            <li><a href="about.html">عن الموقع</a></li>
            <li><a href="contact.html">تواصل معنا</a></li>
        </ul>
    </div>
</nav>

    <div class="search-container" style="display: flex; gap: 10px; margin-bottom: 20px;">
    <input type="text" id="liveSearch" class="search-input" placeholder="ابحث عن اسم..." onkeyup="filterServices()">
    
    <select id="locationFilter" class="location-select" onchange="filterServices()">
            <option value="">كل المناطق</option>
            <option value="gaza">مدينة غزة</option>
            <option value="khanyounis">خانيونس</option>
            <option value="deir_balah">دير البلح</option>
            <option value="nusirat">النصيرات</option>
            <option value="bureij">البريج</option>
            <option value="maghazi">المغازي</option>
            <option value="zawaida">الزوايدة</option>
            <option value="jabalia">جباليا</option>
        </select>
    </div>
    </form>

    <div class="services-list"> 
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="provider-card" onclick="location.href='details.php?id=<?php echo $row['id']; ?>'">
                    <div>
                        <h3 style="color: #2C3E50; margin: 0 0 5px 0;"><?php echo htmlspecialchars($row['full_name']); ?></h3>
                        <p style="color: #2ECC71; font-weight: bold; margin: 0;">
                            <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($row['location']); ?>
                        </p>
                    </div>
                    <div style="display: flex; align-items: center; gap: 20px;">
                        <?php 
                        $current_user = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
                        if ($current_user != 0 && isset($row['user_id']) && $row['user_id'] == $current_user): 
                        ?>
                            <a href="details.php?id=<?php echo $row['id']; ?>&action=delete" 
                               onclick="event.stopPropagation(); return confirm('هل أنت متأكد من الحذف؟');" 
                               style="color: #e74c3c; font-size: 1.2rem;">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        <?php endif; ?>
                        <i class="fas fa-chevron-left" style="color: #bdc3c7;"></i>
                    </div>
                </div> 
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align: center; color: #7f8c8d;">لا يوجد نتائج تطابق بحثك حالياً.</p>
        <?php endif; ?>
    </div> 
</div>
<script>
function filterServices() {

    let searchValue = document.getElementById('liveSearch').value.toLowerCase().trim();
    let locationValue = document.getElementById('locationFilter').value.toLowerCase().trim();
    
    let cards = document.querySelectorAll('.provider-card');

    cards.forEach(card => {
        let nameElement = card.querySelector('h3');
        let locationElement = card.querySelector('p');
        
        if (nameElement && locationElement) {
            let name = nameElement.innerText.toLowerCase();
            let location = locationElement.innerText.toLowerCase();

            
            let matchesSearch = name.indexOf(searchValue) > -1;
            let matchesLocation = (locationValue === "" || location.indexOf(locationValue) > -1);

            if (matchesSearch && matchesLocation) {
                card.style.setProperty('display', 'flex', 'important'); // إظهار
            } else {
                card.style.setProperty('display', 'none', 'important'); // إخفاء
            }
        }
    });
}
</script>
<footer>
    <div class="footer-content">
        <h3>سهّلها</h3>
        <p>دليلك الأول للوصول للخدمات في غزة.. من قلب الصعاب نبتكر الحلول.</p>
    </div>
    <div class="copyright"> 2026 منصة سهّلها - صُنع بكل فخر في غزة 🇵🇸 </div>
</footer>
</body>
</html>