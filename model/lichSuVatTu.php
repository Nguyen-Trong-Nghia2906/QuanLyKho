<?php
include '../model/config.php';
include '../model/ham.php';
$id = intval($_POST['id_vattu'] ?? 0);

$sql = "
  SELECT 
    px.maphieu AS maphieu,
    px.ngayxuat AS ngay,
    nv.hoten AS nguoilienquan,
    t.tento AS to_nhanvien,
    ct.soluong,
    ct.mucdich,
    'Xuất kho' AS loaiphieu
  FROM chitiet_phieuxuat ct
  JOIN phieuxuat px ON ct.id_phieuxuat = px.id
  JOIN nhanvien nv ON px.id_nhanvien = nv.id
  JOIN to_nhanvien t ON nv.id_to = t.id
  WHERE ct.id_vattu = $id

  UNION ALL

  SELECT 
    '' AS maphieu,
    pn.ngaynhap AS ngay,
    pn.nhacungcap AS nguoilienquan,
    '' AS to_nhanvien,
    ct.soluong,
    '' AS mucdich,
    'Nhập kho' AS loaiphieu
  FROM chitiet_phieunhap ct
  JOIN phieunhap pn ON ct.id_phieunhap = pn.id
  WHERE ct.id_vattu = $id

  UNION ALL

   SELECT 
    '' AS maphieu,
    pu.ngayung AS ngay,
    nv.hoten AS nguoilienquan,
    t.tento AS to_nhanvien,
    ct.soluong,
    ct.ghichu as mucdich,
    'Phiếu ứng' AS loaiphieu
  FROM chitiet_phieuung ct
  JOIN phieuung pu ON ct.id_phieuung = pu.id
  JOIN nhanvien nv ON pu.id_nhanvien = nv.id
  JOIN to_nhanvien t ON nv.id_to = t.id
  WHERE ct.id_vattu = $id


  ORDER BY ngay DESC
";


$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
  echo '<table class="table table-bordered table-sm">';
  echo '<thead class="table-secondary">
          <tr>
            <th>Ngày</th>
            <th>Loại phiếu</th>
            <th>Mã phiếu</th>
            <th>Người liên quan</th>
            <th>Tổ</th>
            <th>Số lượng</th>
            <th>Mục đích / Ghi chú</th>
          </tr>
        </thead><tbody>';
  while ($row = mysqli_fetch_assoc($result)) {
    echo '<tr>';
    echo '<td>' . date('d/m/Y', strtotime($row['ngay'])) . '</td>';
    echo '<td>' . htmlspecialchars($row['loaiphieu']) . '</td>';
    echo '<td>' . htmlspecialchars($row['maphieu']) . '</td>';
    echo '<td>' . htmlspecialchars($row['nguoilienquan']) . '</td>';
    echo '<td>' . htmlspecialchars($row['to_nhanvien']) . '</td>';
    echo '<td>' . $row['soluong'] . '</td>';
    echo '<td>' . htmlspecialchars($row['mucdich']) . '</td>';
    echo '</tr>';
  }
  echo '</tbody></table>';
} else {
  echo '<div class="text-muted">Không có dữ liệu xuất hoặc nhập kho</div>';
}
?>
