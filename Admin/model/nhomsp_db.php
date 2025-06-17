<?php

// Lấy toàn bộ danh sách nhóm sản phẩm
function get_nhomsp() {
    global $db;
    $query = 'SELECT * FROM nhomsp';
    $result = $db->query($query);
    return $result ? $result->fetchAll(PDO::FETCH_ASSOC) : [];
}

// Lấy tên nhóm sản phẩm theo IdNhomSP
function get_ten_nhomsp($category_id) {
    global $db;
    $query = "SELECT * FROM nhomsp WHERE IdNhomSP = $category_id";
    $category = $db->query($query);
    $category = $category->fetch();
    $category_name = $category['TenNhomSP'];
    return $category_name;
}

// Lấy hình nhóm sản phẩm theo IdNhomSP
function get_hinh_nhomsp($category_id) {
    global $db;
    $query = "SELECT * FROM nhomsp WHERE IdNhomSP = $category_id";
    $category = $db->query($query);
    $category = $category->fetch();
    $category_name = $category['HinhNSP'];
    return $category_name;
}

// Xóa nhóm sản phẩm theo IdNhomSP
function delete_nhomsp($category_id) {
    global $db;
    // Đảm bảo xóa an toàn bằng prepare
    $stmt = $db->prepare("DELETE FROM nhomsp WHERE IdNhomSP = :id");
    $stmt->bindValue(':id', $category_id, PDO::PARAM_INT);
    $stmt->execute();
}

// Thêm nhóm sản phẩm mới vào database
function them_nhomsp($ten_nhomsp, $hinh_nhomsp) {
    global $db;
    $query = "INSERT INTO `nhomsp` (`IdNhomSP`, `TenNhomSP`, `HinhNSP`) VALUES (NULL, '$ten_nhomsp', '$hinh_nhomsp');";
    $db->exec($query);
}

// Sửa thông tin nhóm sản phẩm theo IdNhomSP
function sua_nhomsp($ten_nhomsp, $hinh_nhomsp, $category_id){
    global $db;
    $query = "UPDATE `nhomsp` SET `TenNhomSP` = '$ten_nhomsp', `HinhNSP` = '$hinh_nhomsp' WHERE `nhomsp`.`IdNhomSP` = '$category_id'";
    $db->exec($query);
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