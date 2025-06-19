<?php 
session_start();
require_once 'model/user_db.php'; // Để dùng hàm them_user nếu cần

require_once __DIR__ . '/PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer-master/src/SMTP.php';
require_once __DIR__ . '/PHPMailer-master/src/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Khởi tạo mảng lỗi và thông báo
$tb = ["username" => NULL, "password" => NULL, "repass" => NULL, "mail" => NULL, "code" => NULL, "oke" => NULL];
$user = $pass = $repass = $email = $code = NULL;

// Gửi mã xác thực về email
if (isset($_POST['send_code'])) {
    $email = trim($_POST['email']);
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $tb["mail"] = "Vui lòng nhập email hợp lệ để nhận mã xác thực!";
    } else {
        $code = rand(100000, 999999);
        $_SESSION['register_email'] = $email;
        $_SESSION['register_code'] = $code;

        $mail = new PHPMailer(true);
        try {
            $mail->CharSet = 'UTF-8'; // Thêm dòng này để gửi tiếng Việt không lỗi font
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'jinbao1994@gmail.com'; // Gmail của bạn
            $mail->Password   = 'nyeu rxpt wjne ilvq';  // App password
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('jinbao1994@gmail.com', 'ABC Register');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = "Mã xác thực đăng ký tài khoản";
            $mail->Body    = "Mã xác thực của bạn là: <b>$code</b><br><br>CÔNG TY HÃNG PHIM TRẺ";

            $mail->send();
            $tb["oke"] = "Đã gửi mã xác thực tới email của bạn. Vui lòng kiểm tra hộp thư!";
        } catch (Exception $e) {
            $tb["mail"] = "Không gửi được email. Lỗi: " . $mail->ErrorInfo;
        }
    }
}

// Xử lý gửi mã xác thực qua AJAX (nếu là request AJAX)
if (isset($_POST['ajax_send_code'])) {
    $email = trim($_POST['email']);
    $result = ["success" => false, "msg" => ""];
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $result["msg"] = "Vui lòng nhập email hợp lệ để nhận mã xác thực!";
    } else {
        $code = rand(100000, 999999);
        $_SESSION['register_email'] = $email;
        $_SESSION['register_code'] = $code;

        $mail = new PHPMailer(true);
        try {
            $mail->CharSet = 'UTF-8';
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'jinbao1994@gmail.com';
            $mail->Password   = 'nyeu rxpt wjne ilvq';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('jinbao1994@gmail.com', 'ABC Register');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = "Mã xác thực đăng ký tài khoản";
            $mail->Body    = "Mã xác thực của bạn là: <b>$code</b><br><br>CÔNG TY HÃNG PHIM TRẺ";

            $mail->send();
            $result["success"] = true;
            $result["msg"] = "Đã gửi mã xác thực tới email của bạn. Vui lòng kiểm tra hộp thư!";
        } catch (Exception $e) {
            $result["msg"] = "Không gửi được email. Lỗi: " . $mail->ErrorInfo;
        }
    }
    header('Content-Type: application/json');
    echo json_encode($result);
    exit();
}

