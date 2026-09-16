<?php
if ($_SESSION['quyen'] == 1) {

    $id = $_GET['id_pu'];
    $sql = "SELECT * FROM  phieuung  WHERE id = $id";
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

    <h4 class="mb-4">Chỉnh Sửa Phiếu Xuất Vật Tư</h4>

    <form id="form-sua-phieu-ung">
        <div class="row g-3 mb-3">
            <div class="col-md-3 col-6">
                <label class="form-label">Ngày</label>
                <input type="hidden" name="id_pu" value="<?php echo $px['id'] ?>">
                <input type="date" value="<?php echo $px['ngayung'] ?>" name="ngay_ung" class="form-control" value="<?= date('Y-m-d') ?>">
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
                    <option value="<?php echo  $px['id_nhanvien'] ?>"> <?php echo  getNameNV($conn, $px['id_nhanvien']); ?> </option>
                </select>
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
                        <th></th>
                    </tr>
                </thead>
                <tbody id="ds-vattu-da-chon">
                    <?php
                    $result2 = mysqli_query($conn, "SELECT * FROM chitiet_phieuung WHERE id_phieuung = $id");

                    while ($ct = mysqli_fetch_array($result2)) {
                        $max = getSlVT($conn, $ct['id_vattu']) + $ct['soluong'];
                    ?>
                        <tr data-id="<?php echo $ct['id_vattu'] ?>">
                            <td><?php echo getMaVT($conn, $ct['id_vattu']) ?><input type="hidden" name="idvt[]" value="<?php echo $ct['id_vattu'] ?>"></td>
                            <td><?php echo getTenVT($conn, $ct['id_vattu']) ?></td>
                            <td><?php echo getDvtVT($conn, $ct['id_vattu']) ?></td>
                            <td><?php echo getSlVT($conn, $ct['id_vattu']) ?><input type="hidden" class="sl-kho" value="<?php echo $max ?>"></td>
                            <td><input name="soluong[]" type="number" value="<?php echo $ct['soluong'] ?>" min="0" step="0.01" max="<?php echo $max; ?>" class="form-control sl-xuat" required></td>
                            <td><input name="mucdich[]" type="text" value="<?php echo $ct['ghichu'] ?>" class="form-control"></td>
                            <td><button data-idPU="<?php echo $px['id'] ?>" data-vattu="<?php echo $ct['id_vattu'] ?>" type="button" class="btn btn-sm btn-danger xoa-vattu-ung">X</button></td>
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
        let element = document.getElementById('phieuung');
        element.classList.add('active');
    </script>

<?php  } ?>