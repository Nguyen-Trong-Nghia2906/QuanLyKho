<?php
session_start();
require '../model/config.php';
require '../model/ham.php';
// Nhận dữ liệu từ Ajax
$id_to = $_POST['id_to'] ?? '';
$maphieu = $_POST['maphieu'] ?? '';
$ten_nv = $_POST['ten_nv'] ?? '';
$ngayxuat = $_POST['ngayxuat'] ?? '';
$ten_vt = $_POST['ten_vt'] ?? '';

// Bắt đầu truy vấn phiếu xuất
$sql = "SELECT 
    px.*, 
    nv.hoten,
    to_nhanvien.tento
FROM phieuxuat px
JOIN nhanvien nv ON px.id_nhanvien = nv.id
JOIN to_nhanvien ON nv.id_to = to_nhanvien.id
WHERE 1=1";

// Thêm điều kiện nếu có
if (!empty($id_to)) {
    $sql .= " AND to_nhanvien.id = '$id_to'";
}

if (!empty($maphieu)) {
    $sql .= " AND px.maphieu LIKE '%$maphieu%'";
}

if (!empty($ten_nv)) {
    $sql .= " AND nv.hoten LIKE '%$ten_nv%'";
}

if (!empty($ngayxuat)) {
    $sql .= " AND px.ngayxuat = '$ngayxuat'";
}

// Nếu có lọc theo tên vật tư thì phải kiểm tra bảng chi tiết
if (!empty($ten_vt)) {
    $sql .= " AND px.id IN (
                SELECT DISTINCT id_phieuxuat FROM chitiet_phieuxuat 
                WHERE id_vattu IN (
                    SELECT id FROM vattu WHERE tenhang LIKE '%$ten_vt%'
                )
             )";
}

$sql .= " ORDER BY px.ngayxuat DESC, px.id DESC";
$result = mysqli_query($conn, $sql);
?>


<?php

while ($phieuxuat = mysqli_fetch_assoc($result)) { ?>
    <tr data-id="<?php echo $phieuxuat['id'] ?>">
        <td><?php echo $phieuxuat['maphieu'] ?></td>
        <td><?php echo $phieuxuat['hoten'] ?></td>
        <td class="d-none d-md-table-cell"><?php echo $phieuxuat['tento'] ?></td>
        <td class="d-none d-md-table-cell"><?php echo date('d/m/Y', strtotime($phieuxuat['ngayxuat']))  ?></td>
        <td><?php echo $phieuxuat['ghichu'] ?></td>
        <td class="text-center pointer text-primary open-details">▶</td>
    </tr>
    <tr class="details">
        <td colspan="6" style="background-color: #f1f1f1;">
            <div class="details-container">
                <div class="details-title">Danh sách vật tư</div>
                <div class="table-responsive">
                    <table class="table mb-0 details-table ">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Mã VT</th>
                                <th>Tên VT</th>
                                <th>ĐVT</th>
                                <th>Số lượng</th>
                                <th>Mục đích</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql2 = "
                            SELECT 
                                ct.*, 
                                vt.mahang, 
                                vt.tenhang, 
                                vt.dvt
                            FROM chitiet_phieuxuat ct
                            JOIN vattu vt ON ct.id_vattu = vt.id
                            WHERE ct.id_phieuxuat = '" . $phieuxuat['id'] . "'
                            ";
                           
                            $result2 = mysqli_query($conn, $sql2);
                            $stt = 1;
                            while ($chitiet = mysqli_fetch_assoc($result2)) { ?>
                                <tr>
                                    <td><?php echo $stt;
                                        $stt++; ?></td>
                                    <td><?php echo $chitiet['mahang'] ?></td>
                                    <td><?php echo $chitiet['tenhang'] ?></td>
                                    <td><?php echo $chitiet['dvt'] ?> </td>
                                    <td><?php echo $chitiet['soluong'] ?></td>
                                    <td><?php echo $chitiet['mucdich'] ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-2 text-end">
                    <?php if ($_SESSION['quyen'] == 1) {
                    ?>
                        <a href="?v=sua_phieuxuat&id_px=<?php echo $phieuxuat['id'] ?>" class="btn btn-sm btn-warning px-3">
                            Chỉnh sửa
                        </a>
                        <button data-id="<?php echo $phieuxuat['id'] ?>" class="xoa_phieuxuat btn btn-sm btn-danger px-3">
                            Xóa phiếu
                        </button>

                    <?php } ?>
                </div>
            </div>
        </td>
    </tr>
<?php } ?>