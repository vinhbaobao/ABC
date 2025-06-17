<?php include 'view/header_admin.php'; ?>
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
								<th>Cấp độ</th>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($users as $user) : ?>
							<tr>
								<td>
									<!-- Nút xóa user -->
									<form method="post" style="margin:0;">
										<input type="hidden" name="action" value="del_user">
										<button type="submit" class="btn btn-link" style="padding:0;"><i class="fa fa-window-close"></i></button>
										<input type="hidden" name="id" value="<?php echo $user['IdUser']; ?>" />
									</form>
								</td>
								<td><?php echo htmlspecialchars($user['Username']); ?></td>
								<td><?php echo htmlspecialchars($user['Email']); ?></td>
								<td>
									<?php
										// Hiển thị cấp độ user
										if ($user['Loai'] == 1){
											echo 'Nhân viên';
										} else {
											echo 'Quản trị viên';
										}
									?>
								</td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<!-- Form thêm quản trị viên mới -->
		<div class="col-lg-6 col-md-6 col-sm-12" style="margin-bottom:16px;">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 style="font-weight:600;margin:0;">Thêm quản trị viên</h4>
				</div>
				<div class="panel-body">
					<form class="form-group" method="post" style="margin-bottom:0;">
						<input type="hidden" name="action" value="them_admin">
						<div class="form-group">
							<label for="user">Nhập tên</label>
							<input type="text" class="form-control" name="user" placeholder="Nhập tên">
						</div>
						<div class="form-group">
							<label for="pass">Mật khẩu</label>
							<input type="password" class="form-control" name="pass" placeholder="Nhập mật khẩu">
						</div>
						<div class="form-group">
							<label for="email">Email</label>
							<input type="email" class="form-control" name="email" placeholder="Email">
						</div>
						<button type="submit" class="btn btn-primary">Thêm</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>