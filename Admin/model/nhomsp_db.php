<?php

//Get categories
function get_nhomsp() {
    global $db;
    $query = 'SELECT * FROM nhomsp';
    $result = $db->query($query);
    return $result ? $result->fetchAll(PDO::FETCH_ASSOC) : [];
}

//Get categories name
function get_ten_nhomsp($category_id) {
    global $db;
    $query = "SELECT * FROM nhomsp WHERE IdNhomSP = $category_id";
    $category = $db->query($query);
    $category = $category->fetch();
    $category_name = $category['TenNhomSP'];
    return $category_name;
}
function get_hinh_nhomsp($category_id) {
    global $db;
    $query = "SELECT * FROM nhomsp WHERE IdNhomSP = $category_id";
    $category = $db->query($query);
    $category = $category->fetch();
    $category_name = $category['HinhNSP'];
    return $category_name;
}
function delete_nhomsp($category_id) {
    global $db;
    // Đảm bảo xóa an toàn bằng prepare
    $stmt = $db->prepare("DELETE FROM nhomsp WHERE IdNhomSP = :id");
    $stmt->bindValue(':id', $category_id, PDO::PARAM_INT);
    $stmt->execute();
}

function them_nhomsp($ten_nhomsp, $hinh_nhomsp) {
    global $db;
    $query = "INSERT INTO `nhomsp` (`IdNhomSP`, `TenNhomSP`, `HinhNSP`) VALUES (NULL, '$ten_nhomsp', '$hinh_nhomsp');";
    $db->exec($query);
}
function sua_nhomsp($ten_nhomsp, $hinh_nhomsp, $category_id){
    global $db;
    $query = "UPDATE `nhomsp` SET `TenNhomSP` = '$ten_nhomsp', `HinhNSP` = '$hinh_nhomsp' WHERE `nhomsp`.`IdNhomSP` = '$category_id'";
    $db->exec($query);
}
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