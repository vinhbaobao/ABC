<?php
// Lấy danh sách kho
function get_all_kho() {
    global $db;
    $query = "SELECT * FROM kho";
    $result = $db->query($query);
    return $result ? $result->fetchAll(PDO::FETCH_ASSOC) : [];
}

// Lấy tổng số sản phẩm tồn trong kho
function get_tong_sanpham_trong_kho($kho_id) {
    global $db;
    $stmt = $db->prepare("SELECT SUM(SoLuong) AS tong FROM sanpham WHERE id_kho = :idkho");
    $stmt->bindValue(':idkho', $kho_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch();
    return $row && $row['tong'] ? $row['tong'] : 0;
}

// Đếm số nhóm sản phẩm trong kho
function get_soluong_nhomsp_trong_kho($kho_id) {
    global $db;
    $stmt = $db->prepare("SELECT COUNT(DISTINCT IdNhomSP) AS so_nhom FROM sanpham WHERE id_kho = :idkho");
    $stmt->bindValue(':idkho', $kho_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch();
    return $row && $row['so_nhom'] ? $row['so_nhom'] : 0;
}

// Thống kê nhập, xuất, tồn sản phẩm trong kho
function thong_ke_nhap_xuat_ton($kho_id) {
    global $db;
    // Kiểm tra nếu bảng phieunhap hoặc phieuxuat không tồn tại thì trả về 0
    try {
        // Tổng nhập
        $stmt = $db->prepare("SELECT COALESCE(SUM(SoLuong),0) AS tong_nhap FROM phieunhap WHERE id_kho = :idkho");
        $stmt->bindValue(':idkho', $kho_id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        $tong_nhap = $row ? $row['tong_nhap'] : 0;
    } catch (PDOException $e) {
        $tong_nhap = 0;
    }

    try {
        // Tổng xuất
        $stmt = $db->prepare("SELECT COALESCE(SUM(SoLuong),0) AS tong_xuat FROM phieuxuat WHERE id_kho = :idkho");
        $stmt->bindValue(':idkho', $kho_id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        $tong_xuat = $row ? $row['tong_xuat'] : 0;
    } catch (PDOException $e) {
        $tong_xuat = 0;
    }

    // Tổng tồn (có thể lấy từ bảng sanpham)
    $tong_ton = get_tong_sanpham_trong_kho($kho_id);

    return [
        'tong_nhap' => $tong_nhap,
        'tong_xuat' => $tong_xuat,
        'tong_ton'  => $tong_ton
    ];
}

// Thêm kho mới
function them_kho($ten_kho, $diachi) {
    global $db;
    $query = "INSERT INTO kho (TenKho, DiaChi) VALUES (:ten_kho, :diachi)";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':ten_kho', $ten_kho, PDO::PARAM_STR);
    $stmt->bindValue(':diachi', $diachi, PDO::PARAM_STR);
    $stmt->execute();
}

// Thêm hàm xóa kho
function delete_kho($id_kho) {
    global $db;
    $stmt = $db->prepare("DELETE FROM kho WHERE IdKho = :id_kho");
    $stmt->bindValue(':id_kho', $id_kho, PDO::PARAM_INT);
    $stmt->execute();
}
?>
