<?php
include '../model/config.php';

$id = $_POST['id'];
$tento = $_POST['tento'];

$sql = "UPDATE to_nhanvien SET
            tento = '$tento' 
        WHERE id = $id";
if (mysqli_query($conn, $sql)) {
    echo "success";
} else {
    http_response_code(500);
    echo "fail";
}
