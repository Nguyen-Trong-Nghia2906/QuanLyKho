<?php
include '../model/config.php';
include '../model/ham.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form
    $idpx = $_POST['idphieu']; 
    $idvattu = $_POST['idvattu'];
    $maphieu = $_POST['maphieu'];
    $soluong = $_POST['soluong'];
    $check = 0;

    $sqlCheck = "SELECT * FROM phieuxuat_kho WHERE maphieu = '$maphieu' AND id_phieuxuat = '$idpx'";
    $query = mysqli_query($conn, $sqlCheck);
    if (mysqli_num_rows($query) > 0) {
        $idPXK = mysqli_fetch_assoc($query);
        $check = 1;
        $id_phieuxuatkho = $idPXK['id'];
    } else {
        $sqlPhieu = "INSERT INTO phieuxuat_kho (maphieu, id_phieuxuat) 
               VALUES ('$maphieu', '$idpx')";
        if (mysqli_query($conn, $sqlPhieu)) {
            $check = 1;
            $id_phieuxuatkho = mysqli_insert_id($conn);
        }
    }

    if ($check) {
        if(checkCTPXK2($conn, $id_phieuxuatkho, $idvattu) > 0){
            $sqlChiTiet = "UPDATE `chitiet_phieuxuatkho` SET soluong = soluong + $soluong WHERE id_phieuxuatkho = $id_phieuxuatkho AND id_vattu = '$idvattu'";
        }
        else{
            $sqlChiTiet = "INSERT INTO `chitiet_phieuxuatkho`(`id_phieuxuatkho`, `id_vattu`, `soluong`) VALUES ('$id_phieuxuatkho','$idvattu','$soluong')";
        }

        if (mysqli_query($conn, $sqlChiTiet)) {
            //Cập nhật số lượng còn lại
            if (mysqli_query($conn, "UPDATE `chitiet_phieuxuat` SET `xuat_kho` = `xuat_kho` - $soluong WHERE id_phieuxuat = '$idpx' AND id_vattu = '$idvattu'")) {
                if(checkTrangThaiCTPX($conn, $idpx) == 0){
                    mysqli_query($conn, "UPDATE `phieuxuat` SET `trang_thai` = 1 WHERE id = '$idpx'");
                }
                echo "success";
            }
            else{
                echo "Lỗi khi lưu chi tiết phiếu xuất.";
            }
        } else {
            echo "Lỗi khi lưu chi tiết phiếu xuất.";
        }

    } else {
        echo "Lỗi khi lưu phiếu xuất: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>