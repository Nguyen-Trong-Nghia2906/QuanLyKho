<?php
session_start();
include '../model/config.php';

include '../model/ham.php';

// Lấy dữ liệu từ POST và gán giá trị mặc định nếu bỏ trống
$tento = isset($_POST['tento']) ? $_POST['tento'] : '';

$sql = "INSERT INTO to_nhanvien (tento)
        VALUES ('$tento')";

if (mysqli_query($conn, $sql)) {
    // Sau khi thêm xong, lấy lại dòng mới thêm (nếu muốn)
    $id = mysqli_insert_id($conn);
    $res = mysqli_query($conn, "SELECT * FROM to_nhanvien WHERE id = $id");
    $row = mysqli_fetch_assoc($res);

?>

    <tr data-id="<?php echo $row['id'] ?>">
        <td><?php echo $row['tento'] ?></td>
        <td class="text-nowrap">

            <?php
            if ($_SESSION['quyen'] ==  1) {
            ?>
                <button data-id="<?php echo $row['id']; ?>" class="btn_edit_nv btn btn-sm btn-outline-primary" title="Chi tiết">
                    <i class="bi bi-pencil-square"></i>
                </button>

                <button data-id="<?php echo $row['id'] ?>" class="btn_remove btn btn-sm btn-outline-danger" title="Xóa">
                    <i class="bi bi-trash"></i>
                </button>
            <?php }
            ?>
        </td>

    </tr>
<?php

} else {
    echo "Lỗi: " . mysqli_error($conn);
}
