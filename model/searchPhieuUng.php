<?php
require '../model/config.php';
require '../model/ham.php';
session_start();
// Nhận dữ liệu từ Ajax
$id_to = $_POST['id_to'] ?? '';
$ten_nv = $_POST['ten_nv'] ?? '';
$ngayxuat = $_POST['ngayxuat'] ?? '';
$ten_vt = $_POST['ten_vt'] ?? '';

// Bắt đầu truy vấn phiếu xuất
$sql = "SELECT px.*, nv.hoten, to_nhanvien.tento 
        FROM phieuung px
        JOIN nhanvien nv ON px.id_nhanvien = nv.id
        JOIN to_nhanvien ON nv.id_to = to_nhanvien.id
        WHERE 1=1";

// Thêm điều kiện nếu có
if (!empty($id_to)) {
    $sql .= " AND to_nhanvien.id = '$id_to'";
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
                SELECT DISTINCT id_phieuung FROM chitiet_phieuung 
                WHERE id_vattu IN (
                    SELECT id FROM vattu WHERE tenhang LIKE '%$ten_vt%'
                )
             )";
}

$sql .= " ORDER BY px.ngayung DESC, px.id DESC";
$result = mysqli_query($conn, $sql);
?>


<?php

while ($phieuung = mysqli_fetch_assoc($result)) { ?>
    <tr data-id="<?php echo $phieuung['id'] ?>">
        <td class="d-none d-md-table-cell"><?php echo date('d/m/Y', strtotime($phieuung['ngayung'])) ?></td>
        <td><?php echo getNameNV($conn, $phieuung['id_nhanvien']) ?></td>
        <td class="d-none d-md-table-cell"><?php echo getNameTo($conn, $phieuung['id_nhanvien']) ?></td>
        <td class="text-center pointer text-primary open-details">▶</td>
    </tr>
    <tr class="details">
        <td colspan="5" style="background-color: #f1f1f1;">
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
                                <th>Ghi chú</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql2 = "SELECT * FROM chitiet_phieuung WHERE id_phieuung = '" . $phieuung['id'] . "'";
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
                                    <td><?php echo $chitiet['ghichu'] ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-2 text-end">
                    <?php if ($_SESSION['quyen'] == 1) {
                    ?>
                    <a href="?v=sua_phieuung&id_pu=<?php echo $phieuung['id'] ?>" class="btn btn-sm btn-warning px-3">
                                        Chỉnh sửa
                                    </a>
                        <button data-id="<?php echo $phieuung['id'] ?>" class="xoa_phieuung btn btn-sm btn-danger px-3">
                            Xóa phiếu
                        </button>

                    <?php } ?>
                </div>
            </div>
        </td>
    </tr>
<?php } ?>