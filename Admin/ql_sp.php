<?php include 'view/header_admin.php'; ?>
<style>
/* ======= PHẦN: Style giao diện quản lý sản phẩm ======= */
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

<!-- ======= PHẦN: Layout chính, tiêu đề trang ======= -->
<div class="right-col col-lg-10 col-md-9 col-sm-9 col-xs-12" style="background:#f4f6f9;color:#283e51;min-height:100vh;padding:16px 8px 8px 8px;">
    <!-- ======= PHẦN: Tiêu đề trang ======= -->
    <div class="tittle" style="margin-bottom:16px;">
        <h3 style="font-weight:600;">Quản lý sản phẩm</h3>
    </div>
    <div class="row">
        <!-- ======= PHẦN: Cột trái - Nhóm sản phẩm, kho ======= -->
        <div class="col-lg-6 col-md-6 col-sm-12" style="margin-bottom:16px;">
            <!-- Danh sách nhóm sản phẩm -->
            <div class="panel panel-default" style="margin-bottom:16px;">
                <div class="panel-heading" style="display:flex;align-items:center;justify-content:space-between;">
                    <h4 style="font-weight:600;margin:0;">Danh sách nhóm sản phẩm</h4>
                    <button id="toggle-category-list" type="button" class="btn btn-default btn-xs" style="margin-left:10px;">
                        <span id="toggle-category-list-icon" class="glyphicon glyphicon-chevron-down"></span>
                    </button>
                </div>
                <div class="panel-body" id="category-list-content">
                    <table class="table table-striped table-bordered" style="background:#fff;">
                        <thead>
                            <tr style="background:#e3eaf1;">
                                <th style="width:50px;">&nbsp;</th>
                                <th>Tên nhóm sản phẩm</th>
                                <th>Hình ảnh</th>
                                <th colspan="2">Sửa đổi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($categories as $category) : ?>
                        <tr>
                            <td>
                                <form method="post" class="delete-nhomsp-form" action="" style="margin:0;">
                                    <input type="hidden" name="action" value="del_nhomsp">
                                    <input type="hidden" name="category_id" value="<?php echo $category['IdNhomSP']; ?>">
                                    <button type="submit" class="btn btn-link" style="padding:0;"><i class="fa fa-window-close"></i></button>
                                </form>
                            </td>
                            <td>
                                <a href="?action=ql_sp&category_id=<?php echo $category['IdNhomSP']; ?>"><?php echo $category['TenNhomSP']; ?></a>
                            </td>
                            <td>
                                <img style="width:100px;" src="../images/nhomsp/<?php echo htmlspecialchars($category['HinhNSP']); ?>">
                            </td>
                            <td>
                                <a href="?action=edit_nsp&category_id=<?php echo $category['IdNhomSP']; ?>"><i class="fa fa-pencil-square-o"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Thêm nhóm sản phẩm -->
            <div class="panel panel-default" style="margin-bottom:16px;">
                <div class="panel-heading">
                    <h4 style="font-weight:600;margin:0;">Thêm nhóm sản phẩm</h4>
                </div>
                <div class="panel-body">
                    <form class="form-group" method="post" enctype="multipart/form-data" onsubmit="return validateNhomSPForm();">
                        <input type="hidden" name="action" value="them_nhomsp">
                        <div class="form-group">
                            <label for="ten_nhomsp">Nhập tên</label>
                            <input type="text" class="form-control" name="ten_nhomsp" id="ten_nhomsp" placeholder="Nhập tên nhóm sản phẩm" required>
                        </div>
                        <div class="form-group">
                            <label for="hinh_nhomsp">Chọn hình ảnh Nhóm SP</label>
                            <input type="file" class="form-control" name="hinh_nhomsp" id="hinh_nhomsp" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Thêm</button>
                    </form>
                </div>
            </div>
            <!-- Thêm kho -->
            <div class="panel panel-default" style="margin-bottom:16px;">
                <div class="panel-heading">
                    <h4 style="font-weight:600;margin:0;">Thêm kho</h4>
                </div>
                <div class="panel-body">
                    <form class="form-group" method="post" id="form-them-kho" autocomplete="off" onsubmit="return validateKhoForm();">
                        <input type="hidden" name="action" value="them_kho">
                        <div class="form-group">
                            <label for="ten_kho">Nhập tên kho</label>
                            <input type="text" class="form-control" name="ten_kho" id="ten_kho" placeholder="Nhập tên kho" required>
                        </div>
                        <div class="form-group">
                            <label for="diachi">Địa chỉ</label>
                            <input type="text" class="form-control" name="diachi" id="diachi" placeholder="Nhập địa chỉ kho" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Thêm kho</button>
                    </form>
                    <div id="them-kho-msg"></div>
                </div>
            </div>
            <!-- Danh sách kho -->
            <div class="panel panel-default">
                <div class="panel-heading" style="display:flex;align-items:center;justify-content:space-between;">
                    <h4 style="font-weight:600;margin:0;">Danh sách kho</h4>
                    <button id="toggle-kho-list" type="button" class="btn btn-default btn-xs" style="margin-left:10px;">
                        <span id="toggle-kho-list-icon" class="glyphicon glyphicon-chevron-down"></span>
                    </button>
                </div>
                <div class="panel-body" id="kho-list-content" style="width:100%;">
                    <table class="table table-striped table-bordered" id="kho_datatable" style="width:100%;background:#fff;">
                        <thead>
                            <tr style="background:#e3eaf1;">
                                <th style="width:50px;">&nbsp;</th>
                                <th style="width:200px;">Tên kho</th>
                                <th style="width:300px;">Địa chỉ</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-kho">
                            <?php
                            $ds_kho = get_all_kho();
                            foreach ($ds_kho as $kho):
                            ?>
                            <tr>
                                <td>
                                    <form method="post" class="delete-kho-form" action="" style="margin:0;">
                                        <input type="hidden" name="action" value="del_kho">
                                        <input type="hidden" name="id_kho" value="<?php echo $kho['IdKho']; ?>">
                                        <button type="submit" class="btn btn-link" style="padding:0;"><i class="fa fa-window-close"></i></button>
                                    </form>
                                </td>
                                <td>
                                    <a href="?action=ql_sp&kho_id=<?php echo $kho['IdKho']; ?>">
                                        <?php echo htmlspecialchars($kho['TenKho']); ?>
                                    </a>
                                </td>
                                <td><?php echo htmlspecialchars($kho['DiaChi']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($ds_kho)): ?>
                                <tr><td colspan="3" style="text-align:center;">Không có dữ liệu kho</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- ======= PHẦN: Cột phải - Thêm sản phẩm ======= -->
        <div class="col-lg-6 col-md-6 col-sm-12" style="margin-bottom:16px;">
            <div class="panel panel-default">
                <div class="panel-heading" style="display:flex;align-items:center;justify-content:space-between;">
                    <h4 style="font-weight:600;margin:0;">Thêm sản phẩm</h4>
                    <button id="toggle-add-product" type="button" class="btn btn-default btn-xs" style="margin-left:10px;">
                        <span id="toggle-add-product-icon" class="glyphicon glyphicon-chevron-down"></span>
                    </button>
                </div>
                <div class="panel-body" id="add-product-content">
                    <form class="form-group" method="post" enctype="multipart/form-data" onsubmit="return validateSanPhamForm();">
                        <input type="hidden" name="action" value="them_sp">
                        <div class="form-group">
                            <label for="category_id">Chọn nhóm sản phẩm</label>
                            <select class="form-control" name="category_id" id="category_id" required>
                        <option value="0">--Chọn nhóm --</option>
                        <?php foreach ($categories2 as $category) : ?> 
                            <option value="<?php echo $category['IdNhomSP']; ?>"> 
                                <?php echo $category['TenNhomSP']; ?> 
                            </option> 
                        <?php endforeach; ?> 
                    </select>
                        </div>
                        <div class="form-group">
                            <label for="ten_sp">Tên sản phẩm</label>
                            <input type="text" class="form-control" name="ten_sp" id="ten_sp" placeholder="Nhập tên sản phẩm" required>
                        </div>
                        <div class="form-group">
                            <label for="id_kho">Kho</label>
                            <select class="form-control" name="id_kho" id="id_kho" required>
                                <option value="0">--Chọn kho--</option>
                                <?php
                                $stmt_kho = $db->query("SELECT IdKho, TenKho FROM kho");
                                foreach ($stmt_kho as $row_kho) : ?>
                                    <option value="<?php echo $row_kho['IdKho']; ?>">
                                        <?php echo htmlspecialchars($row_kho['TenKho']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="hinh">Chọn hình ảnh</label>
                            <input type="file" class="form-control" name="hinh_file" id="hinh" required>
                        </div>
                        <div class="form-group">
                            <label for="hansd">Hạn sử dụng</label>
                            <input type="date" class="form-control" name="hansd" id="hansd" required>
                        </div>
                        <div class="form-group">
                            <label for="hethan">Hết hạn</label>
                            <input type="date" class="form-control" name="hethan" id="hethan" required>
                        </div>
                        <div class="form-group">
                            <label for="soluong">Số lượng</label>
                            <input type="number" class="form-control" name="soluong" id="soluong" placeholder="Nhập số lượng" min="0" required>
                        </div>
                        <div class="form-group">
                            <label for="chitiet">Thông tin chi tiết sản phẩm</label>
                            <textarea id="chitiet" name="chitiet" required></textarea>
                            <script type="text/javascript">CKEDITOR.replace( 'chitiet' );</script>
                        </div>
                        <button type="submit" class="btn btn-primary">Thêm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- ======= PHẦN: Bảng danh sách sản phẩm ======= -->
    <div class="content col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 style="font-weight:600;margin:0;">Danh sách sản phẩm &gt;&gt; <?php echo $category_name; ?></h4>
            </div>
            <div class="panel-body">
                <table class="table table-striped" id="sp_datatable" style="width:100%;background:#fafbfc;">
                    <thead>
                        <tr style="background:#e3eaf1;">
                            <th style="width:50px;">&nbsp</th>
                            <th style="width:200px;">Kho</th>
                            <th style="width:300px;">Tên sản phẩm</th>
                            <th style="width:100px;">Hình Ảnh</th>
                            <th style="width:100px;">Hạn sử dụng</th>
                            <th style="width:100px;">Hết hạn</th>
                            <th style="width:100px;">Số lượng</th>
                            <th>Thay đổi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $ds_kho = [];
                    $stmt_kho = $db->query("SELECT IdKho, TenKho FROM kho");
                    foreach ($stmt_kho as $row_kho) {
                        $ds_kho[$row_kho['IdKho']] = $row_kho['TenKho'];
                    }
                    foreach ($products as $product) : ?>
                    <tr>
                        <td>
                            <form method="post" class="delete-sp-form" action="" style="margin:0;">
                                <input type="hidden" name="action" value="del_sp">
                                <input type="hidden" name="id_sanpham" value="<?php echo $product['IdSP']; ?>">
                                <button type="submit" class="btn btn-link" style="padding:0;"><i class="fa fa-window-close"></i></button>
                            </form>
                        </td>
                        <td>
                            <?php
                            $id_kho = isset($product['id_kho']) ? $product['id_kho'] : (isset($product['IdKho']) ? $product['IdKho'] : null);
                            echo isset($ds_kho[$id_kho]) ? htmlspecialchars($ds_kho[$id_kho]) : '---';
                            ?>
                        </td>
                        <td style="text-align:center; vertical-align:middle;"><?php echo $product['TenSP']; ?></td>
                        <td><img style="width:100px;" src="../images<?php echo $product['Hinh']; ?>"></td>
                        <td><?php echo date('d/m/Y', strtotime($product['HanSD'])); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($product['HetHan'])); ?></td>
                        <td><?php echo (int)$product['SoLuong']; ?></td>
                        <td>
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
</content>
</body>
</html>
<!-- ======= PHẦN: Script xử lý AJAX, UI ======= -->
<!-- textnote: Script xử lý AJAX xóa và thu/phóng -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function() {
    // Xử lý xóa nhóm sản phẩm, sản phẩm, kho
    $('.delete-nhomsp-form').on('submit', function(e) {
        e.preventDefault();
        var $form = $(this);
        var row = $form.closest('tr');
        $.post('', $form.serialize(), function(res) {
            row.fadeOut(300, function() { $(this).remove(); });
        });
    });
    $('.delete-sp-form').on('submit', function(e) {
        e.preventDefault();
        var $form = $(this);
        var row = $form.closest('tr');
        $.post('', $form.serialize(), function(res) {
            row.fadeOut(300, function() { $(this).remove(); });
        });
    });
    $('.delete-kho-form').on('submit', function(e) {
        e.preventDefault();
        var $form = $(this);
        var row = $form.closest('tr');
        $.post('', $form.serialize(), function(res) {
            row.fadeOut(300, function() { $(this).remove(); });
        });
    });
    // Cho phép click vào label để submit form
    $('label[for^="nsp_"], label[for^="sp_"]').on('click', function() {
        var inputId = $(this).attr('for');
        $('#' + inputId).closest('form').submit();
    });
    // Thu/phóng phần thêm sản phẩm
    $('#toggle-add-product').on('click', function() {
        $('#add-product-content').slideToggle(200);
        var $icon = $('#toggle-add-product-icon');
        $icon.toggleClass('glyphicon-chevron-down glyphicon-chevron-right');
    });
    // Thu/phóng danh sách nhóm sản phẩm
    $('#toggle-category-list').on('click', function() {
        $('#category-list-content').slideToggle(200);
        var $icon = $('#toggle-category-list-icon');
        $icon.toggleClass('glyphicon-chevron-down glyphicon-chevron-right');
    });
    // Thu/phóng danh sách kho
    $('#toggle-kho-list').on('click', function() {
		$('#kho-list-content').slideToggle(200);
		var $icon = $('#toggle-kho-list-icon');
		$icon.toggleClass('glyphicon-chevron-down glyphicon-chevron-right');
	});
});
</script>
<script>
$(function() {
    // Xử lý thêm kho bằng AJAX
    $('#form-them-kho').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        $.ajax({
            url: '', // gửi về chính trang này
            type: 'POST',
            data: form.serialize(),
            success: function(res) {
                $('#them-kho-msg').html('<span style="color:green;">Thêm kho thành công!</span>');
                // Lấy lại tbody danh sách kho mới nhất qua AJAX và cập nhật bảng mà không reload
                $.get(window.location.href, { ajax: 1, reload_kho: 1 }, function(html) {
                    var newTbody = $(html).find('#tbody-kho').html();
                    $('#tbody-kho').html(newTbody);
                });
                form[0].reset();
            },
            error: function() {
                $('#them-kho-msg').html('<span style="color:red;">Có lỗi xảy ra!</span>');
            }
        });
    });
});
</script>
<?php
// AJAX: chỉ render lại tbody kho nếu có tham số reload_kho
if (isset($_GET['ajax']) && isset($_GET['reload_kho'])) {
    ?>
    <tbody id="tbody-kho">
        <?php
        $ds_kho = get_all_kho();
        foreach ($ds_kho as $kho): ?>
            <tr>
                <td>
                    <form method="post" class="delete-kho-form" action="">
                        <input type="hidden" name="action" value="del_kho">
                        <input type="hidden" name="id_kho" value="<?php echo $kho['IdKho']; ?>">
                        <button type="submit" class="btn btn-link" style="padding:0;"><i class="fa fa-window-close"></i></button>
                    </form>
                </td>
                <td><?php echo htmlspecialchars($kho['TenKho']); ?></td>
                <td><?php echo htmlspecialchars($kho['DiaChi']); ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($ds_kho)): ?>
            <tr><td colspan="3" style="text-align:center;">Không có dữ liệu kho</td></tr>
        <?php endif; ?>
    </tbody>
    <script>
    // Gắn lại sự kiện xóa kho cho các form mới
			
	$('.delete-kho-form').on('submit', function(e) {
		e.preventDefault();
		var $form = $(this);
		var row = $form.closest('tr');
		$.post('', $form.serialize(), function(res) {
			row.fadeOut(300, function() { $(this).remove(); });
		});
	});
	</script>
	<?php
	exit; // Dừng script để không render thêm gì khác
}
?>
<!-- ======= PHẦN: Script kiểm tra dữ liệu đầu vào ======= -->
<script>
// Kiểm tra form thêm nhóm sản phẩm
function validateNhomSPForm() {
	var ten = document.getElementById('ten_nhomsp').value.trim();
	var hinh = document.getElementById('hinh_nhomsp').value;
	if (!ten || !hinh) {
		alert('Vui lòng nhập tên và chọn hình ảnh nhóm sản phẩm!');
		return false;
	}
	return true;
}
// Kiểm tra form thêm kho
function validateKhoForm() {
	var ten = document.getElementById('ten_kho').value.trim();
	var diachi = document.getElementById('diachi').value.trim();
	if (!ten || !diachi) {
		alert('Vui lòng nhập đầy đủ tên kho và địa chỉ!');
		return false;
	}
	return true;
}
// Kiểm tra form thêm sản phẩm
function validateSanPhamForm() {
	var ten = document.getElementById('ten_sp').value.trim();
	var nhom = document.getElementById('category_id').value;
	var kho = document.getElementById('id_kho').value;
	var hinh = document.getElementById('hinh').value;
	var hansd = document.getElementById('hansd').value;
	var hethan = document.getElementById('hethan').value;
	var soluong = document.getElementById('soluong').value;
	// Cho phép chitiet để trống
	// var chitiet = CKEDITOR.instances.chitiet.getData().trim();
	if (!ten || nhom == "0" || kho == "0" || !hinh || !hansd || !hethan || !soluong) {
		alert('Vui lòng nhập đầy đủ thông tin sản phẩm!');
		return false;
	}
	return true;
}
</script>