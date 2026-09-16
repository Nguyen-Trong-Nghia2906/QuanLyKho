<?php
include '../model/config.php'; // Kết nối CSDL

if (isset($_POST['id'])) {
  $id = intval($_POST['id']);
  $sql = "DELETE FROM vattu WHERE id = $id";
  if (mysqli_query($conn, $sql)) {
    echo 'success';
  } else {
    echo 'fail';
  }
}
?>