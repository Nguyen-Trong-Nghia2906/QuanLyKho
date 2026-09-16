
<?php


// $conn = new mysqli("localhost", "admin", "Thuytrang2107@", "nhquaqvc_quanlykho");
// $conn->set_charset("utf8");

$conn = new mysqli("localhost", "root", "", "quanlykho");
$conn->set_charset("utf8");


if ($conn->connect_error) {
    die("Lỗi kết nối DB: " . $conn->connect_error);
    echo "Lỗi kết nói";
}
?>

