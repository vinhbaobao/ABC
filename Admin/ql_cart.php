<?php include 'view/header_admin.php'; ?>
<div class="right-col col-lg-10 col-md-9 col-sm-9 col-xs-12" style="background:#f4f6f9;color:#283e51;min-height:100vh;padding:16px 8px 8px 8px;">
	<div class="tittle" style="margin-bottom:16px;">
		<h3 style="font-weight:600;">Quản lý đơn hàng</h3>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 style="font-weight:600;margin:0;">Danh sách đơn hàng</h4>
		</div>
		<div class="panel-body">
			<table class="table table-striped table-bordered" style="background:#fff;">
				<thead>
					<tr style="background:#e3eaf1;">
						<th style="width:50px;">&nbsp;</th>
						<th>Tên khách hàng</th>
						<th>Email</th>
						<th>Chi tiết</th>
						<th>Tổng tiền</th>
						<th>Thời gian</th>
						<th>Trạng thái</th>
						<th style="width:50px;">&nbsp;</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($categories as $category) : ?>
					<tr>
						<td>
							<form method="post" style="margin:0;">
								<input type="hidden" name="action" value="del_cart">
								<button type="submit" class="btn btn-link" style="padding:0;"><i class="fa fa-window-close"></i></button>
								<input type="hidden" name="id" value="<?php echo $category['IdCart']; ?>" />
							</form>
						</td>
						<td><?php echo htmlspecialchars(get_ten_user($category['IdUser'])); ?></td>
						<td><?php echo htmlspecialchars(get_email_user($category['IdUser'])); ?></td>
						<td><?php echo htmlspecialchars($category['ChiTiet']); ?></td>
						<td><?php echo number_format($category['GiaTien']); ?></td>
						<td><?php echo htmlspecialchars($category['ThoiGian']); ?></td>
						<td><?php echo htmlspecialchars($category['TrangThai']); ?></td>
						<td>
							<form method="post" style="margin:0;">
								<input type="hidden" name="action" value="up_cart">
								<input type="hidden" name="cartid" value="<?php echo $category['IdCart'];?>">
								<input type="hidden" name="userid" value="<?php echo $category['IdUser'];?>">
								<input type="hidden" name="chitiet" value="<?php echo htmlspecialchars($category['ChiTiet']);?>">
								<input type="hidden" name="giatien" value="<?php echo $category['GiaTien'];?>">
								<input type="hidden" name="date" value="<?php echo $category['ThoiGian'];?>">
								<?php
									if($category['TrangThai'] == 'Chưa thanh toán'){
										echo "<input type='hidden' name='trangthai' value='Đã thanh toán'>";
									}
									else{
										echo "<input type='hidden' name='trangthai' value='Chưa thanh toán'>";
									}
								 ?>
								<button type="submit" class="btn btn-link" style="padding:0;"><i class="fa fa-pencil-square-o"></i></button>
							</form>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
</body>
</html>