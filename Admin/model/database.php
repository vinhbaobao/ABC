<?php
// Khai báo thông tin kết nối database
$host = 'localhost'; // Địa chỉ máy chủ MySQL
$dbname = 'danhom5'; // Tên cơ sở dữ liệu
$username = 'root'; // Tên người dùng MySQL
$password = ''; // Mật khẩu MySQL (nếu có)

try {
    // Tạo đối tượng PDO để kết nối database
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
    $options = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    );
    $db = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    // Xử lý lỗi kết nối database
    $error_message = $e->getMessage();
    include('database_error.php');
    exit();
}
?>
