<?php
require('model/database.php');
require('model/nhomsp_db.php');
require('model/sanpham_db.php');
require('model/user_db.php');
require('model/cart_db.php');
require('model/kho_dp.php');

// ===== XỬ LÝ PHIẾU XUẤT NHẬP KHO (POST) =====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && in_array($_POST['action'], ['add_phieu', 'edit_phieu', 'del_phieu'])) {
    require_once('model/phieu.php');
    $error_message = '';
    try {
        if ($_POST['action'] === 'add_phieu') {
            $ma_phieu = trim($_POST['ma_phieu']);
            $ngay = $_POST['ngay'];
            $nhan_vien = trim($_POST['nhan_vien']);
            $loai_phieu = $_POST['loai_phieu'];
            $id_nhomsp = intval($_POST['id_nhomsp']);
            $id_kho = intval($_POST['id_kho']);
            $ds_sp = isset($_POST['id_sp']) ? $_POST['id_sp'] : [];
            $ds_soluong = isset($_POST['so_luong']) ? $_POST['so_luong'] : [];
            // Kiểm tra dữ liệu
            if ($ma_phieu && in_array($loai_phieu, ['nhap', 'xuat']) && $id_nhomsp && $id_kho && $ngay && $nhan_vien && is_array($ds_sp) && is_array($ds_soluong) && count($ds_sp) > 0) {
                require_once('model/phieu.php');
                add_phieu($ma_phieu, $ngay, $nhan_vien, $loai_phieu, $id_nhomsp, $id_kho, $ds_sp, $ds_soluong);
                header("Location: index.php?action=ql_phieuXNKho");
                exit;
            } else {
                $error_message = "Vui lòng nhập đầy đủ thông tin hợp lệ!";
            }
        }
        if ($_POST['action'] === 'edit_phieu' && isset($_POST['id'])) {
            $id = intval($_POST['id']);
            $ma_phieu = trim($_POST['ma_phieu']);
            $ngay = $_POST['ngay'];
            $nhan_vien = trim($_POST['nhan_vien']);
            $loai_phieu = $_POST['loai_phieu'];
            $id_nhomsp = intval($_POST['id_nhomsp']);
            $id_kho = intval($_POST['id_kho']);
            $ds_sp = isset($_POST['id_sp']) ? $_POST['id_sp'] : [];
            $ds_soluong = isset($_POST['so_luong']) ? $_POST['so_luong'] : [];
            if ($ma_phieu && $ngay && $nhan_vien && in_array($loai_phieu, ['nhap', 'xuat']) && $id_nhomsp && $id_kho && is_array($ds_sp) && is_array($ds_soluong) && count($ds_sp) > 0) {
                require_once('model/phieu.php');
                sua_phieu($id, $ma_phieu, $ngay, $nhan_vien, $loai_phieu, $id_nhomsp, $id_kho, $ds_sp, $ds_soluong);
                header("Location: index.php?action=ql_phieuXNKho");
                exit;
            } else {
                $error_message = "Vui lòng nhập đầy đủ thông tin hợp lệ khi sửa!";
            }
        }
        if ($_POST['action'] === 'del_phieu' && isset($_POST['id'])) {
            $id = intval($_POST['id']);
            require_once('model/phieu.php');
            del_phieu($id);
            header("Location: index.php?action=ql_phieuXNKho");
            exit;
        }
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}

