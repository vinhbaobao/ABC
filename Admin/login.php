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
</head>
<body style="background: #F7F7F7; height: 100%">
	<div class="login" style="width:600px;margin:auto;position:relative;">
		<center><i class="fa fa-user-circle" aria-hidden="true" style="font-size:100px;"></i></center>
		<form class="form-horizontal" action="login.php" method="post">
			<div class="form-group">
				<label for="inputPassword3" class="col-sm-3 control-label">&nbsp</label>
				<div class="col-sm-8">
					<p class="col-sm-12" style="color:red;">&nbsp</p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">USER NAME</label>
				<div class="col-sm-8">
					<input type="text" name="user" class="form-control" placeholder="User name">
				</div>
			</div>
			<div class="form-group">
				<label for="inputPassword3" class="col-sm-3 control-label">PASSWORD</label>
				<div class="col-sm-8">
					<input type="password" class="form-control" name="pass" placeholder="Password">
				</div>
			</div>
			<div class="form-group">
				<label for="inputPassword3" class="col-sm-3 control-label">&nbsp</label>
				<div class="col-sm-8">
					<p class="col-sm-12" style="color:red;"><?php echo $oi["ok"] ?></p>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-3 col-sm-8">
					<button type="submit" name="ok" class="btn btn-default">Log in</button>
					<a href="register.php" class="btn btn-default">Register</a>
				</div>
			</div>
		</form>
	</div>
</body>
</html>