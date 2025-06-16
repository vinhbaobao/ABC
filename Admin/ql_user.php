<?php include 'view/header_admin.php'; ?>
<div class="right-col col-lg-10 col-md-9 col-sm-9 col-xs-12" style="background:#f4f6f9;color:#283e51;min-height:100vh;padding:16px 8px 8px 8px;">
	<div class="tittle" style="margin-bottom:16px;">
		<h3 style="font-weight:600;">Quản lý user</h3>
	</div>
	<div class="row">
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