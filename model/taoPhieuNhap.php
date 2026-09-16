<?php
include '../model/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
  $ngay_nhap = $_POST['ngay_nhap'];
  $ncc = $_POST['nhacungcap'];

  $idVattuArr = $_POST['idvt'] ?? [];
  $soLuongArr = $_POST['soluong'] ?? [];
  // Bảo vệ khi không có vật tư
  if (count($idVattuArr) === 0) {
    echo "Không có vật tư nào được chọn!";
    exit;
  }

  // Tạo phiếu xuất
  $sqlPhieu = "INSERT INTO phieunhap (nhacungcap, ngaynhap) 
               VALUES ('$ncc', '$ngay_nhap')";

  if (mysqli_query($conn, $sqlPhieu)) {
    $id_phieunhap = mysqli_insert_id($conn);
    $thanhCong = true;

    // Thêm từng dòng chi tiết
    for ($i = 0; $i < count($idVattuArr); $i++) {
      $idVT = mysqli_real_escape_string($conn, $idVattuArr[$i]);
      $sl = floatval($soLuongArr[$i]);
      $md = mysqli_real_escape_string($conn, $mucDichArr[$i]);

      $sqlChiTiet = "INSERT INTO chitiet_phieunhap (id_phieunhap, id_vattu, soluong) 
                     VALUES ('$id_phieunhap', '$idVT', '$sl')";

      if (!mysqli_query($conn, $sqlChiTiet)) {
        $thanhCong = false;
        break;
      }

      // Cộng số lượng tồn kho
      $updateKho = "UPDATE vattu SET soluong = soluong + '$sl' WHERE id = '$idVT'";
      mysqli_query($conn, $updateKho);

      //Lấy số lượng tồn sau khi cập nhật
      $sql = "SELECT soluong FROM vattu WHERE id = $idVT";
      $result = mysqli_query($conn, $sql);
      $row = mysqli_fetch_assoc($result);
      $tonluyke = $row['soluong'];

      //Cập nhật tồn luỹ kế
      $updateLuyke = "UPDATE chitiet_phieunhap SET ton_luyke = '$tonluyke' WHERE id_phieunhap = '$id_phieunhap' AND id_vattu = '$idVT'";
      mysqli_query($conn, $updateLuyke);
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
