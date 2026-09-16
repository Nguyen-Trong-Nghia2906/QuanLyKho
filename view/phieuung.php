<style>
    .details {
        display: none;
    }

    .pointer {
        cursor: pointer;
    }

    .table-sm th,
    .table-sm td {
        font-size: 14px;
    }

    .details-container {
        padding: 10px;
    }

    .details-title {
        font-weight: bold;
        margin-bottom: 5px;
    }

    @media (max-width: 576px) {

        .table-sm th,
        .table-sm td {
            font-size: 12px;
        }

        .btn {
            font-size: 14px;
        }
    }
</style>
<div class="table-responsive">
    <div class="mb-2 text-end">
        <a href="?v=tao_phieuung" class="btn btn-success mt-2 mt-md-0">+ Thêm phiếu ứng</a>
    </div>
    <table class="table table-bordered ">
        <thead>
            <tr>
                <th class="d-none d-md-table-cell"><input type="date" id="tim_ngayung" class="form-control"></th>

                <th> <input type="text" id="tim_nhanvien_ung" class="form-control" placeholder="Tìm tên người xuất">
                </th>
                <th class="d-none d-md-table-cell"><select id="tim_to_ung" class="form-select">
                        <option value="0"> --- </option>
                        <?php
                        $toList = mysqli_query($conn, "SELECT id, tento FROM to_nhanvien");
                        while ($row = mysqli_fetch_assoc($toList)) {
                            echo "<option value='{$row['id']}'>{$row['tento']}</option>";
                        }
                        ?>
                    </select></th>
                <th class="text-center"><input type="text" id="tim_vattu_ung" class="form-control" placeholder="Tìm tên vật tư"></th>
            </tr>
        </thead>
        <thead class="table-light">
            <tr>

                <th class="d-none d-md-table-cell">Ngày ứng</th>
                <th>Họ tên</th>
                <th class="d-none d-md-table-cell">Tổ</th>
                <th class="text-center">Chi tiết</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // $sql = "SELECT * FROM phieuung WHERE ngayxuat = '".date("Y/m/d")."'";
            $sql = "SELECT * FROM phieuung ORDER BY id DESC";

            $result = mysqli_query($conn, $sql);
            while ($phieuung = mysqli_fetch_assoc($result)) { ?>
                <tr data-id="<?php echo $phieuung['id'] ?>">
                    <td class="d-none d-md-table-cell"><?php echo date('d/m/Y', strtotime($phieuung['ngayung']))  ?></td>
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
        </tbody>
    </table>
</div>

<!-- Modal xác nhận xóa -->
<div class="modal fade" id="modalXacNhanXoa" tabindex="-1" aria-labelledby="xacNhanXoaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="xacNhanXoaLabel">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div id="text_delete" class="modal-body">
                Bạn có chắc chắn muốn xóa phiếu xuất?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="xacNhanXoaPU">Xóa</button>
            </div>
        </div>
    </div>
</div>

<script>
    let element = document.getElementById('phieuung');
    element.classList.add('active');
</script>