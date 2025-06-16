<?php include 'view/header_admin.php'; ?>
<div class="right-col col-lg-10 col-md-9 col-sm-9 col-xs-12" style="background:#f4f6f9;color:#283e51;min-height:100vh;padding:16px 8px 8px 8px;">
	<div class="tittle" style="margin-bottom:16px;">
		<h3 style="font-weight:600;">Quản lý hình ảnh</h3>
	</div>
	<div class="row">
		<div class="col-lg-8 col-md-8 col-sm-12" style="margin-bottom:16px;">
			<div class="panel panel-default" style="margin-bottom:16px;">
				<div class="panel-heading">
					<h4 style="font-weight:600;margin:0;">Hình ảnh các nhóm sản phẩm</h4>
				</div>
				<div class="panel-body">
					<div class="row">
					<?php foreach ($hinh_nsp as $file) { ?>
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" style="min-height: 150px;border:1px solid #E6E9ED;margin-bottom:12px;background:#fafbfc;padding:8px;">
							<img data-toggle="modal" class="img-exm bs-example-modal-lg" data-target="#<?php echo substr($file,0,-4); ?>" src="../images/nhomsp/<?php echo $file; ?>" style="max-width:100%;margin:12px 0;">
							<div class="modal fade bs-example-modal-lg"  id="<?php echo substr($file,0,-4); ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
								<div class="modal-dialog modal-lg" role="document">
									<div class="modal-content">
										<div class="modal-header">
										    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										    <h4 class="modal-title" id="myModalLabel">/nhomsp/<?php echo $file; ?></h4>
										</div>
										<div class="modal-body" style="text-align:center;">
											<img style="max-width:100%;" src="../images/nhomsp/<?php echo $file; ?>">
									    </div>
										<div class="modal-footer">
											<form method="post">
												<input type="hidden" name="action" value="del_img_nsp">
												<label for="<?php echo $file; ?>" class="btn"><i class="fa fa-trash-o"></i></label>
		    									<input id="<?php echo $file; ?>" type="submit" name="id_img" value="<?php echo $file; ?>" class="hidden" />
											</form>
									    </div>
									</div>
								</div>
							</div>		
						</div>		
					<?php } ?>
					</div>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 style="font-weight:600;margin:0;">Hình ảnh các sản phẩm</h4>
				</div>
				<div class="panel-body">
					<div class="row">
					<?php foreach ($hinh_sp as $file) { ?>
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" style="min-height: 150px;border:1px solid #E6E9ED;margin-bottom:12px;background:#fafbfc;padding:8px;">
							<img data-toggle="modal" class="img-exm bs-example-modal-lg" data-target="#<?php echo substr($file,0,-4); ?>" src="../images/sanpham/<?php echo $file; ?>" style="max-width:100%;margin:12px 0;">
							<div class="modal fade bs-example-modal-lg"  id="<?php echo substr($file,0,-4); ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
								<div class="modal-dialog modal-lg" role="document">
									<div class="modal-content">
										<div class="modal-header">
										    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										    <h4 class="modal-title" id="myModalLabel">/sanpham/<?php echo $file; ?></h4>
										</div>
										<div class="modal-body" style="text-align:center;">
											<img style="max-width:100%;" src="../images/sanpham/<?php echo $file; ?>">
									    </div>
									    <div class="modal-footer">
									    	<form method="post">
												<input type="hidden" name="action" value="del_img_sp">
												<label for="<?php echo $file; ?>" class="btn"><i class="fa fa-trash-o"></i></label>
		    									<input id="<?php echo $file; ?>" type="submit" name="id_img" value="<?php echo $file; ?>" class="hidden" />
											</form>
									    </div>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-12" style="margin-bottom:16px;">
			<div class="panel panel-default" style="margin-bottom:16px;">
				<div class="panel-heading">
					<h4 style="font-weight:600;margin:0;">Thêm hình ảnh nhóm sản phẩm</h4>
				</div>
				<div class="panel-body">
					<form action="upload_nsp.php" class="dropzone">
						<div class="dz-default dz-message">
							<span>Drop files here to upload</span>
						</div>
					</form>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 style="font-weight:600;margin:0;">Thêm hình ảnh sản phẩm</h4>
				</div>
				<div class="panel-body">
					<form action="upload_sp.php" class="dropzone">
						<div class="dz-default dz-message">
							<span>Drop files here to upload</span>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>