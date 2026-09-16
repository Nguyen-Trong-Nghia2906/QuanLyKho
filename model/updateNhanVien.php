<?php
include '../model/config.php';

$id = $_POST['id'];
$hoten = $_POST['hoten'];
$id_to = $_POST['id_to'];
$sdt = $_POST['sdt'];


$sql = "UPDATE nhanvien SET
            hoten = '$hoten',
            id_to = '$id_to',
            sdt = '$sdt'
        WHERE id = $id";

if (mysqli_query($conn, $sql)) {
    echo "success";
} else {
    http_response_code(500);
    echo "fail";
}
