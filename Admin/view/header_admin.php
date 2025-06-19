<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION["Loai"]) || ($_SESSION["Loai"] != 1 && $_SESSION["Loai"] != 2)) {
    header("location:../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Trang Quản lý</title>
	<!-- Nhúng các file CSS và JS cần thiết cho giao diện admin -->
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="../css/font-awesome.css">
	<link rel="stylesheet" type="text/css" href="../css/dropzone.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
	<script src="../js/jquery-3.1.1.min.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script src="../js/dropzone.js"></script>
	<script src="../js/table.js"></script>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="../css/styles_admin.css">
	<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
	<style>
		/* CSS cho layout và menu admin */
		body {
			background: #f4f6f9;
		}
		.row.admin-layout {
			display: flex;
			flex-wrap: nowrap;
			margin: 0;
		}
		.left-col {
			background: linear-gradient(135deg, #283e51 0%, #485563 100%);
			border-radius: 22px;
			margin: 22px 0 22px 22px;
			box-shadow: 0 6px 32px rgba(40,62,81,0.13);
			padding: 0;
			min-height: 96vh;
			transition: box-shadow 0.2s;
			float: none !important; /* Xóa float */
			width: auto;
			height: 100vh;
			position: relative;
			display: block;
		}
		.logo img {
			border-radius: 50%;
			box-shadow: 0 2px 8px rgba(0,0,0,0.08);
			margin-top: 18px;
			margin-bottom: 8px;
		}
		.user {
			padding: 18px 0 10px 0;
			border-bottom: 1px solid #3a4a5a;
			margin-bottom: 10px;
		}
		.user p {
			margin: 0;
			color: #fff;
			font-weight: 500;
		}
		.user a {
			color: #ffd54f;
			text-decoration: none;
			font-size: 14px;
		}
		.rightnav {
			padding: 0 10px 10px 10px;
		}
		.rightnav table {
			background: transparent;
			border-radius: 14px;
			overflow: hidden;
			width: 100%;
		}
		.rightnav th {
			background: #34495e;
			color: #fff;
			border: none;
			font-size: 17px;
			border-radius: 14px 14px 0 0;
			letter-spacing: 1px;
		}
		.menu-item {
			display: flex;
			align-items: center;
			gap: 10px;
			padding: 10px 12px;
			margin: 6px 0;
			border-radius: 10px;
			transition: background 0.18s, color 0.18s;
			cursor: pointer;
			font-size: 15px;
			color: #e7e7e7;
			font-weight: 500;
			text-decoration: none;
		}
		.menu-item:hover, .menu-item.active {
			background: #fffde7;
			color: #283e51 !important;
			box-shadow: 0 2px 8px rgba(44,62,80,0.08);
			text-decoration: none;
		}
		.menu-item i {
			font-size: 18px;
			width: 22px;
			text-align: center;
		}
		@media (max-width: 991px) {
			.row.admin-layout {
				display: block;
			}
			.left-col, .right-col {
				width: 100% !important;
				min-height: unset;
			}
			.left-col {
				margin: 0;
				border-radius: 0 0 22px 22px;
			}
		}
	</style>
	<script>
		// Khởi tạo DataTable và tooltip khi tài liệu sẵn sàng
		$(document).ready(function(){
    		$('#sp_datatable').DataTable();
		});
		$(function () {
			$('[data-toggle="tooltip"]').tooltip()
		});
	</script>
</head>
<body>
<div class="container-fluid" style="padding:0;">
	<div class="row admin-layout">
		<!-- Sidebar trái: Thông tin user và menu -->
		<div class="left-col col-lg-2 col-md-3 col-sm-3 col-xs-12">
			<div class="user" style="width:100%;text-align:center;">
				<div class="logo">
					<center><img src="image/admin.png" height="70" alt=""></center>
					<?php 
					// Cho phép cả nhân viên và quản trị viên vào admin
					if ($_SESSION["Loai"]==1 || $_SESSION["Loai"]==2) {
						echo"<p>Xin chào, <b>".$_SESSION['Username']. "</b></p>
						<p><a href='logout.php'><i class='fa fa-sign-out'></i> Đăng xuất</a></p>";
					} else {
						header("location:../index.php");
						exit();
					}
					?>
				</div>
			</div>
			<!-- Menu chức năng quản trị -->
			<div class="rightnav" style="width:100%;text-align:left;">
				<table>
					<thead>
						<tr>
							<th colspan="2" style="text-align:center;">MENU</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$is_admin = ($_SESSION["Loai"] == 2);
						$is_staff = ($_SESSION["Loai"] == 1);
						?>
						<?php if ($is_admin): ?>
							<tr><td colspan="2">
								<a href="?action=home" class="menu-item<?php if(isset($_GET['action']) && $_GET['action']=='home') echo ' active'; ?>"><i class="fa fa-home"></i> Thống kê</a>
							</td></tr>
							<tr><td colspan="2">
								<a href="?action=ql_user&id=0" class="menu-item<?php if(isset($_GET['action']) && $_GET['action']=='ql_user') echo ' active'; ?>"><i class="fa fa-user"></i> Quản lý người dùng</a>
							</td></tr>
							<tr><td colspan="2">
								<a href="?action=ql_hinhanh" class="menu-item<?php if(isset($_GET['action']) && $_GET['action']=='ql_hinhanh') echo ' active'; ?>"><i class="fa fa-picture-o"></i> Quản lý hình ảnh</a>
							</td></tr>
						<?php endif; ?>
						<tr><td colspan="2">
							<a href="?action=ql_sp&category_id=0" class="menu-item<?php if(isset($_GET['action']) && $_GET['action']=='ql_sp') echo ' active'; ?>"><i class="fa fa-list-alt"></i> Quản lý sản phẩm</a>
						</td></tr>
						<tr><td colspan="2">
							<a href="?action=ql_cart" class="menu-item<?php if(isset($_GET['action']) && $_GET['action']=='ql_cart') echo ' active'; ?>"><i class="fa fa-shopping-cart"></i> Quản lý đơn hàng</a>
						</td></tr>
						<tr><td colspan="2">
							<a href="?action=ql_kho" class="menu-item<?php if(isset($_GET['action']) && $_GET['action']=='ql_kho') echo ' active'; ?>"><i class="fa fa-cubes"></i> Quản lý kho</a>
						</td></tr>
						<tr><td colspan="2">
							<a href="?action=ql_phieuXNKho" class="menu-item<?php if(isset($_GET['action']) && $_GET['action']=='ql_phieuXNKho') echo ' active'; ?>"><i class="fa fa-file-text"></i> Quản lý Phiếu Xuất Nhập kho</a>
						</td></tr>
					</tbody>
				</table>
			</div>
		</div>
		