<?php
// Nhúng các file model cần thiết
require('Admin/model/database.php');
require('Admin/model/nhomsp_db.php');
require('Admin/model/sanpham_db.php');
require('Admin/model/user_db.php');
require('Admin/model/cart_db.php');
session_start();

// Xử lý thông tin giỏ hàng để hiển thị trên header
$ok = 1;
$total = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $k => $v) {
        if (isset($k)) {
            $ok = 2;
        }
    }
}
if ($ok != 2) {
    $cart_info = 'Giỏ hàng: 0 sản phẩm';
} else {
    $items = $_SESSION['cart'];
    $cart_info = 'Giỏ hàng: ' . count($items) . ' sản phẩm';
}

// Xác định action từ request (POST hoặc GET), mặc định là 'home'
if (isset($_POST['action'])) {
    $action = $_POST['action'];//exam: add product
} else if (isset($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = 'home';
}

// Xử lý các action chính của website
if ($action == 'home') {
    // Trang chủ: lấy danh sách nhóm sản phẩm và 12 sản phẩm mới nhất
    $categories = get_nhomsp();
    $products = get_sanpham_12_new();
    include('homepage.php');
} else if ($action == 'product_detail') {
    // Trang chi tiết sản phẩm
    $category_id = $_GET['id_nsp'];
    $id_sanpham = $_GET['id_sp'];
    $products = get_sanpham($id_sanpham);
    $categories = get_nhomsp();
    include('production_detail.php');
} else if ($action == 'productions') {
    // Trang danh sách sản phẩm theo nhóm hoặc tất cả
    $category_id = $_GET['id_nsp'];
    if ($category_id == '0'){
        $breadcrumb = 'Tất cả các sản phẩm';
        $categories = get_nhomsp();
        $products = get_sanpham_all();
        include('productions.php');
    }
    else {
        $breadcrumb = get_ten_nhomsp($category_id);
        $categories = get_nhomsp();
        $products = get_sanpham_theo_nhomsp($category_id);
        include('productions.php');
    }
}  else if ($action == 'view_cart'){
    // Trang xem giỏ hàng
    include('view_cart.php');
}  else if ($action == 'addcart'){
    // Thêm sản phẩm vào giỏ hàng
    $item = $_GET['item'];
    include('addcart.php');
}  else if ($action == 'delcart'){
    // Xóa sản phẩm khỏi giỏ hàng
    $item = $_GET['item'];
    include('delcart.php');
}  else if ($action == 'thanhtoan'){
    // Xử lý thanh toán đơn hàng
    $userid = $_POST['userid'];
    $chitiet = $_POST['chitiet'];
    $trangthai = 'Chưa thanh toán';
    $giatien = $_POST['giatien'];
    $date = $_POST['date'];
    $shipping_address =$_POST['$shipping_address'];
    $Phone = $_POST['Phone'];
    them_cart($userid, $chitiet, $giatien, $trangthai, $date,$shipping_address,$Phone);
    header("Location: test.php");
}

?>