<?php
session_start();
include '../model/config.php';

include '../model/ham.php';

// Lấy dữ liệu từ POST và gán giá trị mặc định nếu bỏ trống
$hoten = isset($_POST['hoten']) ? $_POST['hoten'] : '';
$to_nhanvien = isset($_POST['to_nhanvien']) ? $_POST['to_nhanvien'] : '';
$sdt = isset($_POST['sdt']) ? $_POST['sdt'] : NULL;

// Thoát dữ liệu để tránh lỗi SQL injection (tối thiểu)
$hoten = mysqli_real_escape_string($conn, $hoten);
$to_nhanvien = mysqli_real_escape_string($conn, $to_nhanvien);
$sdt = mysqli_real_escape_string($conn, $sdt);

// Tạo câu truy vấn (giá trị rỗng vẫn được thêm)
$sql = "INSERT INTO nhanvien (hoten, id_to, sdt)
        VALUES ('$hoten', '$to_nhanvien', '$sdt')";

if (mysqli_query($conn, $sql)) {
    // Sau khi thêm xong, lấy lại dòng mới thêm (nếu muốn)
    $id = mysqli_insert_id($conn);
    $res = mysqli_query($conn, "SELECT nv.*, tento FROM nhanvien nv JOIN to_nhanvien to_nc ON nv.id_to = to_nc.id WHERE nv.id = $id");
    $row = mysqli_fetch_assoc($res);

?>

    <tr data-id="<?php echo $row['id'] ?>">
        <td><?php echo $row['hoten'] ?></td>
        <td class="d-none d-md-table-cell"><?php echo getNameTo($conn, $row['id']) ?></td>
        <td class="d-none d-md-table-cell"><?php echo $row['sdt'] ?></td>
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
