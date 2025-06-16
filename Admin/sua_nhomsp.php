<?php include 'view/header_admin.php'; ?>
		<div class="right-col col-lg-10 col-md-9 col-sm-9 col-xs-12" style="background-color:#F7F7F7;color:#73879C;">
			<!-- Tiêu đề trang -->
			<div class="tittle">
				<h3>Quản lý sản phẩm</h3>
			</div>
			<!-- Cột trái: Danh sách nhóm sản phẩm -->
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="mini-content">
					<div class="title">
						<h2>Danh sách nhóm sản phẩm</h2>
					</div>
					<div class="main-mini-content">
						<table class="table table-striped" id="center-content">
							<thead>
								<tr>
									<th style="width:50px;">&nbsp</th>
								    <th>Tên nhóm sản phẩm</th>
								    <th>Hình ảnh</th>
									<th colspan="2">Sửa đổi</th>
								</tr>
							</thead>
							<?php foreach ($categories as $category) : ?>
								<tr>
									<td>
										<!-- Nút xóa nhóm sản phẩm -->
										<form method="post">
											<input type="hidden" name="action" value="del_nhomsp">
											<label for="<?php echo $category['IdNhomSP']; ?>" class="btn"><i class="fa fa-window-close"></i></label>
	    									<input id="<?php echo $category['IdNhomSP']; ?>" type="submit" name="category_id" value="<?php echo $category['IdNhomSP']; ?>" class="hidden" />
										</form>
									</td>
									<td>
										<!-- Tên nhóm sản phẩm, click để lọc -->
										<a href="?action=ql_sp&category_id=<?php echo $category['IdNhomSP']; ?>&hangsx=0"><?php echo $category['TenNhomSP']; ?></a>
									</td>
									<td>
										<!-- Hiển thị hình ảnh nhóm sản phẩm -->
										<?php
											$img_file = !empty($category['HinhNSP']) ? basename($category['HinhNSP']) : '';
											$img_path = "../images/nhomsp/" . $img_file;
											if ($img_file && file_exists($img_path)) {
												echo '<img style="width:100px;" src="' . $img_path . '">';
											} else {
												echo '<span style="color:#ccc;"><i class="fa fa-picture-o" style="font-size:22px;"></i></span>';
											}
										?>
									</td>
									<td>
										<!-- Nút sửa nhóm sản phẩm -->
										<a href="?action=edit_nsp&category_id=<?php echo $category['IdNhomSP']; ?>"><i class="fa fa-pencil-square-o"></i></a>
									</td>
								</tr>
							<?php endforeach; ?>
						</table>
					</div>
				</div>
			</div>
			<!-- Cột phải: Form sửa nhóm sản phẩm -->
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="mini-content">
					<div class="title">
						<h2>Sửa đổi thông tin nhóm sản phẩm</h2>
					</div>
					<div class="main-mini-content">
						<form class="form-group" method="post" enctype="multipart/form-data">
							<input type="hidden" name="action" value="sua_nhomsp">
							<input type="hidden" name="category_id" value="<?php echo $category_id; ?>">
							<div class="form-group">
								<label for="exampleInputName2">Nhập tên</label>
								<input type="text" class="form-control" name="ten_nhomsp" value="<?php echo $category_name1; ?>">
							</div>
							<div class="form-group">
								<label for="hinh_nhomsp">Chọn hình ảnh</label>
								<input type="file" class="form-control" name="hinh_nhomsp" id="hinh_nhomsp">
								<?php if (!empty($category_img)): ?>
									<div style="margin-top:8px;">
										<!-- Hiển thị hình ảnh hiện tại -->
										<img src="../images/nhomsp/<?php echo basename($category_img); ?>" style="max-width:120px;">
										<!-- Input ẩn lưu tên file cũ -->
										<input type="hidden" name="hinh_nhomsp_old" value="<?php echo basename($category_img); ?>">
									</div>
								<?php endif; ?>
							</div>
							<button type="submit" class="btn btn-primary">Thay đổi</button>
						</form>
					</div>
				</div>
			</div>
			<!-- Bảng danh sách sản phẩm thuộc nhóm -->
			<div class="content col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="mini-content">
					<div class="title">
						<h2>Danh sách sản phẩm >> <?php echo $category_name; ?></h2>
					</div>
					<div class="main-mini-content">
						<table class="table table-striped">
							<thead>
								<tr>
									<th style="width:50px;">&nbsp</th>
									<th style="width:300px;">Tên sản phẩm</th>
									<th style="width:100px;">Hình Ảnh</th>
									<th>Số lượng</th>
									<th>Hạn sử dụng</th>
									<th>Hết hạn</th>
									<th>Thay đổi</th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($products as $product) : ?>
							<tr>
								<td>
									<!-- Nút xóa sản phẩm -->
									<form method="post">
										<input type="hidden" name="action" value="del_sp">
										<label for="<?php echo $product['IdSP']; ?>" class="btn"><i class="fa fa-window-close"></i></label>
										<input id="<?php echo $product['IdSP']; ?>" type="submit" name="id_sanpham" value="<?php echo $product['IdSP']; ?>" class="hidden" />
									</form>
								</td>
								<td>
									<!-- Tên sản phẩm -->
									<?php echo $product['TenSP']; ?>
								</td>
								<td>
									<!-- Hình ảnh sản phẩm -->
									<img style="width:100px;" src="../images<?php echo $product['Hinh']; ?>">
								</td>
								<td><?php echo (int)$product['SoLuong']; ?></td>
								<td><?php echo date('d/m/Y', strtotime($product['HanSD'])); ?></td>
								<td><?php echo date('d/m/Y', strtotime($product['HetHan'])); ?></td>
								<td>
									<!-- Nút sửa sản phẩm -->
									<a href="?action=edit_sp&product_id=<?php echo $product['IdSP']; ?>"><i class="fa fa-pencil-square-o"></i></a>
								</td>
							</tr>
							<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	<?php include 'view/footer_admin.php'; ?>
