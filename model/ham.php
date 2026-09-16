<?php
    function getNameNV($conn, $id){
        $sql = "SELECT hoten FROM nhanvien WHERE id = $id";
        $result = mysqli_fetch_array(mysqli_query($conn, $sql));
        return $result['hoten'];
    }

    function getToNV($conn, $id){
        $sql = "SELECT to_nhanvien.id as id FROM to_nhanvien JOIN nhanvien ON to_nhanvien.id = nhanvien.id_to WHERE nhanvien.id = $id";
        $result = mysqli_fetch_array(mysqli_query($conn, $sql));
        return $result['id'];
    }


    function getNameTo($conn, $id){
        $sql = "SELECT tento FROM to_nhanvien JOIN nhanvien ON to_nhanvien.id = nhanvien.id_to WHERE nhanvien.id = $id";
        $result = mysqli_fetch_array(mysqli_query($conn, $sql));
        return $result['tento'];
    }

    function getChiTietPhieuXuat($conn, $id){
        $sql = "SELECT * FROM chitiet_phieuxuat  WHERE id_phieuxuat = $id";
        return mysqli_fetch_assoc(mysqli_query($conn, $sql));
    }

    function getMaVT($conn, $id){
        $sql = "SELECT mahang FROM vattu WHERE id = $id";
        $result = mysqli_fetch_array(mysqli_query($conn, $sql));
        return $result['mahang'];
    }

    function getTenVT($conn, $id){
        $sql = "SELECT tenhang FROM vattu WHERE id = $id";
        $result = mysqli_fetch_array(mysqli_query($conn, $sql));
        return $result['tenhang'];
    }

    function getDvtVT($conn, $id){
        $sql = "SELECT dvt FROM vattu WHERE id = $id";
        $result = mysqli_fetch_array(mysqli_query($conn, $sql));
        return $result['dvt'];
    }

    function getSlVT($conn, $id){
        $sql = "SELECT soluong FROM vattu WHERE id = $id";
        $result = mysqli_fetch_array(mysqli_query($conn, $sql));
        return $result['soluong'];
    }
?>