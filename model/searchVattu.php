<?php
session_start();
include '../model/config.php';
include '../model/ham.php';
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

  
  while ($row = mysqli_fetch_assoc($result)) {
?>
    <tr data-id="<?php echo $row['id']; ?>">

      <td><?php echo $row['mahang']; ?></td>
      <td><?php echo $row['tenhang']; ?></td>
      <td><?php echo $row['vitri'] ?? 'Chưa cập nhật'; ?></td>
      <td><?php echo $row['dvt']; ?></td>
      <td><?php echo $row['soluong']; ?></td>
      <td><?php echo $row['tenkho']; ?></td>

      <td class="text-nowrap">
            <button data-id="<?php echo $row['id'] ?>" data-bs-toggle="modal" data-bs-target="#lichSuXuatModal"
              class="btn_lichsu btn btn-sm btn-outline-success" title="Lịch sử">
              <i class="bi bi-clock-history"></i>
            </button>

            <a href="?v=chitiet_vattu&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary"
              title="Chi tiết">
              <i class="bi bi-pencil-square"></i>
            </a>

            <?php
            if ($_SESSION['quyen'] == 1) {
              ?>


              <button data-id="<?php echo $row['id'] ?>" data-bs-toggle="modal" data-bs-target="#thongKeNhaMayModal"
                class="btn_thongke btn btn-sm btn-outline-warning" title="Nhà máy">
                <i class="bi bi-house-gear"></i>
              </button>
              <button data-id="<?php echo $row['id'] ?>" class="btn_remove btn btn-sm btn-outline-danger" title="Xóa">
                <i class="bi bi-trash"></i>
              </button>
              <?php
            }
            ?>

          </td>

    </tr>

  <?php
  }

} else {
  $result = mysqli_query($conn, "SELECT * FROM vattu ORDER BY id DESC LIMIT 100");
  
  while ($row = mysqli_fetch_assoc($result)) {
  ?>
    <tr data-id="<?php echo $row['id']; ?>">

      <td><?php echo $row['mahang']; ?></td>

      <td><?php echo $row['tenhang']; ?></td>
      <td><?php echo $row['vitri'] ?? 'Chưa cập nhật'; ?></td>
      <td><?php echo $row['dvt']; ?></td>
      <td><?php echo $row['soluong']; ?></td>
      <td><?php echo $row['tenkho']; ?></td>

      <td class="text-nowrap">
            <button data-id="<?php echo $row['id'] ?>" data-bs-toggle="modal" data-bs-target="#lichSuXuatModal"
              class="btn_lichsu btn btn-sm btn-outline-success" title="Lịch sử">
              <i class="bi bi-clock-history"></i>
            </button>

            <a href="?v=chitiet_vattu&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary"
              title="Chi tiết">
              <i class="bi bi-pencil-square"></i>
            </a>

            <?php
            if ($_SESSION['quyen'] == 1) {
              ?>


              <button data-id="<?php echo $row['id'] ?>" data-bs-toggle="modal" data-bs-target="#thongKeNhaMayModal"
                class="btn_thongke btn btn-sm btn-outline-warning" title="Nhà máy">
                <i class="bi bi-house-gear"></i>
              </button>
              <button data-id="<?php echo $row['id'] ?>" class="btn_remove btn btn-sm btn-outline-danger" title="Xóa">
                <i class="bi bi-trash"></i>
              </button>
              <?php
            }
            ?>

          </td>

    </tr>

<?php
  }
}
