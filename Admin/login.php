<?php
session_start();
$loi = array();
$loi["username"] = $loi["password"] = $oi["ok"] = NULL;
$username = $password = NULL;

if (isset($_POST["ok"])) {
	// Kiểm tra trường username
	if (empty($_POST["user"])) {
		$loi["username"] = "Vui lòng nhập username";
	} else {
		$username = trim($_POST["user"]);
	}
	// Kiểm tra trường password
	if (empty($_POST["pass"])) {
		$loi["password"] = "Vui lòng nhập password";
	} else {
		$password = $_POST["pass"];
	}

	if ($username && $password) {
		$us = "root";
		$pas = "";
		$conn = mysqli_connect("localhost", $us, $pas, "danhom5");
		mysqli_set_charset($conn, "utf8");
		// Sử dụng prepared statement để chống SQL Injection
		$stmt = mysqli_prepare($conn, "SELECT * FROM user WHERE Username = ?");
		mysqli_stmt_bind_param($stmt, "s", $username);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		if ($row = mysqli_fetch_assoc($result)) {
			// So sánh mật khẩu đã hash
			if (password_verify($password, $row['MatKhau'])) {
				$_SESSION["Loai"] = $row["Loai"];
				$_SESSION["Username"] = $username;
				session_regenerate_id(true);	
				// Sửa đoạn này: cả admin và nhân viên đều vào trang admin
				header("location:index.php");
				exit();
			} else {
				$oi["ok"] = "Sai username hoặc password";
			}
		} else {
			$oi["ok"] = "Sai username hoặc password";
		}
		mysqli_stmt_close($stmt);
		mysqli_close($conn);
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Login</title>
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
		.login .fa-user-circle {
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
</head>
<body>
	<div class="login">
		<center><i class="fa fa-user-circle" aria-hidden="true" style="font-size:100px;"></i></center>
		<form class="form-horizontal" action="login.php" method="post">
			<div class="form-group">
				<label class="col-sm-3 control-label">USER NAME</label>
				<div class="col-sm-8">
					<input type="text" name="user" class="form-control" placeholder="User name">
					<p style="color:red;"><?php echo $loi["username"]; ?></p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">PASSWORD</label>
				<div class="col-sm-8">
					<input type="password" class="form-control" name="pass" placeholder="Password">
					<p style="color:red;"><?php echo $loi["password"]; ?></p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">&nbsp;</label>
				<div class="col-sm-8">
					<p class="col-sm-12" style="color:red;"><?php echo $oi["ok"] ?></p>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-3 col-sm-8">
					<button type="submit" name="ok" class="btn btn-default">Đăng Nhập</button>
					<a href="register.php" class="btn btn-default">Đăng Ký</a>
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
</body>
</html>