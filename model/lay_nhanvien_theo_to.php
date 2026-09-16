<?php
include '../model/config.php';

if (isset($_GET['id_to'])) {
  $idTo = intval($_GET['id_to']);
  $result = mysqli_query($conn, "SELECT id, hoten FROM nhanvien WHERE id_to = $idTo");

  $data = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
  }

  echo json_encode($data);
}
?>
