<?php
session_start();
require '../model/config.php';
require '../model/ham.php';
// Nhận dữ liệu từ Ajax
$id_to = $_POST['id_to'] ?? '';
$maphieu = $_POST['maphieu'] ?? '';
$sophieu = $_POST['sophieu'] ?? '';
$ten_vt = $_POST['ten_vt'] ?? '';

// Bắt đầu truy vấn phiếu xuất
$sql = "SELECT 
    pxk.id as idpxk,
    pxk.maphieu as sophieu,
    px.maphieu as maphieu,
    pxk.id_phieuxuat as id_phieuxuat, 
    nv.hoten,
    to_nhanvien.tento
FROM phieuxuat_kho pxk
LEFT JOIN phieuxuat px ON px.id = pxk.id_phieuxuat
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

if (!empty($sophieu)) {
    $sql .= " AND pxk.maphieu LIKE '%$sophieu%'";
}

// Nếu có lọc theo tên vật tư thì phải kiểm tra bảng chi tiết
if (!empty($ten_vt)) {
    $sql .= " AND pxk.id IN (
                SELECT DISTINCT id_phieuxuatkho FROM chitiet_phieuxuatkho 
                WHERE id_vattu IN (
                    SELECT id FROM vattu WHERE tenhang LIKE '%$ten_vt%'
                )
             )";
}

$sql .= " ORDER BY pxk.id  DESC   LIMIT 20 ";
$result = mysqli_query($conn, $sql);
?>


<?php

while ($phieuxuat = mysqli_fetch_assoc($result)) { ?>
    <tr data-id="<?php echo $phieuxuat['idpxk'] ?>">
        <td><?php echo $phieuxuat['sophieu'] ?></td>
        <td><?php echo $phieuxuat['maphieu'] ?></td>
        <td class="d-none d-md-table-cell"><?php echo getTo($conn, $phieuxuat['id_phieuxuat']) ?></td>
        <td class="d-none d-md-table-cell"><?php echo getTenNV($conn, $phieuxuat['id_phieuxuat']) ?></td>
        <td class="text-center pointer text-primary open-details">▶</td>
    </tr>
    <tr class="details">
        <td colspan="7" style="background-color: #f1f1f1;">
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
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql2 = "SELECT * FROM chitiet_phieuxuatkho WHERE id_phieuxuatkho = '" . $phieuxuat['idpxk'] . "'";
                            $result2 = mysqli_query($conn, $sql2);
                            $stt = 1;
                            while ($chitiet = mysqli_fetch_assoc($result2)) { ?>
                                <tr>
                                    <td><?php echo $stt;
                                    $stt++; ?></td>
                                    <td><?php echo getMaVT($conn, $chitiet['id_vattu']) ?></td>
                                    <td><?php echo getTenVT($conn, $chitiet['id_vattu']) ?></td>
                                    <td><?php echo getDvtVT($conn, $chitiet['id_vattu']) ?></td>
                                    <td><?php echo $chitiet['soluong'] ?></td>

                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-2 text-end">
                    <?php if ($_SESSION['quyen'] == 1) {
                        ?>
                        <a href="?v=sua_phieuxuatkho&id_px=<?php echo $phieuxuat['idpxk'] ?>" class="btn btn-sm btn-warning px-3">
                            Chỉnh sửa
                        </a>
                        <button data-id="<?php echo $phieuxuat['idpxk'] ?>" data-idPX="<?php echo $phieuxuat['id_phieuxuat'] ?>"
                            class="xoa_phieuxuatkho btn btn-sm btn-danger px-3">
                            Xóa phiếu
                        </button>

                    <?php } ?>
                </div>
            </div>
        </td>
    </tr>
<?php } ?>