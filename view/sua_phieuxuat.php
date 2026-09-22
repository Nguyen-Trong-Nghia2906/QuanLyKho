<?php
if ($_SESSION['quyen'] == 1) {

    $id = $_GET['id_px'];
    $sql = "SELECT * FROM  phieuxuat  WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $px = mysqli_fetch_assoc($result);
    ?>
    <style>
        #goi-y-vattu {
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #ccc;
            border-radius: 6px;
            background-color: #fff;
            z-index: 1000;
            position: absolute;
            width: 100%;
        }

        .chon-vattu {
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
        }

        .chon-vattu:hover {
            background-color: #e6f7ff;
        }

        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
            }

            table th,
            table td {
                white-space: nowrap;
            }
        }
    </style>
    <!-- Modal Thêm mới -->
    <div class="modal fade" id="modalThemMoiPXK" tabindex="-1" aria-labelledby="modalThemMoiLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="form-them-moi-PXK">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalThemMoiLabel">Thêm phiếu xuất kế toán</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">
                        <div id="dsThemMoiPX" class="row g-3">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Lưu</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Lịch sử phiếu xuất kho -->
    <div class="modal fade" id="modalLichSuPXK" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">📜 Lịch sử vật tư</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="dsLichSuPX">
                    Đang tải dữ liệu...
                </div>
            </div>
        </div>
    </div>


    <h4 class="mb-4">Chỉnh Sửa Phiếu Xuất Vật Tư</h4>

    <form id="form-sua-phieu-xuat">
        <div class="row g-3 mb-3">
            <div class="col-md-3 col-6">
                <label class="form-label">Số phiếu</label>
                <input type="hidden" name="id_px" value="<?php echo $px['id'] ?>">
                <input type="text" value="<?php echo $px['maphieu'] ?>" name="so_phieu" class="form-control" required>
            </div>
            <div class="col-md-3 col-6">
                <label class="form-label">Ngày</label>
                <input type="date" value="<?php echo $px['ngayxuat'] ?>" name="ngay_xuat" class="form-control">
            </div>
            <div class="col-md-3 col-6">
                <label class="form-label">Tổ</label>
                <select name="to" class="form-select">
                    <option value="0"> --- </option>
                    <?php
                    $toList = mysqli_query($conn, "SELECT id, tento FROM to_nhanvien");
                    while ($to_nv = mysqli_fetch_assoc($toList)) {
                        if (getToNV($conn, $px['id_nhanvien']) == $to_nv['id']) {

                            echo "<option selected value='{$to_nv['id']}'>{$to_nv['tento']}</option>";
                        } else {
                            echo "<option value='{$to_nv['id']}'>{$to_nv['tento']}</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-3 col-6">
                <label class="form-label">Người đề nghị</label>
                <select name="nguoi_de_nghi" class="form-select">
                    <option value="<?php echo $px['id_nhanvien'] ?>"> <?php echo getNameNV($conn, $px['id_nhanvien']); ?>
                    </option>
                </select>
            </div>
            <div class="col-md-3 col-6">
                <label class="form-label">Ghi chú</label>
                <input type="text" value="<?php echo $px['ghichu'] ?>" name="ghichu" class="form-control">
            </div>
        </div>

        <div class="mb-3 position-relative">
            <input type="text" id="tim-vattu" class="form-control" placeholder="Tìm vật tư...">
            <div id="goi-y-vattu" class="d-none"></div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Mã VT</th>
                        <th>Tên vật tư</th>
                        <th>ĐVT</th>
                        <th>Tồn kho</th>
                        <th>SL Xuất</th>
                        <th>Mục đích</th>
                        <th>Còn lại</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="ds-vattu-da-chon">
                    <?php
                    $result2 = mysqli_query($conn, "SELECT * FROM chitiet_phieuxuat WHERE id_phieuxuat = $id");

                    while ($ct = mysqli_fetch_array($result2)) {
                        $max = getSlVT($conn, $ct['id_vattu']) + $ct['soluong'];
                        ?>
                        <tr data-id="<?php echo $ct['id_vattu'] ?>">
                            <td><?php echo getMaVT($conn, $ct['id_vattu']) ?><input type="hidden" name="idvt[]"
                                    value="<?php echo $ct['id_vattu'] ?>"></td>
                            <td><?php echo getTenVT($conn, $ct['id_vattu']) ?></td>
                            <td><?php echo getDvtVT($conn, $ct['id_vattu']) ?></td>
                            <td><?php echo getSlVT($conn, $ct['id_vattu']) ?><input type="hidden" class="sl-kho"
                                    value="<?php echo $max ?>"></td>
                            <td><input name="soluong[]" type="number" value="<?php echo $ct['soluong'] ?>" min="0" step="0.01"
                                    max="<?php echo $max; ?>" class="form-control sl-xuat" required></td>
                            <td><input name="mucdich[]" type="text" value="<?php echo $ct['mucdich'] ?>" class="form-control">
                            </td>
                            <td class="d-flex">
                                <input name="soluong[]" disabled type="number" value="<?php echo $ct['xuat_kho'] ?>" min="0"
                                    step="0.01" max="<?php echo $max; ?>" class="form-control sl-xuat" required>
                                <?php
                                if ($ct['xuat_kho'] > 0) {
                                    ?>
                                    <button data-idPX="<?php echo $px['id'] ?>" data-idVattu="<?php echo $ct['id_vattu'] ?>"
                                        class="btn_themPXK btn btn-sm btn-success me-1 ms-1" title="Thêm mới"
                                        type="button">+</button>
                                    <?php
                                }
                                ?>

                                <button data-idPX="<?php echo $px['id'] ?>" data-idVattu="<?php echo $ct['id_vattu'] ?>"
                                    class="btn_lichsuPXK btn btn-sm btn-warning" title="Lịch sử" type="button"><i
                                        class="bi bi-eye"></i></button>
                            </td>
                            <td>
                                <button data-idPX="<?php echo $px['id'] ?>" data-vattu="<?php echo $ct['id_vattu'] ?>"
                                    type="button" class="btn btn-sm btn-danger xoa-vattu-xuat">X</button>



                            </td>

                        </tr>
                        <?php
                    }
                    ?>


                </tbody>
            </table>
        </div>

        <button type="submit" class="btn btn-success mt-3">Lưu Phiếu Xuất</button>
    </form>




    <script>
        let element = document.getElementById('phieuxuat');
        element.classList.add('active');
    </script>




<?php } ?>