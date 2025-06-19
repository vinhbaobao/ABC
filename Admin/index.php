<?php
// ======= PHẦN: Import các file model cần thiết =======
require('model/database.php');
require('model/nhomsp_db.php');
require('model/sanpham_db.php');
require('model/user_db.php');
require('model/cart_db.php');
require('model/kho_dp.php');
session_start();

// ======= PHẦN: Xác định action hiện tại =======
if (isset($_POST['action'])) {
    $action = $_POST['action'];
} else if (isset($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = 'home';
}

// ======= PHÂN QUYỀN NHÂN VIÊN =======
$staff_allowed = [
    'ql_phieuXNKho', 'add_phieu', 'edit_phieu', 'del_phieu',
    'ql_kho', 'them_kho', 
    'ql_cart', 'del_cart', 'up_cart',
    'ql_sp', 'them_sp', 'del_sp', 'them_nhomsp', 'del_nhomsp', 'edit_sp', 'edit_nsp', 'sua_sp', 'sua_nhomsp',
    'logout'
];
if (isset($_SESSION['Loai']) && $_SESSION['Loai'] == 1) {
    if (!in_array($action, $staff_allowed)) {
        header("Location: index.php?action=ql_phieuXNKho");
        exit();
    }
}

// ======= KHU: QUẢN LÝ PHIẾU XUẤT NHẬP KHO =======
if (in_array($action, ['ql_phieuXNKho', 'add_phieu', 'edit_phieu', 'del_phieu'])) {
    require_once('model/phieu.php');
    $error_message = '';
    $success_message = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            if ($action === 'add_phieu') {
                $ma_phieu = trim($_POST['ma_phieu']);
                $ngay = $_POST['ngay'];
                $nhan_vien = trim($_POST['nhan_vien']);
                $loai_phieu = $_POST['loai_phieu'];
                $id_kho = intval($_POST['id_kho']);
                $ds_sp = isset($_POST['id_sp']) ? $_POST['id_sp'] : [];
                $ds_soluong = isset($_POST['so_luong']) ? $_POST['so_luong'] : [];
                // Kiểm tra dữ liệu
                if ($ma_phieu && in_array($loai_phieu, ['nhap', 'xuat']) && $id_kho && $ngay && $nhan_vien && is_array($ds_sp) && is_array($ds_soluong) && count($ds_sp) > 0) {
                    require_once('model/phieu.php');
                    add_phieu($ma_phieu, $ngay, $nhan_vien, $loai_phieu, $id_kho, $ds_sp, $ds_soluong);
                    header("Location: index.php?action=ql_phieuXNKho");
                    exit;
                } else {
                    $error_message = "Vui lòng nhập đầy đủ thông tin hợp lệ!";
                }
            }
            if ($action === 'edit_phieu' && isset($_POST['id'])) {
                $id = intval($_POST['id']);
                $ma_phieu = trim($_POST['ma_phieu']);
                $ngay = $_POST['ngay'];
                $nhan_vien = trim($_POST['nhan_vien']);
                $loai_phieu = $_POST['loai_phieu'];
                $id_kho = intval($_POST['id_kho']);
                $ds_sp = isset($_POST['id_sp']) ? $_POST['id_sp'] : [];
                $ds_soluong = isset($_POST['so_luong']) ? $_POST['so_luong'] : [];
                if ($ma_phieu && $ngay && $nhan_vien && in_array($loai_phieu, ['nhap', 'xuat']) && $id_kho && is_array($ds_sp) && is_array($ds_soluong) && count($ds_sp) > 0) {
                    require_once('model/phieu.php');
                    sua_phieu($id, $ma_phieu, $ngay, $nhan_vien, $loai_phieu, $id_kho, $ds_sp, $ds_soluong);
                    header("Location: index.php?action=ql_phieuXNKho");
                    exit;
                } else {
                    $error_message = "Vui lòng nhập đầy đủ thông tin hợp lệ khi sửa!";
                }
            }
            if ($action === 'del_phieu' && isset($_POST['id'])) {
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
    $ds_phieu_nhap = get_all_phieu('nhap');
    $ds_phieu_xuat = get_all_phieu('xuat');
    $ds_nhomsp = get_nhomsp();
    $ds_sp = get_sanpham_all();
    $ds_kho = get_all_kho();
    $users = get_user();
    $edit_phieu = null;
    if (isset($_GET['edit_id'])) {
        $edit_phieu = getPhieuById(intval($_GET['edit_id']));
        $edit_phieu['chitiet'] = get_chitiet_phieu($edit_phieu['id']);
    }
    include('ql_phieuXNKho.php');
    exit();
}

// ======= KHU: QUẢN LÝ KHO =======
if ($action == 'ql_kho') {
    $ds_kho = get_all_kho();
    include('ql_kho.php');
    exit();
} else if ($action == 'them_kho') {
    $ten_kho = $_POST['ten_kho'];
    $diachi = $_POST['diachi'];
    them_kho($ten_kho, $diachi);
    header("Location: .?action=ql_sp&category_id=0");
    exit();
} else if ($action == 'del_kho') {
    $id_kho = $_POST['id_kho'];
    delete_kho($id_kho);
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        exit;
    }
    header("Location: .?action=ql_sp&category_id=0");
    exit();
}

// ======= KHU: QUẢN LÝ SẢN PHẨM =======
if ($action == 'ql_sp') {
    $category_id = isset($_GET['category_id']) ? $_GET['category_id'] : '0';
    if ($category_id == '0') {
        $categories = get_nhomsp();
        $categories2 = get_nhomsp();
        $products = get_sanpham_all();
        $category_name = 'Tất cả';
        include('ql_sp.php');
    } else {
        $category_name = get_ten_nhomsp($category_id);
        $categories = get_nhomsp();
        $categories2 = get_nhomsp();
        $products = get_sanpham_theo_nhomsp($category_id);
        include('ql_sp.php');
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
    exit();
} else if ($action == 'del_img_nsp') {
    $img = $_POST['id_img'];
    unlink('../images/nhomsp/'.$img);
    $hinh_nsp = get_hinh_nsp();
    $hinh_sp = get_hinh_sp();
    include('ql_hinhanh.php');
    exit();
} else if ($action == 'del_img_sp') {
    $img = $_POST['id_img'];
    unlink('../images/sanpham/'.$img);
    $hinh_nsp = get_hinh_nsp();
    $hinh_sp = get_hinh_sp();
    include('ql_hinhanh.php');
    exit();
}

// ======= KHU: TRANG CHỦ ADMIN =======
if ($action == 'home') {
    $categories = get_nhomsp();
    $categories1 = get_nhomsp();
    $users = get_user();
    include('home.php');
    exit();
}
// ===== QUẢN LÝ NGƯỜI DÙNG =====
 else if ($action == 'ql_user') {
    $id = $_GET['id'];
    if($id =='0'){
        $users = get_user();
        $breadcrumb = 'Tất cả';
        include('ql_user.php');
    } else {
        $users = get_user_theo_loai($id);
        $breadcrumb = get_loai_user($id);
        if($breadcrumb == '1'){
            $breadcrumb = 'Người dùng';
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
    exit();
// ===== CẬP NHẬT CHỨC VỤ NGƯỜI DÙ
} else if ($action == 'update_role') {
    $id = $_POST['id'];
    $role = $_POST['role'];
    update_role($id, $role);
    header("Location: .?action=ql_user&id=0");
    exit();
}  else if ($action == 'them_admin') {
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
    exit();
} else if ($action == 'up_cart') {
    $id = $_POST['id'];
    $soluong = $_POST['soluong'];
    update_cart($id, $soluong);
    header("Location: .?action=ql_cart");
    exit();
} else if ($action == 'them_user') {
    // Xử lý thêm người dùng từ form (POST)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user = isset($_POST['user']) ? trim($_POST['user']) : '';
        $pass = isset($_POST['pass']) ? $_POST['pass'] : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $loai = isset($_POST['loai']) ? intval($_POST['loai']) : 1;
        $tb = [];
        if ($user && $pass && $email) {
            if (!preg_match('/[A-Z]/', $pass)) {
                $tb["user_message"] = "Mật khẩu phải có ít nhất 1 chữ hoa!";
            } else {
                // Thêm vào bảng user với trường Loai (1: nhân viên, 2: quản trị viên)
                them_user($user, $email, $pass, $loai);
                $tb["user_message"] = ($loai == 2) ? "Thêm quản trị viên thành công!" : "Thêm nhân viên thành công!";
            }
        } else {
            $tb["user_message"] = "Vui lòng nhập đầy đủ thông tin!";
        }
        $_SESSION['user_message'] = $tb["user_message"];
    }
    header("Location: index.php?action=ql_user&id=0");
    exit();
}

?>