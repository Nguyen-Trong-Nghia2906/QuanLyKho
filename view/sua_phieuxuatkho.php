<?php
if ($_SESSION['quyen'] == 1) {

    $id = $_GET['id_px'];
    $sql = "SELECT * FROM  phieuxuat_kho  WHERE id = $id";
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


    <h4 class="mb-4">Chỉnh Sửa Phiếu Xuất Kho Kế Toán</h4>

    <form id="form-sua-phieu-xuat-kho">
        <div class="row g-3 mb-3">
            <div class="col-md-3 col-6">
                <label class="form-label">Số phiếu</label>
                <input type="hidden" name="id_pxk" value="<?php echo $px['id'] ?>">
                <input type="hidden" name="id_px" value="<?php echo $px['id_phieuxuat'] ?>">
                <input type="text" value="<?php echo $px['maphieu'] ?>" name="so_phieu" class="form-control" required>
            </div>
            <div class="col-md-3 col-6">
                <label class="form-label">Mã phiếu</label>
                <input disabled type="text" value="<?php echo getMaPhieuXuat($conn, $px['id_phieuxuat']) ?>" name=""
                    class="form-control">
            </div>
            <div class="col-md-3 col-6">
                <label class="form-label">Tổ</label>
                <select disabled name="to" class="form-select">
                    <option value="0"><?php echo getTo($conn, $px['id_phieuxuat']) ?></option>
                </select>
            </div>
            <div class="col-md-3 col-6">
                <label class="form-label">Người đề nghị</label>
                <select disabled name="nguoi_de_nghi" class="form-select">
                    <option value=""> <?php echo getTenNV($conn, $px['id_phieuxuat']) ?>
                    </option>
                </select>
            </div>

        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Mã VT</th>
                        <th>Tên vật tư</th>
                        <th>ĐVT</th>
                        <th>Còn lại</th>
                        <th>SL Xuất</th>

                        <th></th>
                    </tr>
                </thead>
                <tbody id="ds-vattu-da-chon">
                    <?php
                    $result2 = mysqli_query($conn, "SELECT * FROM chitiet_phieuxuatkho WHERE id_phieuxuatkho = $id");

                    while ($ct = mysqli_fetch_array($result2)) {

                        $max = getSLXK($conn, $px['id_phieuxuat'], $ct['id_vattu']) + $ct['soluong'];
                        ?>
                        <tr data-id="<?php echo $ct['id_vattu'] ?>">
                            <td><?php echo getMaVT($conn, $ct['id_vattu']) ?><input type="hidden" name="idvt[]"
                                    value="<?php echo $ct['id_vattu'] ?>"></td>
                            <td><?php echo getTenVT($conn, $ct['id_vattu']) ?></td>
                            <td><?php echo getDvtVT($conn, $ct['id_vattu']) ?></td>
                            <td>
                                <?php echo getSLXK($conn, $px['id_phieuxuat'], $ct['id_vattu']); ?>

                                <input type="hidden" class="sl-xuatkho" value="<?php echo $max ?>">
                            </td>
                            <td><input name="soluong[]" type="number" value="<?php echo $ct['soluong'] ?>" min="0" step="0.01"
                                    class="form-control sl-xuat" required></td>

                            <td> <button data-idPXK="<?php echo $px['id'] ?>" data-vattu="<?php echo $ct['id_vattu'] ?>  "
                                    data-idPX="<?php echo $px['id_phieuxuat'] ?>" type="button"
                                    class="btn btn-sm btn-danger xoa-vattu-xuatkho">X</button>



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
        let element = document.getElementById('phieuxuat_kho');
        element.classList.add('active');
    </script>




<?php } ?>