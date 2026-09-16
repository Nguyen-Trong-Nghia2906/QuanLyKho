<?php
include '../model/config.php';
include '../model/ham.php';
session_start();
// Lấy dữ liệu từ POST và gán giá trị mặc định nếu bỏ trống
$mahang = isset($_POST['mahang']) ? $_POST['mahang'] : '';
$tenhang = isset($_POST['tenhang']) ? $_POST['tenhang'] : '';
$vitri = isset($_POST['vitri']) ? $_POST['vitri'] : '';
$tenkho = isset($_POST['tenkho']) ? $_POST['tenkho'] : '';
$dvt = isset($_POST['dvt']) ? $_POST['dvt'] : '';
$soluong = 0; // Mặc định số lượng ban đầu là 0
$giatri = isset($_POST['giatri']) && $_POST['giatri'] !== '' ? $_POST['giatri'] : 'NULL';

// Thoát dữ liệu để tránh lỗi SQL injection (tối thiểu)
$mahang = mysqli_real_escape_string($conn, $mahang);
$tenhang = mysqli_real_escape_string($conn, $tenhang);
$vitri = mysqli_real_escape_string($conn, $vitri);
$tenkho = mysqli_real_escape_string($conn, $tenkho);
$dvt = mysqli_real_escape_string($conn, $dvt);

// Tạo câu truy vấn (giá trị rỗng vẫn được thêm)
$sql = "INSERT INTO vattu (mahang, tenhang, vitri, tenkho, dvt, soluong, giatri)
        VALUES ('$mahang', '$tenhang', '$vitri', '$tenkho', '$dvt', $soluong, $giatri)";

if (mysqli_query($conn, $sql)) {
    // Sau khi thêm xong, lấy lại dòng mới thêm (nếu muốn)
    $id = mysqli_insert_id($conn);
    $res = mysqli_query($conn, "SELECT * FROM vattu WHERE id = $id");
    $row = mysqli_fetch_assoc($res);

    //Thêm mới vào tồn đầu kỳ
    $sql = "INSERT INTO ton_dauky (id_vattu, soluong)
        VALUES ('$id', 0)";
    mysqli_query($conn, $sql);
    ?>
    <tr data-id="<?php echo $row['id']; ?>">


        <td><?php echo $row['tenhang']; ?></td>
        <td><?php echo $row['vitri'] ?? 'Chưa cập nhật'; ?></td>
        <td><?php echo $row['dvt']; ?></td>
        <td><?php echo $row['soluong']; ?></td>
        <td><?php echo $row['tenkho']; ?></td>

        <td class="text-nowrap">
            <?php
            if ($_SESSION['quyen'] == 1) {
                ?>
                <button data-id="<?php echo $row['id'] ?>" data-bs-toggle="modal" data-bs-target="#lichSuXuatModal"
                    class="btn_lichsu btn btn-sm btn-outline-success" title="Lịch sử">
                    <i class="bi bi-clock-history"></i>
                </button>

                <a href="?v=chitiet_vattu&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary" title="Chi tiết">
                    <i class="bi bi-pencil-square"></i>
                </a>

                <button data-id="<?php echo $row['id'] ?>" class="btn_remove btn btn-sm btn-outline-danger" title="Xóa">
                    <i class="bi bi-trash"></i>
                </button>
                <?php
            }
            ?>

        </td>

    </tr>
<?php } else {
    echo "Lỗi: " . mysqli_error($conn);
}