// Xử lý đăng ký
if (isset($_POST["ok"])) {
    // Kiểm tra trường username
    if (empty($_POST["user"])) {
        $tb["username"] = "Vui lòng nhập username";
    } else {
        $user = trim($_POST["user"]);
    }
    // Kiểm tra trường password
    if (empty($_POST["pass"])) {
        $tb["password"] = "Vui lòng nhập password";
    } else {
        $pass = $_POST["pass"];
    }
    // Kiểm tra trường nhập lại password
    if (empty($_POST["repass"])) {
        $tb["repass"] = "Vui lòng nhập lại password";
    } else {
        $repass = $_POST["repass"];
    }
    // Kiểm tra trường email
    if (empty($_POST["email"])) {
        $tb["mail"] = "Vui lòng nhập Email";
    } else {
        $email = trim($_POST["email"]);
    }
    // Kiểm tra mã xác thực
    if (empty($_POST["code"])) {
        $tb["code"] = "Vui lòng nhập mã xác thực đã gửi về email!";
    } else {
        $code = trim($_POST["code"]);
    }

    // Nếu đã nhập đủ thông tin
    if ($user && $pass && $repass && $email && $code) {
        // Kiểm tra mật khẩu có ít nhất 1 chữ hoa
        if (!preg_match('/[A-Z]/', $pass)) {
            $tb["password"] = "Mật khẩu phải có ít nhất 1 chữ hoa!";
        }
        // Kiểm tra mật khẩu trùng khớp
        else if ($pass !== $repass) {
            $tb["repass"] = "Mật khẩu nhập lại không khớp!";
        }
        // Kiểm tra mã xác thực
        else if (
            !isset($_SESSION['register_code']) ||
            !isset($_SESSION['register_email']) ||
            $_SESSION['register_email'] !== $email ||
            $_SESSION['register_code'] != $code
        ) {
            $tb["code"] = "Mã xác thực không đúng hoặc email không khớp!";
        }
        // Nếu mọi thứ hợp lệ, thêm user
        else {
            them_user($user, $email, $pass, 1); // Loai=1 là nhân viên
            $tb["oke"] = "Đăng ký thành công!";
            unset($_SESSION['register_code'], $_SESSION['register_email']);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Register</title>
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="../css/font-awesome.css">
	<link rel="stylesheet" type="text/css" href="../css/styles.css">
	<style>
		body {
			background: url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1500&q=80') no-repeat center center fixed;
			background-size: cover;
		}
		.login {
			width: 420px;
			margin: 48px auto 0 auto;
			position: relative;
			background: rgba(255,255,255,0.95);
			border-radius: 18px;
			box-shadow: 0 8px 32px rgba(40,62,81,0.18);
			padding: 32px 32px 24px 32px;
		}
		.login .fa-user-plus {
			color: #388e3c;
			margin-bottom: 12px;
			text-shadow: 0 2px 8px rgba(40,62,81,0.08);
		}
		.form-horizontal .form-group label {
			font-weight: 500;
			color: #283e51;
		}
		.form-horizontal .form-control {
			border-radius: 8px;
			border: 1px solid #cfd8dc;
			background: #f8fafb;
		}
		.btn-info, .btn-default {
			border-radius: 8px;
		}
		.social-icons {
			margin-top: 24px;
			text-align: center;
		}
		.social-icons a {
			display: inline-block;
			margin: 0 10px;
			color: #fff;
			width: 40px;
			height: 40px;
			line-height: 40px;
			border-radius: 50%;
			background: #3b5998;
			font-size: 20px;
			transition: background 0.2s;
			box-shadow: 0 2px 8px rgba(40,62,81,0.08);
		}
		.social-icons a.fa-google { background: #dd4b39; }
		.social-icons a.fa-facebook { background: #3b5998; }
		.social-icons a.fa-twitter { background: #1da1f2; }
		.social-icons a.fa-github { background: #24292e; }
		.social-icons a:hover { opacity: 0.85; }
		.company-note {
			text-align:center;
			margin-top:18px;
			color:#888;
			font-weight:bold;
			letter-spacing:1px;
		}
	</style>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
	<div class="login">
		<center><i class="fa fa-user-plus" aria-hidden="true" style="font-size:100px;"></i></center>
		<form class="form-horizontal" action="register.php" method="post" id="registerForm">
			<div class="form-group">
				<label class="col-sm-3 control-label">USER NAME</label>
				<div class="col-sm-8">
					<input type="text" name="user" class="form-control" placeholder="User name" value="<?php echo htmlspecialchars($user ?? ''); ?>">
					<p style="color:red;"><?php echo $tb["username"]; ?></p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">PASSWORD</label>
				<div class="col-sm-8">
					<input type="password" class="form-control" name="pass" placeholder="Password">
					<p style="color:red;"><?php echo $tb["password"]; ?></p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">RE-PASSWORD</label>
				<div class="col-sm-8">
					<input type="password" class="form-control" name="repass" placeholder="Nhập lại Password">
					<p style="color:red;"><?php echo $tb["repass"]; ?></p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">EMAIL</label>
				<div class="col-sm-8" style="display:flex;gap:8px;">
					<input type="email" name="email" class="form-control" placeholder="Email" value="<?php echo htmlspecialchars($email ?? ''); ?>" id="email_input">
					<button type="button" id="send_code_btn" class="btn btn-info" style="white-space:nowrap;">Gửi mã</button>
				</div>
				<div class="col-sm-8 col-sm-offset-3">
					<p id="ajax_mail_msg" style="color:red;"></p>
					<p style="color:red;"><?php echo $tb["mail"]; ?></p>
					<p style="color:green;"><?php echo $tb["oke"]; ?></p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">MÃ XÁC THỰC</label>
				<div class="col-sm-8">
					<input type="text" name="code" class="form-control" placeholder="Nhập mã xác thực">
					<p style="color:red;"><?php echo $tb["code"]; ?></p>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-3 col-sm-8">
					<button type="submit" name="ok" class="btn btn-default">Tạo Tài Khoản</button>
					<a href="login.php" class="btn btn-default">Đăng Nhập</a>
				</div>
			</div>
		</form>
		<!-- Icon mạng xã hội trang trí -->
		<div class="social-icons">
			<a href="#" class="fa fa-facebook" title="Facebook"></a>
			<a href="#" class="fa fa-google" title="Google"></a>
			<a href="#" class="fa fa-twitter" title="Twitter"></a>
			<a href="#" class="fa fa-github" title="GitHub"></a>
		</div>
		<div class="company-note">
			CÔNG TY HÃNG PHIM TRẺ
		</div>
	</div>
	<script>
	$(document).ready(function(){
		$('#send_code_btn').click(function(){
			var email = $('#email_input').val();
			$('#send_code_btn').prop('disabled', true);
			$('#ajax_mail_msg').text('');
			$.ajax({
				url: 'register.php',
				type: 'POST',
				data: { ajax_send_code: 1, email: email },
				dataType: 'json',
				success: function(res) {
					if(res.success) {
						$('#ajax_mail_msg').css('color','green').text(res.msg);
					} else {
						$('#ajax_mail_msg').css('color','red').text(res.msg);
					}
					$('#send_code_btn').prop('disabled', false);
				},
				error: function() {
					$('#ajax_mail_msg').css('color','red').text('Lỗi gửi mã xác thực!');
					$('#send_code_btn').prop('disabled', false);
				}
			});
		});
	});
	</script>
</body>
</html>