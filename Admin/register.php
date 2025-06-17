<?php 
	// Khởi tạo mảng lỗi và thông báo
	$loi=array();
	$tb["username"]=$tb["password"]=$tb["mail"]=$tb["oke"]=NULL;
	$user=$pass=$email=NULL;

	// Kiểm tra nếu form được submit
	if (isset($_POST["ok"])) {
		// Kiểm tra trường username
		if (empty($_POST["user"])) {
			$tb["username"]="Vui lòng nhập username";
		}
		else{
			$user=$_POST["user"];
		}
		// Kiểm tra trường password
		if (empty($_POST["pass"])) {
			$tb["password"]="Vui lòng nhập password";
		}
		else{
			$pass=$_POST["pass"];
		}
		// Kiểm tra trường email
		if (empty($_POST["email"])) {
			$tb["mail"]="Vui lòng nhập Email";
		}
		else{
			$email=$_POST["email"];
		}
		// Nếu đã nhập đủ thông tin
		if ($user && $email && $pass) {
			// Kết nối CSDL
			$us="root";
			$pas="";
			$conn=mysqli_connect("localhost",$us,$pas,"danhom5");
			mysqli_select_db($conn,"danhom5");
			// Thêm người dùng mới vào bảng user
			mysqli_query($conn,"insert into user(Username, Email,Matkhau,Loai) value('$user','$email','$pass','1')");
			
			$tb["oke"]="Đăng ký thành công";
			mysqli_close($conn);
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Register</title>
	<!-- Nhúng các file CSS cần thiết -->
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="../css/font-awesome.css">
	<link rel="stylesheet" type="text/css" href="../css/styles.css">
</head>
<body style="background: #F7F7F7">
	<div class="login" style="width:600px;margin:auto;position:relative;">
			<!-- Icon đăng ký -->
			<center><i class="fa fa-user-plus" aria-hidden="true" style="font-size:100px;"></i></center>
			<!-- Form đăng ký -->
			<form class="form-horizontal" action="register.php" method="post">
				<div class="form-group">
				    <label for="inputPassword3" class="col-sm-3 control-label">&nbsp</label>
				    <div class="col-sm-8">
				    	<!-- Hiển thị thông báo thành công -->
				    	<p class="col-sm-12" style="color:red;"><?php echo $tb["oke"] ?></p>
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
				    <label class="col-sm-3 control-label">EMAIL</label>
				    <div class="col-sm-8">
				    	<input type="email" name="email" class="form-control" placeholder="Email">
				    </div>
				</div>
				<div class="form-group">
				    <div class="col-sm-offset-3 col-sm-8">
				    	<button type="submit" name="ok" class="btn btn-default">Register</button>
				    	<a href="login.php" class="btn btn-default">Log in</a>
				    </div>
				</div>
			</form>
	</div>
</body>
</html>