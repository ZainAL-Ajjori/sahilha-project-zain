<?php
session_start();
session_unset(); // مسح البيانات
session_destroy(); // تدمير الجلسة

//يرجع لصفحة الاندكس بعد تسجيل الخروج
header("Location: index.html"); 
exit();
?>