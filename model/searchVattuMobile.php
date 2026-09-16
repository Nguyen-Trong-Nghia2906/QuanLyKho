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
          <a style="color:#000; text-decoration:none" href="?v=chitiet_vattu&id=<?php echo $row['id']; ?>">
      
      
            <div data-id="<?php echo $row['id'] ?>"  class="card-vattu">
      
              <div class="info">
                <div class="name"><?php echo $row['tenhang']; ?></div>
                <div class="location"><?php echo $row['vitri'] ?? 'Chưa cập nhật'; ?></div>
              </div>
              <div class="qty"><?php echo $row['soluong'] . "  " . $row['dvt']; ?></div>
            </div>
          </a>
    <?php
    }
} else {
    $result = mysqli_query($conn, "SELECT * FROM vattu ORDER BY id DESC LIMIT 100");

    while ($row = mysqli_fetch_assoc($result)) {
        ?>
          <a style="color:#000; text-decoration:none" href="?v=chitiet_vattu&id=<?php echo $row['id']; ?>">
      
      
            <div data-id="<?php echo $row['id'] ?>"  class="card-vattu">
      
              <div class="info">
                <div class="name"><?php echo $row['tenhang']; ?></div>
                <div class="location"><?php echo $row['vitri'] ?? 'Chưa cập nhật'; ?></div>
              </div>
              <div class="qty"><?php echo $row['soluong'] . "  " . $row['dvt']; ?></div>
            </div>
          </a>
<?php
    }
}
