<?php
session_start();
include '../model/config.php';
include '../model/ham.php';
$hoten = $_POST['hoten'] ?? '';
$to = $_POST['to'] ?? '0';

$sql = "SELECT *
        FROM nhanvien 
        WHERE 1 = 1 
      ";
if (!empty($hoten)) {
    // Câu lệnh SQL
    $sql .= " AND hoten LIKE '%$hoten%' ";
}

if (!empty($to)) {
    // Câu lệnh SQL
    $sql .= " AND id_to = '$to' ";
}

$sql .= " ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
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
}
?>