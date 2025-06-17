<?php
$ds          = DIRECTORY_SEPARATOR;  //1: Ký tự phân tách thư mục phù hợp với hệ điều hành
 
$storeFolder = 'images';   //2: Thư mục lưu trữ file upload
 
if (!empty($_FILES)) {
    //3: Lấy đường dẫn tạm của file vừa upload
    $tempFile = $_FILES['file']['tmp_name'];             
    //4: Xác định đường dẫn thư mục lưu trữ file
    $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;  
    //5: Tạo đường dẫn file đích
    $targetFile =  $targetPath. $_FILES['file']['name'];  
    //6: Di chuyển file từ thư mục tạm sang thư mục lưu trữ
    move_uploaded_file($tempFile,$targetFile); 
}
?>