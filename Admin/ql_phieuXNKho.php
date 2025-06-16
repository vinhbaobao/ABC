<?php include 'view/header_admin.php'; ?>
<meta charset="UTF-8">
<div class="right-col col-lg-10 col-md-9 col-sm-9 col-xs-12" style="background:#f4f6f9;color:#283e51;min-height:100vh;padding:16px 8px 8px 8px;">
	<div class="tittle" style="margin-bottom:16px;">
		<h3 style="font-weight:600;">Quản lý Phiếu Xuất Nhập Kho</h3>
	</div>
	<?php if (!empty($error_message)): ?>
		<div class="alert alert-danger"><?php echo $error_message; ?></div>
	<?php endif; ?>
	<?php if (!empty($success_message)): ?>
		<div class="alert alert-success"><?php echo $success_message; ?></div>
	<?php endif; ?>
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-12" style="margin-bottom:16px;">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 style="font-weight:600;margin:0;"><?php echo isset($edit_phieu) && $edit_phieu ? 'Sửa Phiếu' : 'Thêm Phiếu Xuất/Nhập Kho'; ?></h4>
				</div>
				<div class="panel-body">
					<form class="form-group" method="post" autocomplete="off" style="margin-bottom:0;">
						<input type="hidden" name="action" value="<?php echo isset($edit_phieu) && $edit_phieu ? 'edit_phieu' : 'add_phieu'; ?>">
						<?php if (isset($edit_phieu) && $edit_phieu): ?>
							<input type="hidden" name="id" value="<?php echo $edit_phieu['id']; ?>">
						<?php endif; ?>
						<div class="form-group">
							<label for="loai_phieu">Loại phiếu</label>
							<select class="form-control" name="loai_phieu" id="loai_phieu" required>
								<option value="">--Chọn loại phiếu--</option>
								<option value="nhap" <?php if(isset($edit_phieu) && $edit_phieu['loai_phieu']=='nhap') echo 'selected'; ?>>Phiếu Nhập Kho</option>
								<option value="xuat" <?php if(isset($edit_phieu) && $edit_phieu['loai_phieu']=='xuat') echo 'selected'; ?>>Phiếu Xuất Kho</option>
							</select>
						</div>
						<div class="form-group">
							<label for="id_nhomsp">Nhóm sản phẩm</label>
							<select class="form-control" name="id_nhomsp" id="id_nhomsp" required>
								<option value="">--Chọn nhóm sản phẩm--</option>
								<?php foreach ($ds_nhomsp as $nhom): ?>
									<option value="<?php echo $nhom['IdNhomSP']; ?>" <?php if(isset($edit_phieu) && $edit_phieu['id_nhomsp']==$nhom['IdNhomSP']) echo 'selected'; ?>>
										<?php echo htmlspecialchars($nhom['TenNhomSP']); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="form-group">
							<label>Sản phẩm & Số lượng</label>
							<div id="ds_sanpham">
								<div class="row sanpham-row" style="margin-bottom:8px;">
									<div class="col-xs-7">
										<select name="id_sp[]" class="form-control" required>
											<option value="">--Chọn sản phẩm--</option>
											<?php foreach ($ds_sp as $sp): ?>
												<option value="<?php echo $sp['IdSP']; ?>"><?php echo htmlspecialchars($sp['TenSP']); ?></option>
											<?php endforeach; ?>
										</select>
									</div>
									<div class="col-xs-5">
										<input type="number" name="so_luong[]" class="form-control" min="1" required placeholder="Số lượng">
									</div>
								</div>
							</div>
							<button type="button" id="add-sp-row" class="btn btn-default btn-xs" style="margin-top:5px;">Thêm sản phẩm</button>
						</div>
						<div class="form-group">
							<label for="id_kho">Kho</label>
							<select class="form-control" name="id_kho" id="id_kho" required>
								<option value="">--Chọn kho--</option>
								<?php foreach ($ds_kho as $kho): ?>
									<option value="<?php echo $kho['IdKho']; ?>" <?php if(isset($edit_phieu) && $edit_phieu['id_kho']==$kho['IdKho']) echo 'selected'; ?>>
										<?php echo htmlspecialchars($kho['TenKho']); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="form-group">
							<label for="ma_phieu">Mã phiếu</label>
							<?php
							// Hàm sinh mã phiếu random
							function generate_ma_phieu($loai = 'NK') {
								$prefix = ($loai == 'xuat') ? 'XK' : 'NK';
								return $prefix . '-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
							}
							$ma_phieu_random = '';
							if (!isset($edit_phieu) || !$edit_phieu) {
								$loai = isset($_POST['loai_phieu']) ? $_POST['loai_phieu'] : 'nhap';
								$ma_phieu_random = generate_ma_phieu($loai);
							}
							?>
							<input type="text" class="form-control" name="ma_phieu" id="ma_phieu"
								placeholder="Ví dụ: NK-xxxxxxx hoặc XK-xxxxxxx" required
								value="<?php
									echo isset($edit_phieu) && $edit_phieu
										? htmlspecialchars($edit_phieu['ma_phieu'])
										: (isset($_POST['ma_phieu']) ? htmlspecialchars($_POST['ma_phieu']) : $ma_phieu_random);
								?>">
						</div>
						<div class="form-group">
							<label for="ngay">Ngày</label>
							<?php
							// Lấy ngày giờ hiện tại theo múi giờ Việt Nam
							date_default_timezone_set('Asia/Ho_Chi_Minh');
							$today_vn = date('Y-m-d');
							?>
							<input type="date" class="form-control" name="ngay" id="ngay" required
								value="<?php
									echo isset($edit_phieu) && $edit_phieu
										? $edit_phieu['ngay']
										: (isset($_POST['ngay']) ? htmlspecialchars($_POST['ngay']) : $today_vn);
								?>">
						</div>
						<div class="form-group">
							<label for="nhan_vien">Nhân viên</label>
							<?php
							// Lấy danh sách user từ session hoặc từ DB (giống ql_user)
							if (!isset($users)) {
								require_once 'model/user_db.php';
								$users = get_user();
							}
							$is_admin = isset($_SESSION['Loai']) && $_SESSION['Loai'] == 2;
							$current_user = isset($_SESSION['Username']) ? $_SESSION['Username'] : '';
							?>
							<select class="form-control" name="nhan_vien" id="nhan_vien" required <?php if(!$is_admin) echo 'disabled'; ?>>
								<option value="">--Chọn nhân viên--</option>
								<?php foreach ($users as $user): ?>
									<?php
									$is_selected = false;
									if (isset($edit_phieu) && $edit_phieu && $edit_phieu['nhan_vien'] == $user['Username']) $is_selected = true;
									if (!isset($edit_phieu) && $current_user == $user['Username']) $is_selected = true;
									?>
									<?php if ($is_admin || $user['Username'] == $current_user): ?>
										<option value="<?php echo htmlspecialchars($user['Username']); ?>" <?php if($is_selected) echo 'selected'; ?>>
											<?php echo htmlspecialchars($user['Username']); ?>
										</option>
									<?php endif; ?>
								<?php endforeach; ?>
							</select>
							<?php if(!$is_admin): ?>
								<input type="hidden" name="nhan_vien" value="<?php echo htmlspecialchars($current_user); ?>">
							<?php endif; ?>
						</div>
						<button type="submit" class="btn btn-primary" style="border-radius:8px;"><?php echo isset($edit_phieu) && $edit_phieu ? 'Lưu thay đổi' : 'Thêm phiếu'; ?></button>
						<?php if (isset($edit_phieu) && $edit_phieu): ?>
							<a href="?action=ql_phieuXNKho" class="btn btn-default" style="border-radius:8px;">Hủy</a>
						<?php endif; ?>
					</form>
				</div>
			</div>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-12" style="margin-bottom:16px;">
			<div class="panel panel-default" style="margin-bottom:16px;">
				<div class="panel-heading">
					<h4 style="font-weight:600;margin:0;">Danh sách Phiếu Nhập Kho</h4>
				</div>
				<div class="panel-body">
					<table class="table table-striped table-bordered" style="width:100%;border-radius:12px;overflow:hidden;background:#fafbfc;">
						<thead>
							<tr style="background:#e3eaf1;">
								<th>Mã phiếu</th>
								<th>Ngày</th>
								<th>Nhân viên</th>
								<th>Nhóm SP</th>
								<th>Sản phẩm</th>
								<th>Kho</th>
								<th>Số lượng</th>
								<th>Trạng thái</th>
								<th>Hành động</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($ds_phieu_nhap as $phieu): ?>
							<tr id="row-phieu-<?php echo $phieu['id']; ?>">
								<td><?php echo htmlspecialchars($phieu['ma_phieu']); ?></td>
								<td><?php echo date('d/m/Y', strtotime($phieu['ngay'])); ?></td>
								<td><?php echo htmlspecialchars($phieu['nhan_vien']); ?></td>
								<td>
									<?php
									foreach ($ds_nhomsp as $nhom) {
										if ($nhom['IdNhomSP'] == $phieu['id_nhomsp']) {
											echo htmlspecialchars($nhom['TenNhomSP']);
											break;
										}
									}
									?>
								</td>
								<td>
									<?php
									foreach ($ds_sp as $sp) {
										if ($sp['IdSP'] == $phieu['id_sp']) {
											echo htmlspecialchars($sp['TenSP']);
											break;
										}
									}
									?>
								</td>
								<td>
									<?php
									foreach ($ds_kho as $kho) {
										if ($kho['IdKho'] == $phieu['id_kho']) {
											echo htmlspecialchars($kho['TenKho']);
											break;
										}
									}
									?>
								</td>
								<td><?php echo (int)$phieu['so_luong']; ?></td>
								<td><span class="badge" style="background:#388e3c;">Nhập</span></td>
								<td>
									<a href="?action=ql_phieuXNKho&edit_id=<?php echo $phieu['id']; ?>" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i> Sửa</a>
									<form method="post" class="delete-phieu-form" style="display:inline;">
										<input type="hidden" name="action" value="del_phieu">
										<input type="hidden" name="id" value="<?php echo $phieu['id']; ?>">
										<button type="submit" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Xóa</button>
									</form>
								</td>
							</tr>
							<?php endforeach; ?>
							<?php if (empty($ds_phieu_nhap)): ?>
							<tr><td colspan="9" style="text-align:center;">Không có phiếu nhập kho</td></tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 style="font-weight:600;margin:0;">Danh sách Phiếu Xuất Kho</h4>
				</div>
				<div class="panel-body">
					<table class="table table-striped table-bordered" style="width:100%;border-radius:12px;overflow:hidden;background:#fafbfc;">
						<thead>
							<tr style="background:#e3eaf1;">
								<th>Mã phiếu</th>
								<th>Ngày</th>
								<th>Nhân viên</th>
								<th>Nhóm SP</th>
								<th>Sản phẩm</th>
								<th>Kho</th>
								<th>Số lượng</th>
								<th>Trạng thái</th>
								<th>Hành động</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($ds_phieu_xuat as $phieu): ?>
							<tr id="row-phieu-<?php echo $phieu['id']; ?>">
								<td><?php echo htmlspecialchars($phieu['ma_phieu']); ?></td>
								<td><?php echo date('d/m/Y', strtotime($phieu['ngay'])); ?></td>
								<td><?php echo htmlspecialchars($phieu['nhan_vien']); ?></td>
								<td>
									<?php
									foreach ($ds_nhomsp as $nhom) {
										if ($nhom['IdNhomSP'] == $phieu['id_nhomsp']) {
											echo htmlspecialchars($nhom['TenNhomSP']);
											break;
										}
									}
									?>
								</td>
								<td>
									<?php
									foreach ($ds_sp as $sp) {
										if ($sp['IdSP'] == $phieu['id_sp']) {
											echo htmlspecialchars($sp['TenSP']);
											break;
										}
									}
									?>
								</td>
								<td>
									<?php
									foreach ($ds_kho as $kho) {
										if ($kho['IdKho'] == $phieu['id_kho']) {
											echo htmlspecialchars($kho['TenKho']);
											break;
										}
									}
									?>
								</td>
								<td><?php echo (int)$phieu['so_luong']; ?></td>
								<td><span class="badge" style="background:#d32f2f;">Xuất</span></td>
								<td>
									<a href="?action=ql_phieuXNKho&edit_id=<?php echo $phieu['id']; ?>" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i> Sửa</a>
									<form method="post" class="delete-phieu-form" style="display:inline;">
										<input type="hidden" name="action" value="del_phieu">
										<input type="hidden" name="id" value="<?php echo $phieu['id']; ?>">
										<button type="submit" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Xóa</button>
									</form>
								</td>
							</tr>
							<?php endforeach; ?>
							<?php if (empty($ds_phieu_xuat)): ?>
							<tr><td colspan="9" style="text-align:center;">Không có phiếu xuất kho</td></tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function() {
    $('.delete-phieu-form').on('submit', function(e) {
        e.preventDefault();
        if (!confirm('Bạn chắc chắn muốn xóa phiếu này?')) return false;
        var $form = $(this);
        var row = $form.closest('tr');
        $.post('', $form.serialize(), function() {
            row.fadeOut(300, function() { $(this).remove(); });
        });
    });
	$('#add-sp-row').on('click', function() {
		var row = $('#ds_sanpham .sanpham-row:first').clone();
		row.find('select, input').val('');
		$('#ds_sanpham').append(row);
	});
});
</script>


