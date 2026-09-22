<?php
include '../model/config.php';
include '../model/ham.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['px'])) {
    $px = intval($_POST['pxk']);
    $vattu = intval($_POST['vattu']);
    $idphieuxuat = intval($_POST['px']);

    // Kiểm tra phiếu có tồn tại không
    $check = mysqli_query($conn, "SELECT id FROM phieuxuat_kho WHERE id = $px");
    if (mysqli_num_rows($check) == 0) {
        echo 'not_found';
        exit;
    }




    // try {
    // Lấy chi tiết vật tư trong phiếu để cộng lại vào kho
    $sql = "SELECT id_vattu, soluong FROM chitiet_phieuxuatkho WHERE id_phieuxuatkho = $px AND id_vattu = $vattu";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    $id_vattu = $row['id_vattu'];
    $soluong = $row['soluong'];

    //Update lại số lương chưa xuất kho
    mysqli_query($conn, "UPDATE chitiet_phieuxuat SET xuat_kho = xuat_kho + $soluong  WHERE id_vattu = $vattu AND id_phieuxuat = '$idphieuxuat'");




    // Xóa chi tiết phiếu
    $xoaCT = mysqli_query($conn, "DELETE FROM chitiet_phieuxuatkho WHERE id_phieuxuatkho = $px  AND id_vattu = $vattu");

    if(checkCTPXK($conn, $px) == 0){
        mysqli_query($conn, "DELETE FROM phieuxuat_kho WHERE id = $px AND id_phieuxuat = $idphieuxuat");
    }

    //Check trạng thái phiếu xuất
    if (checkTrangThaiCTPX($conn, $idphieuxuat) == 0) {
        mysqli_query($conn, "UPDATE `phieuxuat` SET `trang_thai` = 1 WHERE id = '$idphieuxuat'");
    } else {
        mysqli_query($conn, "UPDATE `phieuxuat` SET `trang_thai` = 0 WHERE id = '$idphieuxuat'");
    }
    echo 'success';
    // } catch (Exception $e) {

    //     echo 'error';
    // }

} else {
    echo 'invalid';
}
