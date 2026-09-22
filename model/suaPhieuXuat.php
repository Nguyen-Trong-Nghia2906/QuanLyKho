<?php
include '../model/config.php';
include '../model/ham.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form
    $idNhanVien = $_POST['nguoi_de_nghi']; // hoặc lấy từ session đăng nhập
    $ngayXuat = $_POST['ngay_xuat'];
    $maphieu = $_POST['so_phieu'];
    $id_phieuxuat = $_POST['id_px'];
    $ghichu = $_POST['ghichu'];

    $idVattuArr = $_POST['idvt'] ?? [];
    $soLuongArr = $_POST['soluong'] ?? [];
    $mucDichArr = $_POST['mucdich'] ?? [];


    // Tạo phiếu xuất
    $sqlPhieu = "UPDATE phieuxuat SET maphieu = '$maphieu', id_nhanvien = '$idNhanVien', ngayxuat = '$ngayXuat', ghichu = '$ghichu' WHERE  id = $id_phieuxuat";

    if (mysqli_query($conn, $sqlPhieu)) {
        $thanhCong = true;

        // Thêm hoặc cập nhật từng dòng chi tiết
        for ($i = 0; $i < count($idVattuArr); $i++) {
            $idVT = mysqli_real_escape_string($conn, $idVattuArr[$i]);
            $sl = floatval($soLuongArr[$i]);
            $md = mysqli_real_escape_string($conn, $mucDichArr[$i]);

            // Kiểm tra vật tư đã có trong phiếu chưa
            $checkSql = "SELECT * FROM chitiet_phieuxuat 
                 WHERE id_phieuxuat = '$id_phieuxuat' AND id_vattu = '$idVT'";
            $checkResult = mysqli_query($conn, $checkSql);
            $check = mysqli_num_rows($checkResult);
            if ($check > 0) {
                // Nếu đã có → UPDATE
                $sqlChiTiet = "UPDATE chitiet_phieuxuat 
                       SET soluong = '$sl', mucdich = '$md' 
                       WHERE id_phieuxuat = '$id_phieuxuat' AND id_vattu = '$idVT'";
            } else {
                // Nếu chưa có → INSERT
                $sqlChiTiet = "INSERT INTO chitiet_phieuxuat (id_phieuxuat, id_vattu, soluong, mucdich, xuat_kho) 
                       VALUES ('$id_phieuxuat', '$idVT', '$sl', '$md', '$sl')";

                mysqli_query($conn, "UPDATE `phieuxuat` SET `trang_thai` = 0 WHERE id = '$id_phieuxuat'");


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
                    $updateKho = "UPDATE vattu SET soluong = soluong - '$chenhLech' WHERE id = '$idVT'";
                    $updatePX = "UPDATE chitiet_phieuxuat SET xuat_kho = xuat_kho + '$chenhLech' WHERE id_vattu = '$idVT' AND id_phieuxuat = '$id_phieuxuat'";
                    mysqli_query($conn, $updateKho);
                    mysqli_query($conn, $updatePX);


                    if($chenhLech > 0){
                        mysqli_query($conn, "UPDATE `phieuxuat` SET `trang_thai` = 0 WHERE id = '$id_phieuxuat'");
                    }
                    if (checkTrangThaiCTPX($conn, $id_phieuxuat) == 0) {
                        mysqli_query($conn, "UPDATE `phieuxuat` SET `trang_thai` = 1 WHERE id = '$id_phieuxuat'");
                    }
                    // //Lấy số lượng tồn sau khi cập nhật
                    // $sql = "SELECT soluong FROM vattu WHERE id = $idVT";
                    // $result = mysqli_query($conn, $sql);
                    // $row = mysqli_fetch_assoc($result);
                    // $tonluyke = $row['soluong'];

                    // //Cập nhật tồn luỹ kế
                    // $updateLuyke = "UPDATE chitiet_phieuxuat SET ton_luyke = '$tonluyke' WHERE id_phieuxuat = '$id_phieuxuat' AND id_vattu = '$idVT'";
                    // mysqli_query($conn, $updateLuyke);
                }
            } else {
                // Trường hợp mới → trừ thẳng
                $updateKho = "UPDATE vattu SET soluong = soluong - '$sl' WHERE id = '$idVT'";
                mysqli_query($conn, $updateKho);

                if (checkTrangThaiCTPX($conn, $id_phieuxuat) == 0) {
                    mysqli_query($conn, "UPDATE `phieuxuat` SET `trang_thai` = 1 WHERE id = '$id_phieuxuat'");
                }

                // //Lấy số lượng tồn sau khi cập nhật
                // $sql = "SELECT soluong FROM vattu WHERE id = $idVT";
                // $result = mysqli_query($conn, $sql);
                // $row = mysqli_fetch_assoc($result);
                // $tonluyke = $row['soluong'];

                // //Cập nhật tồn luỹ kế
                // $updateLuyke = "UPDATE chitiet_phieuxuat SET ton_luyke = '$tonluyke' WHERE id_phieuxuat = '$id_phieuxuat' AND id_vattu = '$idVT'";
                // mysqli_query($conn, $updateLuyke);
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
