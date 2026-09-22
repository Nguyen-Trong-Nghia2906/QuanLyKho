<?php
include '../model/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $idpx = intval($_POST['idpx']);
    // Kiểm tra phiếu có tồn tại không
    $check = mysqli_query($conn, "SELECT id FROM phieuxuat_kho WHERE id = $id");
    if (mysqli_num_rows($check) == 0) {
    echo "SELECT id FROM phieuxuat_kho WHERE id = $id";
    echo 'not_found';
        exit;
    }

    // Lấy chi tiết vật tư trong phiếu để cộng lại vào kho
    $sql = "SELECT id_vattu, soluong FROM chitiet_phieuxuatkho WHERE id_phieuxuatkho = $id";
    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        $id_vattu = $row['id_vattu'];
        $soluong = $row['soluong'];

        // Cộng lại số lượng vào bảng vật tư
        mysqli_query($conn, "
            UPDATE chitiet_phieuxuat
            SET xuat_kho = xuat_kho + $soluong
            WHERE id_vattu = $id_vattu AND id_phieuxuat = '$idpx'
        ");
    }

    mysqli_query($conn, "
            UPDATE phieuxuat
            SET trang_thai = 0
            WHERE id = '$idpx'
        ");

    // Xóa chi tiết phiếu
    $xoaCT = mysqli_query($conn, "DELETE FROM chitiet_phieuxuatkho WHERE id_phieuxuatkho = $id");

    // Xóa phiếu xuất
    $xoaPhieu = mysqli_query($conn, "DELETE FROM phieuxuat_kho WHERE id = $id");

    if ($xoaCT && $xoaPhieu) {
        echo 'success';
    } else {
        echo 'error';
    }
} else {
    echo 'invalid';
}
?>
