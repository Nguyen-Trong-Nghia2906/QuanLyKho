<?php

function getMaPhieuXuat($conn, $idpx)
{
    $sql = "SELECT maphieu FROM phieuxuat WHERE id = $idpx";
    $result = mysqli_fetch_assoc(mysqli_query($conn, $sql));
    return $result['maphieu'];
}
function getNameNV($conn, $id)
{
    $sql = "SELECT hoten FROM nhanvien WHERE id = $id";
    $result = mysqli_fetch_array(mysqli_query($conn, $sql));
    return $result['hoten'];
}

function getToNV($conn, $id)
{
    $sql = "SELECT to_nhanvien.id as id FROM to_nhanvien JOIN nhanvien ON to_nhanvien.id = nhanvien.id_to WHERE nhanvien.id = $id";
    $result = mysqli_fetch_array(mysqli_query($conn, $sql));
    return $result['id'];
}

function getTo($conn, $idpx)
{
    $sql = "SELECT to_nhanvien.tento FROM to_nhanvien JOIN nhanvien ON to_nhanvien.id = nhanvien.id_to JOIN phieuxuat px ON px.id_nhanvien = nhanvien.id WHERE px.id = $idpx";
    $result = mysqli_fetch_array(mysqli_query($conn, $sql));
    return $result['tento'];
}

function getTenNV($conn, $idpx)
{
    $sql = "SELECT nhanvien.hoten FROM to_nhanvien JOIN nhanvien ON to_nhanvien.id = nhanvien.id_to JOIN phieuxuat px ON px.id_nhanvien = nhanvien.id WHERE px.id = $idpx";
    $result = mysqli_fetch_array(mysqli_query($conn, $sql));
    return $result['hoten'];
}


function getNameTo($conn, $id)
{
    $sql = "SELECT tento FROM to_nhanvien JOIN nhanvien ON to_nhanvien.id = nhanvien.id_to WHERE nhanvien.id = $id";
    $result = mysqli_fetch_array(mysqli_query($conn, $sql));
    return $result['tento'];
}

function getChiTietPhieuXuat($conn, $id)
{
    $sql = "SELECT * FROM chitiet_phieuxuat  WHERE id_phieuxuat = $id";
    return mysqli_fetch_assoc(mysqli_query($conn, $sql));
}

function getSLXK($conn, $id, $idvattu)
{
    $sql = "SELECT xuat_kho FROM chitiet_phieuxuat  WHERE id_phieuxuat = $id AND id_vattu = '$idvattu'";
    $result = mysqli_fetch_assoc(mysqli_query($conn, $sql));
    return $result['xuat_kho'];
}

function getMaVT($conn, $id)
{
    $sql = "SELECT mahang FROM vattu WHERE id = $id";
    $result = mysqli_fetch_array(mysqli_query($conn, $sql));
    return $result['mahang'];
}

function getTenVT($conn, $id)
{
    $sql = "SELECT tenhang FROM vattu WHERE id = $id";
    $result = mysqli_fetch_array(mysqli_query($conn, $sql));
    return $result['tenhang'];
}

function getDvtVT($conn, $id)
{
    $sql = "SELECT dvt FROM vattu WHERE id = $id";
    $result = mysqli_fetch_array(mysqli_query($conn, $sql));
    return $result['dvt'];
}

function getSlVT($conn, $id)
{
    $sql = "SELECT soluong FROM vattu WHERE id = $id";
    $result = mysqli_fetch_array(mysqli_query($conn, $sql));
    return $result['soluong'];
}

function checkTrangThaiCTPX($conn, $id_phieuxuat)
{
    $sql = "SELECT xuat_kho FROM chitiet_phieuxuat WHERE id_phieuxuat = $id_phieuxuat AND xuat_kho > 0";
    $result = mysqli_num_rows(mysqli_query($conn, $sql));
    return $result;
}

// function checkTrangThaiPX($conn, $id_phieuxuat){
//     $sql = "SELECT xuat_kho FROM chitiet_phieuxuat WHERE id_phieuxuat = $id_phieuxuat AND xuat_kho > 0";
//     $result = mysqli_num_rows(mysqli_query($conn, $sql));
//     return $result;
// }


//Check xem phiếu xuất kho đó còn chi tiết không
function checkCTPXK($conn, $id_phieuxuatkho)
{
    $sql = "SELECT id_phieuxuatkho FROM chitiet_phieuxuatkho ct WHERE id_phieuxuatkho = $id_phieuxuatkho";
    $result = mysqli_num_rows(mysqli_query($conn, $sql));
    return $result;
}


function checkCTPXK2($conn, $id_phieuxuatkho, $id_vattu)
{
    $sql = "SELECT id_phieuxuatkho FROM chitiet_phieuxuatkho ct WHERE id_phieuxuatkho = $id_phieuxuatkho AND id_vattu = '$id_vattu'";
    $result = mysqli_num_rows(mysqli_query($conn, $sql));
    return $result;
}

function getIdPXK($conn, $id_phieuxuat, $id_vattu)
{
    $sql = "SELECT pxk.id as id 
        FROM chitiet_phieuxuatkho ct JOIN phieuxuat_kho pxk ON ct.id_phieuxuatkho = pxk.id 
        WHERE pxk.id_phieuxuat = $id_phieuxuat AND ct.id_vattu = '$id_vattu'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        return $result;
    }
    return 0;
}



?>