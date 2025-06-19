<?php include 'view/header_admin.php'; ?>
<?php
// Chỉ cho phép loại 2 (quản trị viên) thao tác thêm user
$is_admin = isset($_SESSION['Loai']) && $_SESSION['Loai'] == 2;
$tb = ["username" => NULL, "password" => NULL, "mail" => NULL, "oke" => NULL];
$user = $pass = $email = null;

// Xử lý thêm người dùng: GỬI FORM VỀ index.php ĐỂ XỬ LÝ SQL
// Nếu bạn muốn xử lý thêm user trực tiếp ở đây (không gửi sang index.php), hãy bỏ action="index.php?action=them_user" và thay bằng action="".
// Nếu muốn gửi về index.php (chuẩn MVC), giữ nguyên action="index.php?action=them_user" và đảm bảo index.php có xử lý POST như hướng dẫn dưới đây.
?>
<style>
/* ======= Giao diện CSS cho trang quản lý phiếu xuất nhập kho ======= */
.panel {
    border-radius: 12px !important;
    box-shadow: 0 2px 8px rgba(40,62,81,0.07);
    border: none;
}
.panel-heading {
    border-radius: 12px 12px 0 0 !important;
    background: #e3eaf1 !important;
}
.panel-body {
    background: #fff;
    border-radius: 0 0 12px 12px;
    padding: 18px 18px 12px 18px;
}
.table {
    border-radius: 12px !important;
    overflow: hidden;
    background: #fafbfc;
    margin-bottom: 0;
}
.table > thead > tr {
    background: #e3eaf1;
}
.table > tbody > tr:hover {
    background: #f1f7fa;
    transition: background 0.2s;
}
.table > tbody > tr > td, .table > thead > tr > th {
    padding: 8px 10px !important;
    vertical-align: middle !important;
}
.form-group label {
    font-weight: 500;
}
.form-control {
    border-radius: 8px;
    border: 1px solid #cfd8dc;
    background: #f8fafb;
}
#ds_sanpham .sanpham-row {
    margin-bottom: 8px;
}
.btn-primary, .btn-warning, .btn-danger, .btn-default {
    border-radius: 8px !important;
}
.tittle h3 {
    color: #1a2a3a;
}
@media (max-width: 991px) {
    .panel-body {
        padding: 12px 6px 8px 6px;
    }
}
</style>

<div class="right-col col-lg-10 col-md-9 col-sm-9 col-xs-12" style="background:#f4f6f9;color:#283e51;min-height:100vh;padding:16px 8px 8px 8px;">
	<!-- Tiêu đề trang -->
	<div class="tittle" style="margin-bottom:16px;">
		<h3 style="font-weight:600;">Quản lý user</h3>
	</div>
	<div class="row">
		<!-- Bảng danh sách nhân viên -->
		<div class="col-lg-6 col-md-6 col-sm-12" style="margin-bottom:16px;">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 style="font-weight:600;margin:0;">Danh sách Nhân viên &gt;&gt; <?php echo $breadcrumb; ?></h4>
				</div>
				<div class="panel-body">
					<table class="table table-striped table-bordered" style="background:#fff;">
						<thead>
							<tr style="background:#e3eaf1;">
								<th style="width:50px;">&nbsp;</th>
								<th>Tên Nhân viên</th>
								<th>Email</th>
								<th>Chức vụ</th>
							</tr>
						</thead>
						<tbody>
						<?php 
						$is_admin = isset($_SESSION['Loai']) && $_SESSION['Loai'] == 2;
						foreach ($users as $user) : ?>
							<tr>
								<td>
									<!-- Nút xóa user -->
									<form method="post" action="index.php?action=ql_user&id=0" style="margin:0;">
										<input type="hidden" name="action" value="del_user">
										<button type="submit" class="btn btn-link" style="padding:0;"><i class="fa fa-window-close"></i></button>
										<input type="hidden" name="id" value="<?php echo $user['IdUser']; ?>" />
									</form>
								</td>
								<td><?php echo htmlspecialchars($user['Username']); ?></td>
								<td><?php echo htmlspecialchars($user['Email']); ?></td>
								<td>
									<?php if ($is_admin && $_SESSION['Username'] != $user['Username']) : ?>
										<form method="post" action="index.php?action=ql_user&id=0" style="display:inline;">
											<input type="hidden" name="action" value="update_role">
											<input type="hidden" name="id" value="<?php echo $user['IdUser']; ?>">
											<select name="role" class="form-control" style="display:inline;width:auto;height:28px;padding:2px 8px;">
												<option value="1" <?php if($user['Loai']==1) echo 'selected'; ?>>Nhân viên</option>
												<option value="2" <?php if($user['Loai']==2) echo 'selected'; ?>>Quản trị viên</option>
											</select>
											<button type="submit" class="btn btn-xs btn-success" style="margin-left:4px;">Lưu</button>
										</form>
									<?php else: ?>
										<?php echo ($user['Loai'] == 1) ? 'Nhân viên' : 'Quản trị viên'; ?>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<!-- Form thêm người dùng (dành cho quản trị viên) -->
		<div class="col-lg-6 col-md-6 col-sm-12" style="margin-bottom:16px;">
			<?php if ($is_admin): ?>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 style="font-weight:600;margin:0;">Thêm người dùng</h4>
				</div>
				<div class="panel-body">
					<form class="form-group" method="post" action="index.php?action=them_user" style="margin-bottom:0;">
						<input type="hidden" name="action" value="them_user">
						<div class="form-group">
							<label for="user">Tên đăng nhập</label>
							<input type="text" class="form-control" name="user" placeholder="Nhập tên đăng nhập" required>
						</div>
						<div class="form-group">
							<label for="pass">Mật khẩu</label>
							<input type="password" class="form-control" name="pass" placeholder="Nhập mật khẩu" required>
						</div>
						<div class="form-group">
							<label for="email">Email</label>
							<input type="email" class="form-control" name="email" placeholder="Email" required>
						</div>
						<div class="form-group">
							<label for="loai">Chức vụ</label>
							<select class="form-control" name="loai" required>
								<option value="1">Nhân viên</option>
								<option value="2">Quản trị viên</option>
							</select>
						</div>
						<button type="submit" class="btn btn-primary">Thêm người dùng</button>
					</form>
				</div>
			</div>
			<!-- Form thêm chức vụ mới -->
			<div class="panel panel-default" style="margin-top:24px;">
				<div class="panel-heading">
					<h4 style="font-weight:600;margin:0;">Thêm chức vụ mới</h4>
				</div>
				<div class="panel-body">
					<form class="form-group" method="post" action="index.php?action=them_chucvu" style="margin-bottom:0;">
						<input type="hidden" name="action" value="them_chucvu">
						<div class="form-group">
							<label for="tenchucvu">Tên chức vụ</label>
							<input type="text" class="form-control" name="tenchucvu" placeholder="Nhập tên chức vụ mới" required>
						</div>
						<button type="submit" class="btn btn-success">Thêm chức vụ</button>
					</form>
				</div>
			</div>
			<?php endif; ?>
		</div>
	</div>
</div>
</body>
</html>