<?php include 'view/header_admin.php'; ?>
<!-- ======= PHẦN: Import và cấu hình meta ======= -->
<meta charset="UTF-8">
<style>
/* ======= PHẦN: Style giao diện quản lý phiếu xuất nhập kho ======= */
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
    <!-- ======= PHẦN: Hiển thị tiêu đề và thông báo lỗi/thành công ======= -->
    <!-- Hiển thị tiêu đề quản lý phiếu xuất nhập kho -->
    <div class="tittle" style="margin-bottom:16px;">
        <h3 style="font-weight:600;">Quản lý Phiếu Xuất Nhập Kho</h3>
    </div>
    <!-- Hiển thị thông báo lỗi hoặc thành công khi thao tác phiếu -->
    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>
    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <!-- ======= PHẦN: Form Thêm/Sửa Phiếu Xuất Nhập Kho ======= -->
    <div class="row" style="margin-bottom:16px;">
        <div class="col-lg-8 col-md-10 col-sm-12" style="float:none; margin:0 auto; clear:both;">
            <div class="panel panel-default" style="border:1px solid #e3eaf1;">
                <div class="panel-heading">
                    <!-- Tiêu đề form: Thêm hoặc Sửa phiếu -->
                    <h4 style="font-weight:600;margin:0;">
                        <?php echo isset($edit_phieu) && $edit_phieu ? 'Sửa Phiếu Xuất/Nhập Kho' : 'Thêm Phiếu Xuất/Nhập Kho'; ?>
                    </h4>
                </div>
                <div class="panel-body">
                    <!-- Form nhập thông tin phiếu -->
                    <form class="form-group" method="post" autocomplete="off" style="margin-bottom:0;">
                        <input type="hidden" name="action" value="<?php echo isset($edit_phieu) && $edit_phieu ? 'edit_phieu' : 'add_phieu'; ?>">
                        <?php if (isset($edit_phieu) && $edit_phieu): ?>
                            <input type="hidden" name="id" value="<?php echo $edit_phieu['id']; ?>">
                        <?php endif; ?>
                        <div class="row">
                            <!-- ======= Cột trái: Sản phẩm & số lượng ======= -->
                            <div class="col-sm-6">
                                <!-- Chọn loại phiếu (nhập/xuất) -->
                                <div class="form-group">
                                    <label for="loai_phieu">Loại phiếu</label>
                                    <select class="form-control" name="loai_phieu" id="loai_phieu" required>
                                        <option value="">--Chọn loại phiếu--</option>
                                        <option value="nhap" <?php if(isset($edit_phieu) && $edit_phieu['loai_phieu']=='nhap') echo 'selected'; ?>>Phiếu Nhập Kho</option>
                                        <option value="xuat" <?php if(isset($edit_phieu) && $edit_phieu['loai_phieu']=='xuat') echo 'selected'; ?>>Phiếu Xuất Kho</option>
                                    </select>
                                </div>
                                <!-- Chọn sản phẩm và số lượng -->
                                <div class="form-group" id="sanpham-row-group">
                                    <label>Sản phẩm & Số lượng</label>
                                    <div id="ds_sanpham">
                                        <div class="row sanpham-row" style="margin-bottom:8px;">
                                            <div class="col-xs-7" style="padding-right:2px;">
                                                <select name="id_sp[]" class="form-control sp-select" required>
                                                    <option value="">--Chọn sản phẩm--</option>
                                                    <?php foreach ($ds_sp as $sp): ?>
                                                        <option value="<?php echo $sp['IdSP']; ?>" data-kho="<?php echo $sp['id_kho']; ?>" data-sl="<?php echo $sp['SoLuong']; ?>">
                                                            <?php echo htmlspecialchars($sp['TenSP']); ?> (Tồn: <?php echo $sp['SoLuong']; ?>)
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-xs-5" style="padding-left:2px;">
                                                <input type="number" name="so_luong[]" class="form-control" min="1" required placeholder="Số lượng">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" id="add-sp-row" class="btn btn-default btn-xs" style="margin-top:5px;">Thêm sản phẩm</button>
                                </div>
                            </div>
                            <!-- ======= Cột phải: Thông tin phiếu ======= -->
                            <div class="col-sm-6">
                                <!-- Chọn kho -->
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
                                <!-- Nhập mã phiếu -->
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
                                <!-- Chọn ngày -->
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
                                <!-- Chọn nhân viên thực hiện phiếu -->
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
                            </div>
                        </div>
                        <!-- Nút lưu/thêm phiếu -->
                        <div class="form-group" style="text-align:right;">
                            <button type="submit" class="btn btn-primary" style="border-radius:8px;"><?php echo isset($edit_phieu) && $edit_phieu ? 'Lưu thay đổi' : 'Thêm phiếu'; ?></button>
                            <?php if (isset($edit_phieu) && $edit_phieu): ?>
                                <a href="?action=ql_phieuXNKho" class="btn btn-default" style="border-radius:8px;">Hủy</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ======= PHẦN: Danh sách phiếu nhập kho ======= -->
    <div class="row" style="margin-top:24px;">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <!-- ======= PHẦN: Bảng danh sách Phiếu Nhập Kho ======= -->
            <div class="panel panel-default" style="margin-bottom:16px;">
                <div class="panel-heading">
                    <h4 style="font-weight:600;margin:0;">Danh sách Phiếu Nhập Kho</h4>
                </div>
                <div class="panel-body">
                    <!-- Bảng danh sách phiếu nhập kho -->
                    <table class="table table-striped table-bordered" style="width:100%;border-radius:12px;overflow:hidden;background:#fafbfc;">
                        <thead>
                            <tr style="background:#e3eaf1;">
                                <th>Mã phiếu</th>
                                <th>Ngày</th>
                                <th>Nhân viên</th>
                                <th>Sản phẩm</th>
                                <th>Kho</th>
                                <th>Tổng</th>
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
                                    // Lấy danh sách sản phẩm & số lượng của phiếu nhập
                                    if (isset($phieu['id'])) {
                                        $chitiet = get_chitiet_phieu($phieu['id']);
                                        $sp_sl = [];
                                        foreach ($chitiet as $ct) {
                                            $ten = !empty($ct['TenSP']) ? htmlspecialchars($ct['TenSP']) : '-';
                                            $sl = (int)$ct['so_luong'];
                                            $sp_sl[] = "{$ten} (SL: {$sl})";
                                        }
                                        echo !empty($sp_sl) ? implode(', ', $sp_sl) : '-';
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    // Hiển thị tên kho
                                    foreach ($ds_kho as $kho) {
                                        if ($kho['IdKho'] == $phieu['id_kho']) {
                                            echo htmlspecialchars($kho['TenKho']);
                                            break;
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    // Lấy tổng số lượng sản phẩm của phiếu này từ bảng phieu_chitiet
                                    $so_luong = 0;
                                    if (isset($phieu['id'])) {
                                        $chitiet = get_chitiet_phieu($phieu['id']);
                                        foreach ($chitiet as $ct) {
                                            $so_luong += (int)$ct['so_luong'];
                                        }
                                    }
                                    echo $so_luong;
                                    ?>
                                </td>
                                <td><span class="badge" style="background:#388e3c;">Nhập</span></td>
                                <td>
                                    <!-- Nút sửa và xóa phiếu nhập -->
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
                            <tr><td colspan="8" style="text-align:center;">Không có phiếu nhập kho</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ======= Danh sách Phiếu Xuất Kho ======= -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 style="font-weight:600;margin:0;">Danh sách Phiếu Xuất Kho</h4>
                </div>
                <div class="panel-body">
                    <!-- Bảng danh sách phiếu xuất kho -->
                    <table class="table table-striped table-bordered" style="width:100%;border-radius:12px;overflow:hidden;background:#fafbfc;">
                        <thead>
                            <tr style="background:#e3eaf1;">
                                <th>Mã phiếu</th>
                                <th>Ngày</th>
                                <th>Nhân viên</th>
                                <th>Sản phẩm</th>
                                <th>Kho</th>
                                <th>Tổng</th>
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
                                    // Lấy danh sách sản phẩm & số lượng của phiếu xuất (giống phiếu nhập)
                                    if (isset($phieu['id'])) {
                                        $chitiet = get_chitiet_phieu($phieu['id']);
                                        $sp_sl = [];
                                        foreach ($chitiet as $ct) {
                                            $ten = !empty($ct['TenSP']) ? htmlspecialchars($ct['TenSP']) : '-';
                                            $sl = (int)$ct['so_luong'];
                                            $sp_sl[] = "{$ten} (SL: {$sl})";
                                        }
                                        echo !empty($sp_sl) ? implode(', ', $sp_sl) : '-';
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    // Hiển thị tên kho
                                    foreach ($ds_kho as $kho) {
                                        if ($kho['IdKho'] == $phieu['id_kho']) {
                                            echo htmlspecialchars($kho['TenKho']);
                                            break;
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    // Lấy tổng số lượng sản phẩm của phiếu xuất
                                    $so_luong = 0;
                                    if (isset($phieu['id'])) {
                                        $chitiet = get_chitiet_phieu($phieu['id']);
                                        foreach ($chitiet as $ct) {
                                            $so_luong += (int)$ct['so_luong'];
                                        }
                                    }
                                    echo $so_luong;
                                    ?>
                                </td>
                                <td><span class="badge" style="background:#d32f2f;">Xuất</span></td>
                                <td>
                                    <!-- Nút sửa và xóa phiếu xuất -->
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
                            <tr><td colspan="8" style="text-align:center;">Không có phiếu xuất kho</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ======= PHẦN: Script xử lý JS cho thêm/xóa sản phẩm và xóa phiếu ======= -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
<?php
// Chuẩn bị dữ liệu sản phẩm theo kho cho JS
$sp_theo_kho = [];
foreach ($ds_kho as $kho) {
    $sp_theo_kho[$kho['IdKho']] = [];
    foreach ($ds_sp as $sp) {
        if ($sp['id_kho'] == $kho['IdKho'] && $sp['SoLuong'] > 0) {
            $sp_theo_kho[$kho['IdKho']][] = [
                'IdSP' => $sp['IdSP'],
                'TenSP' => $sp['TenSP'],
                'SoLuong' => $sp['SoLuong']
            ];
        }
    }
}
?>
var spTheoKho = <?php echo json_encode($sp_theo_kho); ?>;
$(function() {
    // Xác nhận xóa phiếu
    $('.delete-phieu-form').on('submit', function(e) {
        e.preventDefault();
        if (!confirm('Bạn chắc chắn muốn xóa phiếu này?')) return false;
        var $form = $(this);
        var row = $form.closest('tr');
        $.post('', $form.serialize(), function() {
            row.fadeOut(300, function() { $(this).remove(); });
        });
    });
    // Thêm dòng sản phẩm mới trong form
    $('#add-sp-row').on('click', function() {
        var khoId = $('#id_kho').val();
        var ds = spTheoKho[khoId] || [];
        if (ds.length === 0) return;
        var row = $('<div class="row sanpham-row" style="margin-bottom:8px;"></div>');
        var colSp = $('<div class="col-xs-7" style="padding-right:2px;"></div>');
        var colSl = $('<div class="col-xs-5" style="padding-left:2px;"></div>');
        var select = $('<select name="id_sp[]" class="form-control sp-select" required></select>');
        select.append('<option value="">--Chọn sản phẩm--</option>');
        ds.forEach(function(sp) {
            select.append('<option value="'+sp.IdSP+'" data-sl="'+sp.SoLuong+'">'+sp.TenSP+' (Tồn: '+sp.SoLuong+')</option>');
        });
        colSp.append(select);
        colSl.append('<input type="number" name="so_luong[]" class="form-control" min="1" required placeholder="Số lượng">');
        row.append(colSp).append(colSl);
        $('#ds_sanpham').append(row);
    });

    // Lọc sản phẩm theo kho khi chọn kho
    function renderSanPhamRows(khoId) {
        var ds = spTheoKho[khoId] || [];
        var $ds_sanpham = $('#ds_sanpham');
        $ds_sanpham.empty();
        if (ds.length === 0) {
            $('#sanpham-row-group').hide();
            return;
        }
        $('#sanpham-row-group').show();
        // Luôn có ít nhất 1 dòng
        var row = $('<div class="row sanpham-row" style="margin-bottom:8px;"></div>');
        var colSp = $('<div class="col-xs-7" style="padding-right:2px;"></div>');
        var colSl = $('<div class="col-xs-5" style="padding-left:2px;"></div>');
        var select = $('<select name="id_sp[]" class="form-control sp-select" required></select>');
        select.append('<option value="">--Chọn sản phẩm--</option>');
        ds.forEach(function(sp) {
            select.append('<option value="'+sp.IdSP+'" data-sl="'+sp.SoLuong+'">'+sp.TenSP+' (Tồn: '+sp.SoLuong+')</option>');
        });
        colSp.append(select);
        colSl.append('<input type="number" name="so_luong[]" class="form-control" min="1" required placeholder="Số lượng">');
        row.append(colSp).append(colSl);
        $ds_sanpham.append(row);
    }

    $('#id_kho').on('change', function() {
        var khoId = $(this).val();
        if (khoId && spTheoKho[khoId] && spTheoKho[khoId].length > 0) {
            renderSanPhamRows(khoId);
        } else {
            $('#ds_sanpham').empty();
            $('#sanpham-row-group').hide();
        }
    });

    // Khi load trang, nếu đã chọn kho thì render lại sản phẩm
    var khoIdInit = $('#id_kho').val();
    if (khoIdInit && spTheoKho[khoIdInit] && spTheoKho[khoIdInit].length > 0) {
        renderSanPhamRows(khoIdInit);
    } else {
        $('#sanpham-row-group').hide();
    }
});
</script>


