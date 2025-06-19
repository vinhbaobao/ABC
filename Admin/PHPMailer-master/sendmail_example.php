<?php
// Tải PHPMailer về, giải nén vào thư mục vendor hoặc cùng cấp file này
require 'src/PHPMailer.php';
require 'src/SMTP.php';
require 'src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // Cấu hình SMTP Gmail
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'jinbao1994@gmail.com'; // Gmail của bạn
    $mail->Password   = 'nyeu rxpt wjne ilvq';  // App password (không phải mật khẩu Gmail thường)
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    // Người gửi và người nhận (phải đúng Gmail bạn cấu hình)
    $mail->setFrom('jinbao1994@gmail.com', 'Tên của bạn');
    $mail->addAddress('jinbao1994@gmail.com', 'Tên người nhận'); // Đổi thành email bạn muốn test

    // Nội dung
    $mail->isHTML(true);
    $mail->Subject = 'Tiêu đề email';
    $mail->Body    = 'Nội dung email <b>HTML</b>';

    $mail->send();
    echo 'Gửi mail thành công!';
} catch (Exception $e) {
    echo "Không gửi được mail. Lỗi: {$mail->ErrorInfo}";
}
?>
