<?php
include '../model/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['px'])) {
    $px = intval($_POST['px']);
    $vattu = intval($_POST['vattu']);

    // Kiểm tra phiếu có tồn tại không
    $check = mysqli_query($conn, "SELECT id FROM phieuxuat WHERE id = $px");
    if (mysqli_num_rows($check) == 0) {
        echo 'not_found';
        exit;
    }

    // Lấy chi tiết vật tư trong phiếu để cộng lại vào kho
    $sql = "SELECT id_vattu, soluong FROM chitiet_phieuxuat WHERE id_phieuxuat = $px AND id_vattu = $vattu";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    $id_vattu = $row['id_vattu'];
    $soluong = $row['soluong'];

    // Cộng lại số lượng vào bảng vật tư
    mysqli_query($conn, "
            UPDATE vattu
            SET soluong = soluong + $soluong
            WHERE id = $id_vattu
        ");


    // Xóa chi tiết phiếu
    $xoaCT = mysqli_query($conn, "DELETE FROM chitiet_phieuxuat WHERE id_phieuxuat = $px  AND id_vattu = $vattu");

  
    if ($xoaCT) {
        echo 'success';
    } else {
        echo 'error';
    }
} else {
    echo 'invalid';
}
