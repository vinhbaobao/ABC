<?php
include_once 'model/nhomsp_db.php';

// Lấy danh sách nhóm sản phẩm
$categories = get_nhomsp();

// Lấy thông tin nhóm sản phẩm cần sửa
$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
$category_name1 = '';
$category_img = '';
if ($category_id > 0) {
    $category_name1 = get_ten_nhomsp($category_id);
    $category_img = get_hinh_nhomsp($category_id);
}

include 'view/header_admin.php';
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
		</div>
	</div>
