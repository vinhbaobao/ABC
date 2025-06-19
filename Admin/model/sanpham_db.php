<?php
// Lấy danh sách sản phẩm theo nhóm sản phẩm
function get_sanpham_theo_nhomsp($category_id) {
    global $db;
    $query = "SELECT * FROM sanpham WHERE sanpham.IdNhomSP = '$category_id' ORDER BY IdSP";
    $products = $db->query($query);
    return $products;
}

// Lấy số lượng sản phẩm theo nhóm sản phẩm
function get_soluong_sanpham($category_id) {
    global $db;
    $query = "SELECT count(*) from sanpham WHERE sanpham.IdNhomSP = '$category_id'";
    $product = $db->query($query);
    foreach ($product as $count) {
        echo $count['count(*)'];
    }
    return $product;
}

// Lấy 12 sản phẩm mới nhất
function get_sanpham_12_new() {
    global $db;
    $query = "SELECT * FROM sanpham ORDER BY IdSP DESC LIMIT 12";
    $product = $db->query($query);
    return $product;
}

// Lấy toàn bộ sản phẩm
function get_sanpham_all() {
    global $db;
    $query = "SELECT * FROM sanpham";
    $product = $db->query($query);
    return $product ? $product->fetchAll(PDO::FETCH_ASSOC) : [];
}

// Lấy thông tin sản phẩm theo IdSP
function get_sanpham($id_sanpham) {
     global $db;
    $query = "SELECT * FROM sanpham WHERE IdSP = :id";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':id', (int)$id_sanpham, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Xóa sản phẩm theo IdSP
function delete_sanpham($id_sanpham) {
    global $db;
    try {
        // Đảm bảo $id_sanpham là số nguyên
        $id_sanpham = (int)$id_sanpham;
        $query = "DELETE FROM sanpham WHERE IdSP = :id";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':id', $id_sanpham, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        // Không dùng die trong production, chỉ để debug
        echo "Lỗi xóa sản phẩm: " . $e->getMessage();
    }
}

// Thêm sản phẩm mới vào database
function add_sanpham($category_id, $ten_sp, $id_kho, $hinh, $han_su_dung, $chitiet, $het_han, $soluong) {
    global $db;
    try {
        $han_su_dung = date('Y-m-d', strtotime($han_su_dung));
        $het_han = date('Y-m-d', strtotime($het_han));
        $soluong = preg_replace('/\D/', '', $soluong);
        if ($soluong === '') $soluong = '0';
        $query = "INSERT INTO sanpham (IdNhomSP, TenSP, id_kho, Hinh, HanSD, ChiTiet, HetHan, SoLuong) 
                  VALUES (:category_id, :ten_sp, :id_kho, :hinh, :han_su_dung, :chitiet, :het_han, :soluong)";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':category_id', (int)$category_id, PDO::PARAM_INT);
        $stmt->bindValue(':ten_sp', $ten_sp, PDO::PARAM_STR);
        $stmt->bindValue(':id_kho', (int)$id_kho, PDO::PARAM_INT);
        $stmt->bindValue(':hinh', $hinh, PDO::PARAM_STR);
        $stmt->bindValue(':han_su_dung', $han_su_dung, PDO::PARAM_STR);
        $stmt->bindValue(':chitiet', $chitiet, PDO::PARAM_STR);
        $stmt->bindValue(':het_han', $het_han, PDO::PARAM_STR);
        $stmt->bindValue(':soluong', $soluong, PDO::PARAM_STR);
        $stmt->execute();
    } catch (PDOException $e) {
        echo "Lỗi thêm sản phẩm: " . $e->getMessage();
    }
}

// Sửa thông tin sản phẩm theo IdSP
function sua_sp($category_id, $ten_sp, $id_kho, $hinh, $han_su_dung, $chitiet, $het_han, $soluong, $id_sanpham){
    global $db;
    try {
        $han_su_dung = date('Y-m-d', strtotime($han_su_dung));
        $het_han = date('Y-m-d', strtotime($het_han));
        // Chỉ nhận số, nếu rỗng thì mặc định là 0
        $soluong = preg_replace('/\D/', '', $soluong);
        if ($soluong === '') $soluong = '0';
        $query = "UPDATE sanpham SET IdNhomSP = :category_id, TenSP = :ten_sp, id_kho = :id_kho, Hinh = :hinh, HanSD = :han_su_dung, ChiTiet = :chitiet, HetHan = :het_han, SoLuong = :soluong WHERE IdSP = :id_sanpham";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':category_id', (int)$category_id, PDO::PARAM_INT);
        $stmt->bindValue(':ten_sp', $ten_sp, PDO::PARAM_STR);
        $stmt->bindValue(':id_kho', (int)$id_kho, PDO::PARAM_INT);
        $stmt->bindValue(':hinh', $hinh, PDO::PARAM_STR);
        $stmt->bindValue(':han_su_dung', $han_su_dung, PDO::PARAM_STR);
        $stmt->bindValue(':chitiet', $chitiet, PDO::PARAM_STR);
        $stmt->bindValue(':het_han', $het_han, PDO::PARAM_STR);
        $stmt->bindValue(':soluong', $soluong, PDO::PARAM_STR);
        $stmt->bindValue(':id_sanpham', (int)$id_sanpham, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        echo "Lỗi sửa sản phẩm: " . $e->getMessage();
    }
}

// Lấy danh sách hình ảnh sản phẩm từ thư mục images/sanpham
function get_hinh_sp(){
    $path= getcwd();
    $img_path = dirname($path).'/images/sanpham';
    $items= scandir($img_path);
    $files= array();
    foreach ($items as $item){
        $item_path= $img_path.DIRECTORY_SEPARATOR.$item;
        if(is_file($item_path)){
        $files[]= $item;}
        }
    return $files;
}
?>