// ===== XÁC ĐỊNH ACTION =====
if (isset($_POST['action'])) {
    $action = $_POST['action'];
} else if (isset($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = 'home';
}

// ===== TRANG CHỦ ADMIN =====
if ($action == 'home') {
    $categories = get_nhomsp();
    $categories1 = get_nhomsp();
    $users = get_user();
    include('home.php');
}

// ===== QUẢN LÝ SẢN PHẨM =====
if ($action == 'ql_sp') {
    $category_id = $_GET['category_id'];
    if ($category_id == '0') {
        $categories = get_nhomsp();
        $categories2 = get_nhomsp();
        $products = get_sanpham_all();
        $category_name = 'Tất cả';
        include('ql_sp.php');
    } else {
        $hangsx = $_GET['hangsx'];
        if ($hangsx == '0'){
            $category_name = get_ten_nhomsp($category_id);
            $categories = get_nhomsp();
            $categories2 = get_nhomsp();
            $products = get_sanpham_theo_nhomsp($category_id);
            include('ql_sp.php');
        } else {
            $category_name = $hangsx;
            $categories = get_nhomsp();
            $categories2 = get_nhomsp();
            $products = get_sanpham_theo_hangsx($hangsx);
            include('ql_sp.php');
        }
    }
// ===== XÓA NHÓM SẢN PHẨM =====
} else if ($action == 'del_nhomsp') {
    $category_id = $_POST['category_id'];
    delete_nhomsp($category_id);
    header("Location: .?action=ql_sp&category_id=0");
// ===== XÓA SẢN PHẨM =====
} else if ($action == 'del_sp') {
    $id_sanpham = $_POST['id_sanpham'];
    delete_sanpham($id_sanpham);
    header("Location: .?action=ql_sp&category_id=0");
// ===== THÊM NHÓM SẢN PHẨM =====
} else if ($action == 'them_nhomsp') {
    $ten_nhomsp = $_POST['ten_nhomsp'];
    $hinh_nhomsp = '';
    // Xử lý upload hình ảnh nhóm sản phẩm
    if (isset($_FILES['hinh_nhomsp']) && $_FILES['hinh_nhomsp']['error'] == 0) {
        $target_dir = "../images/nhomsp/";
        $file_name = basename($_FILES["hinh_nhomsp"]["name"]);
        $target_file = $target_dir . $file_name;
        if (move_uploaded_file($_FILES["hinh_nhomsp"]["tmp_name"], $target_file)) {
            $hinh_nhomsp = $file_name;
        }
    } else if (isset($_POST['hinh_nhomsp'])) {
        $hinh_nhomsp = $_POST['hinh_nhomsp'];
    }
    them_nhomsp($ten_nhomsp, $hinh_nhomsp);
    header("Location: .?action=ql_sp&category_id=0");
// ===== THÊM SẢN PHẨM =====
} else if ($action == 'them_sp') {
    $category_id = $_POST['category_id'];
    $ten_sp = $_POST['ten_sp'];
    $id_kho = $_POST['id_kho'];
    // Xử lý upload hình ảnh
    $hinh = '';
    if (isset($_FILES['hinh_file']) && $_FILES['hinh_file']['error'] == 0) {
        $target_dir = "../images/sanpham/";
        $file_name = basename($_FILES["hinh_file"]["name"]);
        $target_file = $target_dir . $file_name;
        if (move_uploaded_file($_FILES["hinh_file"]["tmp_name"], $target_file)) {
            $hinh = "/sanpham/" . $file_name;
        }
    }
    $hansd = date('Y-m-d', strtotime($_POST['hansd']));
    $chitiet = $_POST['chitiet'];
    $hethan = date('Y-m-d', strtotime($_POST['hethan']));
    $soluong = $_POST['soluong'];
    add_sanpham($category_id, $ten_sp, $id_kho, $hinh, $hansd, $chitiet, $hethan, $soluong);
    header("Location: .?action=ql_sp&category_id=0");
    exit();
// ===== QUẢN LÝ HÌNH ẢNH =====
} else if ($action == 'ql_hinhanh') {
    $hinh_nsp = get_hinh_nsp();
    $hinh_sp = get_hinh_sp();
    include('ql_hinhanh.php');
// ===== XÓA HÌNH ẢNH NHÓM SẢN PHẨM =====
} else if ($action == 'del_img_nsp') {
    $img = $_POST['id_img'];
    unlink('../images/nhomsp/'.$img);
    $hinh_nsp = get_hinh_nsp();
    $hinh_sp = get_hinh_sp();
    include('ql_hinhanh.php');
// ===== XÓA HÌNH ẢNH SẢN PHẨM =====
} else if ($action == 'del_img_sp') {
    $img = $_POST['id_img'];
    unlink('../images/sanpham/'.$img);
    $hinh_nsp = get_hinh_nsp();
    $hinh_sp = get_hinh_sp();
    include('ql_hinhanh.php');
// ===== SỬA NHÓM SẢN PHẨM =====
} else if ($action == 'edit_nsp'){
    $category_id = $_GET['category_id'];
    $category_name1 = get_ten_nhomsp($category_id);
    $category_img = get_hinh_nhomsp($category_id);
    $categories = get_nhomsp();
    $categories2 = get_nhomsp();
    $products = get_sanpham_all();
    $category_name = 'Tất cả';
    include('sua_nhomsp.php');
// ===== SỬA SẢN PHẨM =====
} else if ($action == 'edit_sp'){
    $product_id = $_GET['product_id'];
    $products = get_sanpham($product_id);
    $categories = get_nhomsp();
    $category_name = 'Tất cả';
    include('edit_sp.php');
// ===== LƯU SỬA SẢN PHẨM =====
} else if ($action == 'sua_sp') {
    $product_id = $_POST['product_id'];
    $category_id = $_POST['category_id'];
    $id_kho = $_POST['id_kho']; // lấy id_kho từ form sửa sản phẩm
    $ten_sp = $_POST['ten_sp'];
    $hinh = $_POST['hinh'];
    $hansd = date('Y-m-d', strtotime($_POST['hansd']));
    $chitiet = isset($_POST['chitiet']) ? $_POST['chitiet'] : '';
    $hethan = date('Y-m-d', strtotime($_POST['hethan']));
    $soluong = $_POST['soluong'];
    // Gọi hàm cập nhật sản phẩm với id_kho thay cho hangsx
    sua_sp($category_id, $ten_sp, $id_kho, $hinh, $hansd, $chitiet, $hethan, $soluong, $product_id);
    header("Location: .?action=edit_sp&product_id=$product_id");
// ===== LƯU SỬA NHÓM SẢN PHẨM =====
} else if ($action == 'sua_nhomsp') {
    $ten_nhomsp = $_POST['ten_nhomsp'];
    $hinh_nhomsp = $_POST['hinh_nhomsp'];
    $category_id = $_POST['category_id'];
    sua_nhomsp($ten_nhomsp, $hinh_nhomsp, $category_id);
    header("Location: .?action=ql_sp&category_id=0");
// ===== QUẢN LÝ NGƯỜI DÙNG =====
} else if ($action == 'ql_user') {
    $id = $_GET['id'];
    if($id =='0'){
        $users = get_user();
        $breadcrumb = 'Tất cả';
        include('ql_user.php');
    } else {
        $users = get_user_theo_loai($id);
        $breadcrumb = get_loai_user($id);
        if($breadcrumb == '1'){
            $breadcrumb = 'Nhân viên';
        } else {
            $breadcrumb = 'Quản trị viên';
        }
        include('ql_user.php');
    }
// ===== XÓA NGƯỜI DÙNG =====
} else if ($action == 'del_user') {
    $id = $_POST['id'];
    delete_user($id);
    header("Location: .?action=ql_user&id=0");
// ===== THÊM ADMIN =====
} else if ($action == 'them_admin') {
    $user = $_POST['user'];
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    them_admin($user, $email, $pass);
    header("Location: .?action=ql_user&id=0");
// ===== QUẢN LÝ GIỎ HÀNG =====
} else if ($action == 'ql_cart') {
    $categories = get_cart();
    include('ql_cart.php');
// ===== XÓA GIỎ HÀNG =====
} else if ($action == 'del_cart') {
    $id = $_POST['id'];
    delete_cart($id);
    header("Location: .?action=ql_cart");
// ===== CẬP NHẬT GIỎ HÀNG =====
} else if ($action == 'up_cart') {
    $cartid = $_POST['cartid'];
    $userid = $_POST['userid'];
    $chitiet = $_POST['chitiet'];
    $trangthai = $_POST['trangthai'];
    $giatien = $_POST['giatien'];
    $date = $_POST['date'];
    $Phone = $_POST['Phone'];
    $shipping_address = $_POST['shipping_address'];
    up_cart($userid, $chitiet, $giatien, $trangthai, $date, $cartid,$Phone,$shipping_address);
    header("Location: .?action=ql_cart");
// ===== QUẢN LÝ KHO =====
} else if ($action == 'ql_kho') {
    $ds_kho = get_all_kho();
    include('ql_kho.php');
// ===== THÊM KHO =====
} else if ($action == 'them_kho') {
    $ten_kho = $_POST['ten_kho'];
    $diachi = $_POST['diachi'];
    them_kho($ten_kho, $diachi);
    header("Location: .?action=ql_sp&category_id=0");
    exit();
// ===== XÓA KHO =====
} else if ($action == 'del_kho') {
    $id_kho = $_POST['id_kho'];
    delete_kho($id_kho);
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        exit;
    }
    header("Location: .?action=ql_sp&category_id=0");
    exit();
// ===== QUẢN LÝ PHIẾU XUẤT NHẬP KHO =====
} else if ($action == 'ql_phieuXNKho') {
    require_once('model/phieu.php');
    $ds_phieu_nhap = get_all_phieu('nhap');
    $ds_phieu_xuat = get_all_phieu('xuat');
    $ds_nhomsp = get_nhomsp();
    $ds_sp = get_sanpham_all();
    $ds_kho = get_all_kho();
    require_once('model/user_db.php');
    $users = get_user();
    $edit_phieu = null;
    $success_message = '';

    // Xử lý edit_id để sửa phiếu
    if (isset($_GET['edit_id'])) {
        $edit_phieu = getPhieuById(intval($_GET['edit_id']));
        // Lấy chi tiết sản phẩm cho phiếu sửa
        $edit_phieu['chitiet'] = get_chitiet_phieu($edit_phieu['id']);
    }

    include('ql_phieuXNKho.php');
}
?>