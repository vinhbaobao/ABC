<?php
session_start();
// Khởi tạo mảng lỗi và thông báo
$loi=array();
$loi["username"]=$loi["password"]=$oi["ok"]=NULL;
$username=$Password=NULL;

// Kiểm tra nếu form được submit
if (isset($_POST["ok"])) {
	// Kiểm tra trường username
	if (empty($_POST["user"])) {
		$loi["username"]="Vui lòng nhập username";
	}
	else{
		$username=$_POST["user"];
	}
	// Kiểm tra trường password
	if (empty($_POST["pass"])) {
		$loi["password"]=" Vui lòng nhập password";
	}
	else{
		$password=$_POST["pass"];
	}

	// Nếu đã nhập đủ thông tin
	if ($username && $password) {
		// Kết nối CSDL
		$us="root";
		$pas="";
		$conn=mysqli_connect("localhost",$us,$pas,"danhom5");
		mysqli_select_db($conn,"danhom5");
		// Truy vấn dữ liệu kiểm tra đăng nhập
		$result=mysqli_query($conn,"select * from user where Username='$username' and MatKhau='$password'");
		if (mysqli_num_rows($result)==1) {
			$data=mysqli_fetch_assoc($result);
			$_SESSION["Loai"]=$data["Loai"];
			if ($_SESSION["Loai"]==2) {
				// Nếu là quản trị viên, chuyển hướng vào trang admin
				$_SESSION["Username"]=$username;
				header("location:index.php");
				exit();
			}
			else{
				// Nếu là nhân viên, chuyển hướng ra ngoài trang chính
				$_SESSION["Username"]=$username;
				header("location:../index.php");
				exit();
			}
			
		}
		else{
			// Thông báo lỗi đăng nhập sai
			$oi["ok"]="Bạn đã nhập sai username hoặc password";
		}
	// Đóng kết nối CSDL
	mysqli_close($conn);
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Login</title>
	<!-- Nhúng các file CSS cần thiết -->
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="../css/font-awesome.css">
	<link rel="stylesheet" type="text/css" href="../css/styles.css">
</head>
<body style="background: #F7F7F7; height: 100%">
	<div class="login" style="width:600px;margin:auto;position:relative;">
			<!-- Icon đăng nhập -->
			<center><i class="fa fa-user-circle"" aria-hidden="true" style="font-size:100px;"></i></center>
			<!-- Form đăng nhập -->
			<form class="form-horizontal" action="login.php" method="post">
				<div class="form-group">
				    <label for="inputPassword3" class="col-sm-3 control-label">&nbsp</label>
				    <div class="col-sm-8">
				    	<!-- Thông báo lỗi (nếu có) -->
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
				    	<!-- Hiển thị thông báo lỗi đăng nhập -->
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