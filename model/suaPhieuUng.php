<?php
include '../model/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form
    $idNhanVien = $_POST['nguoi_de_nghi']; // hoặc lấy từ session đăng nhập
    $ngayXuat = $_POST['ngay_ung'];
    $id_phieuung = $_POST['id_pu'];

    $idVattuArr = $_POST['idvt'] ?? [];
    $soLuongArr = $_POST['soluong'] ?? [];
    $mucDichArr = $_POST['mucdich'] ?? [];


    // Tạo phiếu xuất
    $sqlPhieu = "UPDATE phieuung SET  id_nhanvien = '$idNhanVien', ngayung = '$ngayXuat' WHERE  id = $id_phieuung";

    if (mysqli_query($conn, $sqlPhieu)) {
        $thanhCong = true;

        // Thêm hoặc cập nhật từng dòng chi tiết
        for ($i = 0; $i < count($idVattuArr); $i++) {
            $idVT = mysqli_real_escape_string($conn, $idVattuArr[$i]);
            $sl = floatval($soLuongArr[$i]);
            $md = mysqli_real_escape_string($conn, $mucDichArr[$i]);

            // Kiểm tra vật tư đã có trong phiếu chưa
            $checkSql = "SELECT * FROM chitiet_phieuung 
                 WHERE id_phieuung = '$id_phieuung' AND id_vattu = '$idVT'";
            $checkResult = mysqli_query($conn, $checkSql);
            $check = mysqli_num_rows($checkResult);
            if ($check > 0) {
                // Nếu đã có → UPDATE
                $sqlChiTiet = "UPDATE chitiet_phieuung 
                       SET soluong = '$sl', ghichu = '$md' 
                       WHERE id_phieuung = '$id_phieuung' AND id_vattu = '$idVT'";
            } else {
                // Nếu chưa có → INSERT
                $sqlChiTiet = "INSERT INTO chitiet_phieuung (id_phieuung, id_vattu, soluong, ghichu) 
                       VALUES ('$id_phieuung', '$idVT', '$sl', '$md')";
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
                    mysqli_query($conn, $updateKho);

                    //Lấy số lượng tồn sau khi cập nhật
                    $sql = "SELECT soluong FROM vattu WHERE id = $idVT";
                    $result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_assoc($result);
                    $tonluyke = $row['soluong'];

                    //Cập nhật tồn luỹ kế
                    $updateLuyke = "UPDATE chitiet_phieuung SET ton_luyke = '$tonluyke' WHERE id_phieuung = '$id_phieuung' AND id_vattu = '$idVT'";
                    mysqli_query($conn, $updateLuyke);
                }
            } else {
                // Trường hợp mới → trừ thẳng
                $updateKho = "UPDATE vattu SET soluong = soluong - '$sl' WHERE id = '$idVT'";
                mysqli_query($conn, $updateKho);

                //Lấy số lượng tồn sau khi cập nhật
                $sql = "SELECT soluong FROM vattu WHERE id = $idVT";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
                $tonluyke = $row['soluong'];

                //Cập nhật tồn luỹ kế
                $updateLuyke = "UPDATE chitiet_phieuung SET ton_luyke = '$tonluyke' WHERE id_phieuung = '$id_phieuung' AND id_vattu = '$idVT'";
                mysqli_query($conn, $updateLuyke);
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
