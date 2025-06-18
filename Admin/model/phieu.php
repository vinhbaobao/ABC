<?php
// ======= PHẦN: Các hàm xử lý dữ liệu phiếu xuất nhập kho =======

// Lấy tất cả phiếu xuất nhập kho
function get_all_phieu($loai = null) {
    global $db;
    $sql = "SELECT p.*, 
                   (SELECT SUM(so_luong) FROM phieu_chitiet WHERE id_phieu = p.id) as so_luong
            FROM phieu p";
    if ($loai) {
        $sql .= " WHERE loai_phieu = ?";
        $stmt = $db->prepare($sql . " ORDER BY ngay DESC");
        $stmt->execute([$loai]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $stmt = $db->query($sql . " ORDER BY ngay DESC");
        return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    }
}

// Lấy chi tiết sản phẩm của một phiếu
function get_chitiet_phieu($id_phieu) {
    global $db;
    $stmt = $db->prepare("SELECT ct.*, sp.TenSP FROM phieu_chitiet ct LEFT JOIN sanpham sp ON ct.id_sp = sp.IdSP WHERE ct.id_phieu = ?");
    $stmt->execute([$id_phieu]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Thêm phiếu mới và cập nhật số lượng sản phẩm
function add_phieu($ma_phieu, $ngay, $nhan_vien, $loai_phieu, $id_kho, $ds_sp, $ds_soluong) {
    global $db;
    $db->beginTransaction();
    try {
        // Thêm phiếu (KHÔNG có id_nhomsp trong SQL)
        $stmt = $db->prepare("INSERT INTO phieu (ma_phieu, ngay, nhan_vien, loai_phieu, id_kho) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$ma_phieu, $ngay, $nhan_vien, $loai_phieu, $id_kho]);
        $id_phieu = $db->lastInsertId();

        // Thêm chi tiết phiếu và cập nhật số lượng sản phẩm
        foreach ($ds_sp as $i => $id_sp) {
            $so_luong = (int)$ds_soluong[$i];
            // Kiểm tra số lượng khi xuất
            if ($loai_phieu == 'xuat') {
                $stmt2 = $db->prepare("SELECT SoLuong FROM sanpham WHERE IdSP = ?");
                $stmt2->execute([$id_sp]);
                $row = $stmt2->fetch(PDO::FETCH_ASSOC);
                $so_luong_hien_tai = $row ? (int)$row['SoLuong'] : 0;
                if ($so_luong > $so_luong_hien_tai) throw new Exception('Số lượng xuất vượt quá tồn kho!');
            }
            // Lưu chi tiết phiếu
            $stmt3 = $db->prepare("INSERT INTO phieu_chitiet (id_phieu, id_sp, so_luong) VALUES (?, ?, ?)");
            $stmt3->execute([$id_phieu, $id_sp, $so_luong]);
            // Cập nhật số lượng sản phẩm
            if ($loai_phieu == 'nhap') {
                $db->prepare("UPDATE sanpham SET SoLuong = SoLuong + ? WHERE IdSP = ?")->execute([$so_luong, $id_sp]);
            } else if ($loai_phieu == 'xuat') {
                $db->prepare("UPDATE sanpham SET SoLuong = SoLuong - ? WHERE IdSP = ?")->execute([$so_luong, $id_sp]);
            }
        }
        $db->commit();
        return true;
    } catch (Exception $e) {
        $db->rollBack();
        throw $e;
    }
}

// Sửa phiếu (cập nhật lại chi tiết và số lượng sản phẩm)
function sua_phieu($id, $ma_phieu, $ngay, $nhan_vien, $loai_phieu, $id_kho, $ds_sp, $ds_soluong) {
    global $db;
    $db->beginTransaction();
    try {
        // Lấy chi tiết phiếu cũ để hoàn tác số lượng
        $old_ct = get_chitiet_phieu($id);
        foreach ($old_ct as $ct) {
            if ($loai_phieu == 'nhap') {
                $db->prepare("UPDATE sanpham SET SoLuong = SoLuong - ? WHERE IdSP = ?")->execute([$ct['so_luong'], $ct['id_sp']]);
            } else if ($loai_phieu == 'xuat') {
                $db->prepare("UPDATE sanpham SET SoLuong = SoLuong + ? WHERE IdSP = ?")->execute([$ct['so_luong'], $ct['id_sp']]);
            }
        }
        // Xóa chi tiết cũ
        $db->prepare("DELETE FROM phieu_chitiet WHERE id_phieu = ?")->execute([$id]);
        // Cập nhật phiếu (KHÔNG có id_nhomsp trong SQL)
        $stmt = $db->prepare("UPDATE phieu SET ma_phieu=?, ngay=?, nhan_vien=?, loai_phieu=?, id_kho=? WHERE id=?");
        $stmt->execute([$ma_phieu, $ngay, $nhan_vien, $loai_phieu, $id_kho, $id]);
        // Thêm lại chi tiết mới và cập nhật số lượng
        foreach ($ds_sp as $i => $id_sp) {
            $so_luong = (int)$ds_soluong[$i];
            if ($loai_phieu == 'xuat') {
                $stmt2 = $db->prepare("SELECT SoLuong FROM sanpham WHERE IdSP = ?");
                $stmt2->execute([$id_sp]);
                $row = $stmt2->fetch(PDO::FETCH_ASSOC);
                $so_luong_hien_tai = $row ? (int)$row['SoLuong'] : 0;
                if ($so_luong > $so_luong_hien_tai) throw new Exception('Số lượng xuất vượt quá tồn kho!');
            }
            $stmt3 = $db->prepare("INSERT INTO phieu_chitiet (id_phieu, id_sp, so_luong) VALUES (?, ?, ?)");
            $stmt3->execute([$id, $id_sp, $so_luong]);
            if ($loai_phieu == 'nhap') {
                $db->prepare("UPDATE sanpham SET SoLuong = SoLuong + ? WHERE IdSP = ?")->execute([$so_luong, $id_sp]);
            } else if ($loai_phieu == 'xuat') {
                $db->prepare("UPDATE sanpham SET SoLuong = SoLuong - ? WHERE IdSP = ?")->execute([$so_luong, $id_sp]);
            }
        }
        $db->commit();
        return true;
    } catch (Exception $e) {
        $db->rollBack();
        throw $e;
    }
}

// Lấy thông tin phiếu theo ID
function getPhieuById($id) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM phieu WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Xóa phiếu và chi tiết phiếu (KHÔNG hoàn tác số lượng)
function del_phieu($id) {
    global $db;
    $db->beginTransaction();
    try {
        $db->prepare("DELETE FROM phieu_chitiet WHERE id_phieu=?")->execute([$id]);
        $db->prepare("DELETE FROM phieu WHERE id=?")->execute([$id]);
        $db->commit();
        return true;
    } catch (Exception $e) {
        $db->rollBack();
        throw $e;
    }
}
?>

