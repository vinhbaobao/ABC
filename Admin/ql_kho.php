<?php include 'view/header_admin.php'; ?>

<meta charset="UTF-8">
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
    <div class="tittle" style="margin-bottom:16px;">
        <h3 style="font-weight:600;">Quản lý kho</h3>
    </div>
    <div class="panel panel-default" style="margin-bottom:16px;">
        <div class="panel-heading">
            <h4 style="font-weight:600;margin:0;">Danh sách kho và thống kê</h4>
        </div>
        <div class="panel-body">
            <table class="table table-striped table-bordered" style="background:#fff;">
                <thead>
                    <tr style="background:#e3eaf1;">
                        <th>ID Kho</th>
                        <th>Tên kho</th>
                        <th>Địa chỉ</th>
                        <th>Tổng sản phẩm tồn</th>
                        <th>Số nhóm sản phẩm</th>
                        <th>Tổng nhập</th>
                        <th>Tổng xuất</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $kho_id_selected = isset($_GET['kho_id']) ? (int)$_GET['kho_id'] : 0;
                    foreach ($ds_kho as $kho):
                        $tong_sp = get_tong_sanpham_trong_kho($kho['IdKho']);
                        $so_nhomsp = get_soluong_nhomsp_trong_kho($kho['IdKho']);
                        $thongke = thong_ke_nhap_xuat_ton($kho['IdKho']);
                    ?>
                    <tr<?php if ($kho_id_selected == $kho['IdKho']) echo ' style="background:#e0f7fa"'; ?>>
                        <td><?php echo htmlspecialchars($kho['IdKho']); ?></td>
                        <td>
                            <a href="?action=ql_kho&kho_id=<?php echo $kho['IdKho']; ?>">
                                <?php echo htmlspecialchars($kho['TenKho']); ?>
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars($kho['DiaChi']); ?></td>
                        <td><?php echo (int)$tong_sp; ?></td>
                        <td><?php echo (int)$so_nhomsp; ?></td>
                        <td><?php echo (int)$thongke['tong_nhap']; ?></td>
                        <td><?php echo (int)$thongke['tong_xuat']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($ds_kho)): ?>
                        <tr><td colspan="7" style="text-align:center;">Không có dữ liệu kho</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Hiển thị danh sách sản phẩm trong kho được chọn -->
    <?php if ($kho_id_selected): ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 style="font-weight:600;margin:0;">Danh sách sản phẩm trong kho: 
                <?php 
                    foreach ($ds_kho as $kho) {
                        if ($kho['IdKho'] == $kho_id_selected) {
                            echo htmlspecialchars($kho['TenKho']);
                            break;
                        }
                    }
                ?>
            </h4>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped" style="background:#fff;">
                <thead>
                    <tr style="background:#e3eaf1;">
                        <th>Tên sản phẩm</th>
                        <th>Hình ảnh</th>
                        <th>Hạn sử dụng</th>
                        <th>Hết hạn</th>
                        <th>Số lượng</th>
                        <th>Nhóm sản phẩm</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    global $db;
                    $stmt = $db->prepare("SELECT sp.*, nsp.TenNhomSP FROM sanpham sp 
                                          LEFT JOIN nhomsp nsp ON sp.IdNhomSP = nsp.IdNhomSP 
                                          WHERE sp.id_kho = :idkho");
                    $stmt->bindValue(':idkho', $kho_id_selected, PDO::PARAM_INT);
                    $stmt->execute();
                    $products = $stmt->fetchAll();
                    foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['TenSP']); ?></td>
                            <td><img style="width:80px;" src="../images<?php echo $product['Hinh']; ?>"></td>
                            <td><?php echo date('d/m/Y', strtotime($product['HanSD'])); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($product['HetHan'])); ?></td>
                            <td><?php echo (int)$product['SoLuong']; ?></td>
                            <td><?php echo htmlspecialchars($product['TenNhomSP']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($products)): ?>
                        <tr><td colspan="6" style="text-align:center;">Không có sản phẩm trong kho này</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>
</body>
</html>
</body>
</html>
</body>
</html>
</body>
</html>
