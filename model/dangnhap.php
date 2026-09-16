<?php
include '../model/config.php';
session_start();
$taikhoan = $_POST['taikhoan'];
$matkhau = $_POST['matkhau'];

$sql = "SELECT * FROM nguoidung WHERE taikhoan = '$taikhoan' AND matkhau = '$matkhau'";
$result = mysqli_query($conn, $sql);
$num = mysqli_num_rows($result) ;
if($num > 0){
    $user = mysqli_fetch_assoc($result);
    $_SESSION['hoten'] = $user['hoten'];
    $_SESSION['quyen'] = $user['quyen'];
    $sql = "UPDATE nguoidung SET count = count+1 WHERE id = '".$user['id']."'";
    mysqli_query($conn, $sql);
    echo "success";
}
else{
    echo "error";
}

?>