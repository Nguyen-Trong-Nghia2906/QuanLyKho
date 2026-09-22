<?php
include '../model/config.php';
include '../model/ham.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $maphieu = $_POST['so_phieu'];
    $id_phieuxuatkho = $_POST['id_pxk'];
    $id_phieuxuat = $_POST['id_px'];
  
    $idVattuArr = $_POST['idvt'] ?? [];
    $soLuongArr = $_POST['soluong'] ?? [];
  
    // Tạo phiếu xuất
    $sqlPhieu = "UPDATE phieuxuat_kho SET maphieu = '$maphieu' WHERE  id = $id_phieuxuatkho";

    if (mysqli_query($conn, $sqlPhieu)) {
        $thanhCong = true;

        // Thêm hoặc cập nhật từng dòng chi tiết
        for ($i = 0; $i < count($idVattuArr); $i++) {
            $idVT = mysqli_real_escape_string($conn, $idVattuArr[$i]);
            $sl = floatval($soLuongArr[$i]);
            
            // Kiểm tra vật tư đã có trong phiếu chưa
            $checkSql = "SELECT * FROM chitiet_phieuxuatkho
                 WHERE id_phieuxuatkho = '$id_phieuxuatkho' AND id_vattu = '$idVT'";
            $checkResult = mysqli_query($conn, $checkSql);
            $check = mysqli_num_rows($checkResult);
            if ($check > 0) {
                // Nếu đã có → UPDATE
                $sqlChiTiet = "UPDATE chitiet_phieuxuatkho 
                       SET soluong = '$sl'
                       WHERE id_phieuxuatkho = '$id_phieuxuatkho' AND id_vattu = '$idVT'";
            }

            if (!mysqli_query($conn, $sqlChiTiet)) {
                $thanhCong = false;
                break;
            }

            // Trừ hoặc cập nhật kho
            // Lưu ý: nếu update số lượng, bạn cần tính lại chênh lệch để trừ kho cho đúng
            // Ví dụ:

            if ($check > 0) {
                // Lấy số lượng cũ
                $oldData = mysqli_fetch_assoc($checkResult);
                $slCu = floatval($oldData['soluong']);
                $chenhLech = $sl - $slCu; // dương: xuất thêm, âm: trả lại
                if ($chenhLech != 0) {
                    $updatePX = "UPDATE chitiet_phieuxuat SET xuat_kho = xuat_kho - '$chenhLech' WHERE id_vattu = '$idVT' AND id_phieuxuat = '$id_phieuxuat'";
                    mysqli_query($conn, $updatePX);


                    if($chenhLech < 0){
                        mysqli_query($conn, "UPDATE `phieuxuat` SET `trang_thai` = 0 WHERE id = '$id_phieuxuat'");
                    }
                    if (checkTrangThaiCTPX($conn, $id_phieuxuat) == 0) {
                        mysqli_query($conn, "UPDATE `phieuxuat` SET `trang_thai` = 1 WHERE id = '$id_phieuxuat'");
                    }
                    
                }
            } 
        }


        if ($thanhCong) {
            echo "success";
        } else {
            echo "Lỗi khi lưu chi tiết phiếu xuất.";
        }
    } else {
        echo "Lỗi khi lưu phiếu xuất: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
