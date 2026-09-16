<?php
include '../model/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Kiểm tra phiếu có tồn tại không
    $check = mysqli_query($conn, "SELECT id FROM phieuung WHERE id = $id");
    if (mysqli_num_rows($check) == 0) {
        echo 'not_found';
        exit;
    }

    // Lấy chi tiết vật tư trong phiếu để cộng lại vào kho
    $sql = "SELECT id_vattu, soluong FROM chitiet_phieuung WHERE id_phieuung = $id";
    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        $id_vattu = $row['id_vattu'];
        $soluong = $row['soluong'];

        // Cộng lại số lượng vào bảng vật tư
        mysqli_query($conn, "
            UPDATE vattu
            SET soluong = soluong + $soluong
            WHERE id = $id_vattu
        ");
    }

    // Xóa chi tiết phiếu
    $xoaCT = mysqli_query($conn, "DELETE FROM chitiet_phieuung WHERE id_phieuung = $id");

    // Xóa phiếu xuất
    $xoaPhieu = mysqli_query($conn, "DELETE FROM phieuung WHERE id = $id");

    if ($xoaCT && $xoaPhieu) {
        echo 'success';
    } else {
        echo 'error';
    }
} else {
    echo 'invalid';
}
?>
