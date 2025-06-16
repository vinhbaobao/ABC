<?php include 'view/header_admin.php'; ?>
		<div class="right-col col-lg-10 col-md-9 col-sm-9 col-xs-12" style="background-color:#F7F7F7;color:#73879C;">
			<!-- Tiêu đề trang -->
			<div class="tittle">
				<h3>Sửa đổi thông tin sản phẩm</h3>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="mini-content">
					<div class="main-mini-content">
						<form class="form-horizontal" method="post">
							<input type="hidden" name="action" value="sua_sp">
							<input type="hidden" name="product_id" value="<?php echo $products['IdSP']; ?>">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<!-- Hiển thị hình ảnh sản phẩm -->
								<div style="width:100%;float:left;text-align:center;margin-bottom:20px;">
									<img style="max-width:100%;" src="../images<?php echo $products['Hinh']; ?>">
								</div>
								<!-- Nhập URL hình ảnh -->
								<div class="form-group">
									<label for="hinh" class="col-sm-3 control-label">URL</label>
								    <div class="col-sm-9">
								      <input type="text" class="form-control" id="hinh" name="hinh" value="<?php echo $products['Hinh']; ?>">
								    </div>
								</div>
								<!-- Nhập tên sản phẩm -->
								<div class="form-group">
									<label for="ten_sp" class="col-sm-3 control-label">Tên sản phẩm</label>
								    <div class="col-sm-9">
								      <input type="text" class="form-control" id="ten_sp" name="ten_sp" value="<?php echo $products['TenSP']; ?>">
								    </div>
								</div>
								<!-- Hiển thị chi tiết sản phẩm -->
								<div class="form-group">
									<label for="ten_sp" class="col-sm-12 control-label">Chi tiết sản phẩm</label>
								    <div class="col-sm-12" style="margin-top:20px;">
								      <?php echo $products['ChiTiet']; ?>
								    </div>
								</div>	
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<div class="title">
									<h2><?php echo $products['TenSP']; ?></h2>
								</div>
								<!-- Chọn nhóm sản phẩm -->
								<div class="form-group">
									<label for="category_id" class="col-sm-3 control-label">Loại sản phẩm</label>
								    <div class="col-sm-9">
									    <select class="form-control" name="category_id">
								            <?php foreach ($categories as $category) : ?>
								                <option value="<?php echo $category['IdNhomSP']; ?>"
								                    <?php if ($category['IdNhomSP'] == $products['IdNhomSP']) echo 'selected'; ?>>
								                    <?php echo $category['TenNhomSP']; ?>
								                </option>
								            <?php endforeach; ?>
							        	</select>
								    </div>
								</div>
								<!-- Chọn kho -->
								<div class="form-group">
									<label for="id_kho" class="col-sm-3 control-label">Kho</label>
								    <div class="col-sm-9">
									    <select class="form-control" name="id_kho" id="id_kho">
								            <?php
								            $current_kho = isset($products['id_kho']) ? $products['id_kho'] : (isset($products['IdKho']) ? $products['IdKho'] : '');
								            $stmt_kho = $db->query("SELECT IdKho, TenKho FROM kho");
								            foreach ($stmt_kho as $row_kho) : ?>
								                <option value="<?php echo $row_kho['IdKho']; ?>"
								                    <?php if ($row_kho['IdKho'] == $current_kho) echo 'selected'; ?>>
								                    <?php echo htmlspecialchars($row_kho['TenKho']); ?>
								                </option>
								            <?php endforeach; ?>
							        	</select>
								    </div>
								</div>
								<!-- Nhập hạn sử dụng -->
								<div class="form-group">
									<label for="hansd" class="col-sm-3 control-label">Hạn sử dụng</label>
								    <div class="col-sm-9">
								        <input type="date" class="form-control text-right" id="hansd" name="hansd"
								        value="<?php echo (!empty($products['HanSD']) && strtotime($products['HanSD']) && date('Y-m-d', strtotime($products['HanSD'])) > '1900-01-01'
								            ? date('Y-m-d', strtotime($products['HanSD'])) : ''); ?>">
								    </div>
								</div>
								<!-- Nhập ngày hết hạn -->
								<div class="form-group">
									<label for="hethan" class="col-sm-3 control-label">Hết hạn</label>
								    <div class="col-sm-9">
								        <input type="date" class="form-control text-right" id="hethan" name="hethan"
								        value="<?php echo (!empty($products['HetHan']) && strtotime($products['HetHan']) && date('Y-m-d', strtotime($products['HetHan'])) > '1900-01-01'
								            ? date('Y-m-d', strtotime($products['HetHan'])) : ''); ?>">
								    </div>
								</div>
								<!-- Nhập số lượng -->
								<div class="form-group">
									<label for="soluong" class="col-sm-3 control-label">Số lượng</label>
								    <div class="col-sm-9">
								        <input type="number" class="form-control text-right" id="soluong" name="soluong" value="<?php echo (int)$products['SoLuong']; ?>" min="0">
								    </div>
								</div>
								<!-- Nút thay đổi -->
								<div class="form-group" style="margin-top:50px;">
									<div class="col-sm-offset-10 col-sm-2">
								      <button type="submit" class="btn btn-default">Thay đổi</button>
								    </div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</content>
</body>
</html>