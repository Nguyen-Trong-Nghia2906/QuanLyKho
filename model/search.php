<?php
include '../model/config.php';
$keyword = $_POST['keyword'] ?? '';
$column = $_POST['column'] ?? 'tenhang';

// Chuẩn bị và lọc keyword
$keyword = mysqli_real_escape_string($conn, $keyword);
$column = in_array($column, ['tenhang', 'vitri', 'tenkho']) ? $column : 'tenhang';

if (!empty($keyword)) {
  // Câu lệnh SQL
  $sql = "SELECT *
        FROM vattu 
        WHERE $column LIKE '%$keyword%' 
        ORDER BY id DESC ";

  $result = mysqli_query($conn, $sql);

  $data = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
  }
  echo json_encode($data);

}

else{
  $result = mysqli_query($conn, "SELECT * FROM vattu ORDER BY id DESC LIMIT 100");
  $data = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
  }
  echo json_encode($data);
}
