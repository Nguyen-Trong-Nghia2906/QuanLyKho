<?php
require '../model/config.php';
require '../model/ham.php';
session_start();
// Nhận dữ liệu từ Ajax

$ten_ncc = $_POST['ten_ncc'] ?? '';
$ngaynhap = $_POST['ngaynhap'] ?? '';
$ten_vt = $_POST['ten_vt'] ?? '';

// Bắt đầu truy vấn phiếu xuất
$sql = "SELECT px.*
        FROM phieunhap px
        WHERE 1 = 1 ";

// Thêm điều kiện nếu có
$check = 1;
if (!empty($ngaynhap)) {
    $sql .= " AND px.ngaynhap = '$ngaynhap'";
    $check = 0;
}

// Nếu có lọc theo tên vật tư thì phải kiểm tra bảng chi tiết
if (!empty($ten_ncc)) {
    $sql .= " AND nhacungcap LIKE '%$ten_ncc%'";
    $check = 0;
}

if (!empty($ten_vt)) {
    $sql .= " AND px.id IN (
                SELECT DISTINCT id_phieunhap FROM chitiet_phieunhap 
                WHERE id_vattu IN (
                    SELECT id FROM vattu WHERE tenhang LIKE '%$ten_vt%'
                )
             )";
    $check = 0;
}

// if($check){
//     $sql .= " AND ngaynhap = '".date("Y/m/d")."'";
// }
$sql .= " ORDER BY px.ngaynhap DESC, px.id DESC";
$result = mysqli_query($conn, $sql);
?>


<?php

while ($phieunhap = mysqli_fetch_assoc($result)) { ?>
    <tr data-id="<?php echo $phieunhap['id'] ?>">
        <td><?php echo date('d/m/Y', strtotime($phieunhap['ngaynhap'])) ?></td>
        <td class="d-none d-md-table-cell"><?php echo $phieunhap['nhacungcap'] ?></td>
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
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql2 = "SELECT * FROM chitiet_phieunhap WHERE id_phieunhap = '" . $phieunhap['id'] . "'";
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
                    <?php
                    if ($_SESSION['quyen'] == 1) { ?>
                        <a href="?v=sua_phieunhap&id_pn=<?php echo $phieunhap['id'] ?>" class="btn btn-sm btn-warning px-3">
                                        Chỉnh sửa
                                    </a>
                        <button data-id="<?php echo $phieunhap['id'] ?>" class="xoa_phieunhap btn btn-sm btn-danger px-3">
                            Xóa phiếu
                        </button>
                    <?php  }
                    ?>
                </div>
            </div>
        </td>
    </tr>
<?php } ?>