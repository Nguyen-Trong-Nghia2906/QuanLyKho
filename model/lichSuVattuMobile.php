<?php
include '../model/config.php';
include '../model/ham.php';
$id = intval($_POST['id_vattu'] ?? 0);

$sql = "
  SELECT 
    px.maphieu AS maphieu,
    px.ngayxuat AS ngay,
    nv.hoten AS nguoilienquan,
    t.tento AS to_nhanvien,
    ct.soluong,
    ct.mucdich,
    'Xuất kho' AS loaiphieu
  FROM chitiet_phieuxuat ct
  JOIN phieuxuat px ON ct.id_phieuxuat = px.id
  JOIN nhanvien nv ON px.id_nhanvien = nv.id
  JOIN to_nhanvien t ON nv.id_to = t.id
  WHERE ct.id_vattu = $id

  UNION ALL

  SELECT 
    '' AS maphieu,
    pn.ngaynhap AS ngay,
    pn.nhacungcap AS nguoilienquan,
    '' AS to_nhanvien,
    ct.soluong,
    '' AS mucdich,
    'Nhập kho' AS loaiphieu
  FROM chitiet_phieunhap ct
  JOIN phieunhap pn ON ct.id_phieunhap = pn.id
  WHERE ct.id_vattu = $id

  UNION ALL

   SELECT 
    '' AS maphieu,
    pu.ngayung AS ngay,
    nv.hoten AS nguoilienquan,
    t.tento AS to_nhanvien,
    ct.soluong,
    ct.ghichu as mucdich,
    'Phiếu ứng' AS loaiphieu
  FROM chitiet_phieuung ct
  JOIN phieuung pu ON ct.id_phieuung = pu.id
  JOIN nhanvien nv ON pu.id_nhanvien = nv.id
  JOIN to_nhanvien t ON nv.id_to = t.id
  WHERE ct.id_vattu = $id


  ORDER BY ngay DESC
";


$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0): ?>
    <div class="list-group">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class=" list-group-item py-2">
                <div style="display: flex; justify-content: space-between;" >
                    <div class="">
                        <!-- Loại phiếu + mã phiếu -->
                        <div class="fw-bold">
                            <?= htmlspecialchars($row['loaiphieu']) ?>
                            <?php if ($row['maphieu']): ?>
                                (<?= htmlspecialchars($row['maphieu']) ?>)
                            <?php endif; ?>
                        </div>

                        <!-- Ngày + số lượng -->
                        <div class="d-flex justify-content-between small text-muted">
                            <span><?= date('d/m/Y', strtotime($row['ngay'])) ?></span>

                        </div>

                        <!-- Người liên quan -->
                        <div class="small text-muted mt-1">
                            <?= htmlspecialchars($row['nguoilienquan']) ?>
                        </div>

                        <!-- Mục đích -->
                        <?php if (!empty($row['mucdich'])): ?>
                            <div class="small fst-italic text-secondary">
                                <?= htmlspecialchars($row['mucdich']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="">
                        <span style="color:#28a745" ><?php echo $row['soluong'] ?></span>
                        <span><?php echo getDvtVT($conn, $id) ?></span>
                    </div>
                </div>
            </div>

        <?php endwhile; ?>
    </div>
<?php else: ?>
    <div class="text-center text-muted">Không có dữ liệu nhập/xuất kho</div>
<?php endif; ?>