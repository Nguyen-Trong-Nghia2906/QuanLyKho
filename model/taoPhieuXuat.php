<?php
include '../model/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Lấy dữ liệu từ form
  $idNhanVien = $_POST['nguoi_de_nghi']; // hoặc lấy từ session đăng nhập
  $ngayXuat = $_POST['ngay_xuat'];
  $maphieu = $_POST['so_phieu'];
  isset($_POST['ghichu'])? $ghichu = $_POST['ghichu'] : $ghichu = "";

  $idVattuArr = $_POST['idvt'] ?? [];
  $soLuongArr = $_POST['soluong'] ?? [];
  $mucDichArr = $_POST['mucdich'] ?? [];

  // Bảo vệ khi không có vật tư
  if (count($idVattuArr) === 0) {
    echo "Không có vật tư nào được chọn!";
    exit;
  }

  // Tạo phiếu xuất
  $sqlPhieu = "INSERT INTO phieuxuat (maphieu, id_nhanvien, ngayxuat, ghichu) 
               VALUES ('$maphieu', '$idNhanVien', '$ngayXuat', '$ghichu')";

  if (mysqli_query($conn, $sqlPhieu)) {
    $id_phieuxuat = mysqli_insert_id($conn);

    $thanhCong = true;

    // Thêm từng dòng chi tiết
    for ($i = 0; $i < count($idVattuArr); $i++) {
      $idVT = mysqli_real_escape_string($conn, $idVattuArr[$i]);
      $sl = floatval($soLuongArr[$i]);
      $md = mysqli_real_escape_string($conn, $mucDichArr[$i]);

      $sqlChiTiet = "INSERT INTO chitiet_phieuxuat (id_phieuxuat, id_vattu, soluong, mucdich) 
                     VALUES ('$id_phieuxuat', '$idVT', '$sl', '$md')";

      if (!mysqli_query($conn, $sqlChiTiet)) {
        $thanhCong = false;
        break;
      }

      // Trừ số lượng tồn kho (nếu cần)
      $updateKho = "UPDATE vattu SET soluong = soluong - '$sl' WHERE id = '$idVT'";
      mysqli_query($conn, $updateKho);
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
?>
