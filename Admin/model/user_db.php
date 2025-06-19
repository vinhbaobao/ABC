<?php 
// Đảm bảo biến $db luôn tồn tại và kết nối PDO thành công
if (!isset($db) || !$db) {
    require_once __DIR__ . '/database.php';
    if (!isset($db) || !$db) {
        die('Không kết nối được database!');
    }
}
// Thêm user mới vào bảng user (hash mật khẩu, cho phép chọn loại)
function them_user($user, $email, $pass, $loai = 1){
    global $db;
    $hashed = password_hash($pass, PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO user (Username, Email, Matkhau, Loai) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user, $email, $hashed, $loai]);
}

function get_user() {
    global $db;
    $stmt = $db->query("SELECT * FROM user ORDER BY Loai");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Lấy IdUser theo Username (prepared statement)
function get_id_user($username) {
    global $db;
    $stmt = $db->prepare("SELECT IdUser FROM user WHERE Username = ?");
    $stmt->execute([$username]);
    $row = $stmt->fetch();
    return $row ? $row['IdUser'] : null;
}

// Xóa user theo IdUser (prepared statement)
function delete_user($id) {
    global $db;
    $stmt = $db->prepare("DELETE FROM user WHERE IdUser = ?");
    $stmt->execute([$id]);
}

// Lấy danh sách user theo Loai (prepared statement)
function get_user_theo_loai($id) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM user WHERE Loai = ?");
    $stmt->execute([$id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Lấy Loai user theo IdUser (prepared statement)
function get_loai_user($id) {
    global $db;
    $stmt = $db->prepare("SELECT Loai FROM user WHERE IdUser = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    return $row ? $row['Loai'] : null;
}

// Lấy Username theo IdUser (prepared statement)
function get_ten_user($category_id) {
    global $db;
    $stmt = $db->prepare("SELECT Username FROM user WHERE IdUser = ?");
    $stmt->execute([$category_id]);
    $row = $stmt->fetch();
    return $row ? $row['Username'] : null;
}

// Lấy Email theo IdUser (prepared statement)
function get_email_user($category_id) {
    global $db;
    $stmt = $db->prepare("SELECT Email FROM user WHERE IdUser = ?");
    $stmt->execute([$category_id]);
    $row = $stmt->fetch();
    return $row ? $row['Email'] : null;
}

// Lấy số lượng user theo Loai (prepared statement)
function get_soluong_user($id) {
    global $db;
    $stmt = $db->prepare("SELECT COUNT(*) as total FROM user WHERE Loai = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    echo $row ? $row['total'] : 0;
    return $row ? $row['total'] : 0;
}

// Cập nhật chức vụ user (prepared statement)
function update_user_role($id, $role) {
    global $db;
    $stmt = $db->prepare("UPDATE user SET Loai = ? WHERE IdUser = ?");
    return $stmt->execute([$role, $id]);
}
function them_admin($user, $email, $pass) {
    global $db;
    $query = 'INSERT INTO admins (username, email, password) VALUES (:user, :email, :pass)';
    $statement = $db->prepare($query);
    $statement->bindValue(':user', $user);
    $statement->bindValue(':email', $email);
    $statement->bindValue(':pass', password_hash($pass, PASSWORD_DEFAULT));
    $statement->execute();
    $statement->closeCursor();
}
function update_role($id, $role) {
    global $db;
    $query = 'UPDATE user SET Loai = :role WHERE IdUser = :id';
    $statement = $db->prepare($query);
    $statement->bindValue(':role', $role);
    $statement->bindValue(':id', $id);
    return $statement->execute();
}
?>