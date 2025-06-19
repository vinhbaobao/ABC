<?php

// Lấy toàn bộ danh sách nhóm sản phẩm
function get_nhomsp() {
    global $db;
    $stmt = $db->prepare("SELECT * FROM nhomsp");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $rows ? $rows : [];
}

// Lấy tên nhóm sản phẩm theo IdNhomSP
function get_ten_nhomsp($category_id) {
    global $db;
    $stmt = $db->prepare("SELECT TenNhomSP FROM nhomsp WHERE IdNhomSP = :id");
    $stmt->bindValue(':id', $category_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['TenNhomSP'] : '';
}

// Lấy hình nhóm sản phẩm theo IdNhomSP
function get_hinh_nhomsp($category_id) {
    global $db;
    $stmt = $db->prepare("SELECT HinhNSP FROM nhomsp WHERE IdNhomSP = :id");
    $stmt->bindValue(':id', $category_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['HinhNSP'] : '';
}

// Xóa nhóm sản phẩm theo IdNhomSP
function delete_nhomsp($category_id) {
    global $db;
    $stmt = $db->prepare("DELETE FROM nhomsp WHERE IdNhomSP = :id");
    $stmt->bindValue(':id', $category_id, PDO::PARAM_INT);
    $stmt->execute();
}

// Thêm nhóm sản phẩm mới vào database
function them_nhomsp($ten_nhomsp, $hinh_nhomsp) {
    global $db;
    $stmt = $db->prepare("INSERT INTO nhomsp (TenNhomSP, HinhNSP) VALUES (:ten, :hinh)");
    $stmt->bindValue(':ten', $ten_nhomsp, PDO::PARAM_STR);
    $stmt->bindValue(':hinh', $hinh_nhomsp, PDO::PARAM_STR);
    $stmt->execute();
}

// Sửa thông tin nhóm sản phẩm theo IdNhomSP
function sua_nhomsp($ten_nhomsp, $hinh_nhomsp, $category_id){
    global $db;
    $stmt = $db->prepare("UPDATE nhomsp SET TenNhomSP = :ten, HinhNSP = :hinh WHERE IdNhomSP = :id");
    $stmt->bindValue(':ten', $ten_nhomsp, PDO::PARAM_STR);
    $stmt->bindValue(':hinh', $hinh_nhomsp, PDO::PARAM_STR);
    $stmt->bindValue(':id', $category_id, PDO::PARAM_INT);
    $stmt->execute();
}

// Lấy danh sách hình nhóm sản phẩm
function get_hinh_nsp(){
    global $db;
    $query = "SELECT HinhNSP FROM nhomsp";
    $result = $db->query($query);
    $files = array();
    foreach ($result as $row) {
        $files[] = $row['HinhNSP'];
    }
    return $files;
}

?>