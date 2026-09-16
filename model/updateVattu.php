<?php
include '../model/config.php';

$id = $_POST['id'];
$tenhang = $_POST['tenhang'];
$mahang = $_POST['mahang'];
$giatri = $_POST['giatri'];
$vitri = $_POST['vitri'];
$dvt = $_POST['dvt'];
$tenkho = $_POST['tenkho'];


$sql = "UPDATE vattu SET
            tenhang = '$tenhang',
            mahang = '$mahang',
            vitri = '$vitri',
            giatri = '$giatri',
            dvt = '$dvt',
            tenkho = '$tenkho' 
           
        WHERE id = $id";

if (mysqli_query($conn, $sql)) {
    echo "success";
} else {
    http_response_code(500);
    echo "fail";
}
