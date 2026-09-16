<?php
include '../model/config.php';
include '../model/ham.php';
$id = intval($_POST['id_vattu'] ?? 0);

$sql = "
    SELECT 
    tonv.id AS id_to,
    tonv.tento,
    COALESCE(px_sum.tong_px, 0) AS tong_px,
    COALESCE(pu_sum.tong_pu, 0) AS tong_pu,
    COALESCE(px_sum.tong_px, 0) + COALESCE(pu_sum.tong_pu, 0) AS tong_slnhan
FROM to_nhanvien tonv

LEFT JOIN (
    SELECT 
        nv.id_to,
        SUM(ct.soluong) AS tong_px
    FROM phieuxuat px
    JOIN chitiet_phieuxuat ct ON px.id = ct.id_phieuxuat
    JOIN nhanvien nv ON px.id_nhanvien = nv.id
    WHERE ct.id_vattu = $id
    GROUP BY nv.id_to
) AS px_sum ON px_sum.id_to = tonv.id

LEFT JOIN (
    SELECT 
        nv.id_to,
        SUM(ctpu.soluong) AS tong_pu
    FROM phieuung pu
    JOIN chitiet_phieuung ctpu ON pu.id = ctpu.id_phieuung
    JOIN nhanvien nv ON pu.id_nhanvien = nv.id
    WHERE ctpu.id_vattu = $id
    GROUP BY nv.id_to
) AS pu_sum ON pu_sum.id_to = tonv.id

-- 🔥 Chỉ lấy tổ có số lượng PX hoặc PU
WHERE 
    COALESCE(px_sum.tong_px, 0) > 0
    OR COALESCE(pu_sum.tong_pu, 0) > 0;
";
// echo $sql;
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {

    echo '<table class="table table-bordered table-sm">';
    echo '<thead class="table-secondary">';

    echo '<tr>';
        echo '<td></td>';
    // ===== HÀNG 1: Tên tổ =====
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<th class="text-center">' . htmlspecialchars($row['tento']) . '</th>';
    }
    echo '</tr></thead><tbody>';

    // ===== RESET KẾT QUẢ =====
    mysqli_data_seek($result, 0);

    // ===== HÀNG 2: Tổng số lượng =====
    echo '<tr>';
    echo '<td>Tổng</td>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<td class="text-center fw-bold">' . $row['tong_slnhan'] . '</td>';
    }
    echo '</tr>';

    // ===== RESET KẾT QUẢ =====
    mysqli_data_seek($result, 0);

    // ===== HÀNG 3: Phiếu Xuất (màu xanh) =====
    echo '<tr>';
    echo '<td>Phiếu xuất</td>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<td class="text-center" style="color: green;">' . $row['tong_px'] . '</td>';
    }
    echo '</tr>';

    // ===== RESET KẾT QUẢ =====
    mysqli_data_seek($result, 0);

    // ===== HÀNG 4: Phiếu Ứng (màu đỏ) =====
    echo '<tr>';
    echo '<td>Phiếu ứng</td>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<td class="text-center" style="color: red;">' . $row['tong_pu'] . '</td>';
    }
    echo '</tr>';

    echo '</tbody></table>';

} else {
    echo '<div class="text-muted">Không có dữ liệu xuất hoặc nhập kho</div>';
}
?>

