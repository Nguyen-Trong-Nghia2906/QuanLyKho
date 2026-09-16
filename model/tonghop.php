<?php
session_start();
require '../model/config.php';

$tenkho = $_POST['tenkho'] ?? '';
$tenvt = $_POST['tenvt'] ?? '';
$date_start = $_POST['date_start'] ?? '';
$date_end = $_POST['date_end'] ?? '';
$mode = $_POST['mode'] ?? '';
if ($mode == 'excel') {
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=tong_hop_nhap_xuat_ton.xls");
}


// ===== WHERE chính =====
$where = " WHERE 1=1 ";

// lọc kho
if ($tenkho != '' && $tenkho != '1') {
    $where .= " AND vt.tenkho = '$tenkho' ";
}

// lọc tên vật tư
if (!empty($tenvt)) {
    $where .= " AND vt.tenhang LIKE '%$tenvt%' ";
}

// ===== điều kiện ngày =====
$where_date_nhap = "";
$where_date_xuat = "";
$where_date_ung = "";

if (!empty($date_start) && !empty($date_end)) {
    $where_date_nhap = " AND pn.ngaynhap BETWEEN '$date_start' AND '$date_end' ";
    $where_date_xuat = " AND px.ngayxuat BETWEEN '$date_start' AND '$date_end' ";
    $where_date_ung = " AND pu.ngayung BETWEEN '$date_start' AND '$date_end' ";
}

// ===== SQL =====
$sql = "
SELECT 
    vt.id,
    vt.mahang,
    vt.tenhang,
    vt.dvt,
    vt.tenkho,

    COALESCE(vt.soluong, 0) AS so_luong_hien_tai,
    COALESCE(nhap.tong_nhap, 0) AS tong_nhap,
    COALESCE(xuat.tong_xuat, 0) AS tong_xuat,
    COALESCE(ung.tong_ung, 0) AS tong_ung

FROM vattu vt

-- nhập
LEFT JOIN (
    SELECT 
        ctn.id_vattu,
        SUM(ctn.soluong) AS tong_nhap
    FROM chitiet_phieunhap ctn
    JOIN phieunhap pn ON pn.id = ctn.id_phieunhap
    WHERE 1=1 $where_date_nhap
    GROUP BY ctn.id_vattu
) nhap ON vt.id = nhap.id_vattu

-- xuất
LEFT JOIN (
    SELECT 
        ctx.id_vattu,
        SUM(ctx.soluong) AS tong_xuat
    FROM chitiet_phieuxuat ctx
    JOIN phieuxuat px ON px.id = ctx.id_phieuxuat
    WHERE 1=1 $where_date_xuat
    GROUP BY ctx.id_vattu
) xuat ON vt.id = xuat.id_vattu

-- ứng
LEFT JOIN (
    SELECT 
        cu.id_vattu,
        SUM(cu.soluong) AS tong_ung
    FROM chitiet_phieuung cu
    JOIN phieuung pu ON pu.id = cu.id_phieuung
    WHERE 1=1 $where_date_ung
    GROUP BY cu.id_vattu
) ung ON vt.id = ung.id_vattu

$where

ORDER BY vt.mahang
";

$result = mysqli_query($conn, $sql);

if ($mode == 'excel') {

    echo "<meta charset='UTF-8'>";

    // ===== TIÊU ĐỀ =====
    echo "<h2 style='text-align:center;'>TỔNG HỢP NHẬP XUẤT TỒN</h2>";

    // ===== NGÀY =====
    if (!empty($date_start) && !empty($date_end)) {
        echo "<p style='text-align:center;'>Từ ngày: <b>$date_start</b> đến ngày: <b>$date_end</b></p>";
    } else {
        echo "";
    }
    echo "<table border='1'>
    <tr>
        <th>Mã VT</th>
        <th>Tên VT</th>
        <th>ĐVT</th>
        <th>Nhập</th>
        <th>Xuất</th>
        <th>Ứng</th>
        <th>Tồn</th>
        <th>Kho</th>
    </tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
            <td>{$row['mahang']}</td>
            <td>{$row['tenhang']}</td>
            <td>{$row['dvt']}</td>
            <td>{$row['tong_nhap']}</td>
            <td>{$row['tong_xuat']}</td>
            <td>{$row['tong_ung']}</td>
            <td>{$row['so_luong_hien_tai']}</td>
            <td>{$row['tenkho']}</td>
        </tr>";
    }

    echo "</table>";
    exit;
}
// ===== render =====
while ($row = mysqli_fetch_assoc($result)) {
    ?>
    <tr data-id="<?php echo $row['id']; ?>">

        <td><?php echo $row['mahang']; ?></td>
        <td><?php echo $row['tenhang']; ?></td>
        <td><?php echo $row['dvt']; ?></td>
        <td class="text-success"><?php echo $row['tong_nhap']; ?></td>
        <td class="text-warning"><?php echo $row['tong_xuat']; ?></td>
        <td class="text-danger"><?php echo $row['tong_ung']; ?></td>
        <td>
            <?php echo $row['so_luong_hien_tai']; ?>
        </td>
        <td><?php echo $row['tenkho']; ?></td>
        <td class="text-nowrap">
            <button data-id="<?php echo $row['id'] ?>" data-bs-toggle="modal" data-bs-target="#lichSuXuatModal"
                class="btn_lichsu btn btn-sm btn-outline-success" title="Lịch sử">
                <i class="bi bi-clock-history"></i>
            </button>
            <button data-id="<?php echo $row['id'] ?>" data-bs-toggle="modal" data-bs-target="#thongKeNhaMayModal"
                class="btn_thongke btn btn-sm btn-outline-warning" title="Nhà máy">
                <i class="bi bi-house-gear"></i>
            </button>
            <?php
            if ($_SESSION['quyen'] == 1) {
                ?>


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
<?php }



?>