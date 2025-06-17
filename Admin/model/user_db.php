<?php 
// Thêm admin mới vào bảng user
function them_admin($user, $email, $pass){
    global $db;
    $query = "INSERT INTO user (Username, Email, Matkhau, Loai)"
            . "VALUES ('$user', '$email', '$pass', '2')";
    $db->exec($query);
}

// Lấy toàn bộ danh sách user, sắp xếp theo Loai
function get_user() {
    global $db;
    $query = "SELECT * FROM user ORDER BY Loai";
    $username = $db->query($query);
    return $username;
}

// Lấy IdUser theo Username
function get_id_user($username) {
    global $db;
    $query = "SELECT * FROM user WHERE Username = '$username'";
    $category = $db->query($query);
    $category = $category->fetch();
    $category_name = $category['IdUser'];
    return $category_name;
}

// Xóa user theo IdUser
function delete_user($id) {
    global $db;
    $query = "DELETE FROM user WHERE IdUser = '$id'";
    $db->exec($query);
}

// Lấy danh sách user theo Loai
function get_user_theo_loai($id) {
    global $db;
    $query = "SELECT * FROM user WHERE Loai = " . $id;
    $product = $db->query($query);
    return $product;
}

// Lấy Loai user theo IdUser
function get_loai_user($id) {
    global $db;
    $query = "SELECT * FROM user WHERE Loai = $id";
    $category = $db->query($query);
    $category = $category->fetch();
    $category_name = $category['Loai'];
    return $category_name;
}

// Lấy Username theo IdUser
function get_ten_user($category_id) {
    global $db;
    $query = "SELECT * FROM user WHERE IdUser = $category_id";
    $category = $db->query($query);
    $category = $category->fetch();
    $category_name = $category['Username'];
    return $category_name;
}

// Lấy Email theo IdUser
function get_email_user($category_id) {
    global $db;
    $query = "SELECT * FROM user WHERE IdUser = $category_id";
    $category = $db->query($query);
    $category = $category->fetch();
    $category_name = $category['Email'];
    return $category_name;
}

// Lấy số lượng user theo Loai
function get_soluong_user($id) {
    global $db;
    $query = "SELECT count(*) from user WHERE user.Loai = '$id'";
    $product = $db->query($query);
    foreach ($product as $count) {
        echo $count['count(*)'];
    }
    return $product;
}
 ?>