<style>
    body {
        background-color: #f5f9ff;
    }

    .table img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
    }

    @media (max-width: 768px) {
        .table-responsive {
            display: none;
        }

        .card-vattu {
            display: flex;
            align-items: center;
            background: #fff;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .card-vattu img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 10px;
        }

        .card-vattu .info {
            flex-grow: 1;
        }

        .card-vattu .info .name {
            font-weight: bold;
            margin-bottom: 2px;
        }

        .card-vattu .info .location {
            font-size: 0.9rem;
            color: #555;
        }

        .card-vattu .qty {
            font-weight: bold;
            color: #0d6efd;
            white-space: nowrap;
        }

        .fab-add {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #28a745;
            color: white;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 28px;
            text-align: center;
            line-height: 50px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            z-index: 1000;
            text-decoration: none;
        }

        .form_control {
            /* display: block; */
            width: 100%;
            padding: .375rem .75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: var(--bs-body-color);
            background-color: var(--bs-body-bg);
            background-clip: padding-box;
            border: var(--bs-border-width) solid var(--bs-border-color);
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            border-radius: var(--bs-border-radius);
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }
    }
</style>







<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 ">
    <h3 class="d-none d-md-flex">Tổng hợp nhập-xuất-tồn</h3>
    <button id="btn_excel" class="btn btn-success">
        Xuất Excel
    </button>
</div>
<div class="row mb-3 align-items-center">

    <!-- Input tìm kiếm -->
    <div class="col-md-4 col-sm-12 mb-2 mb-md-0">
        <input id="search_tonghop_ten" type="text" class="form-control" placeholder="Tìm kiếm vật tư...">
    </div>


    <!-- Date start -->
    <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
        <input id="date_start_tonghop" type="date" class="form-control">
    </div>

    <!-- Date end -->
    <div class="col-md-3 col-sm-6">
        <input id="date_end_tonghop" type="date" class="form-control">
    </div>

    <!-- Select -->
    <div class="col-md-2 col-sm-12 mb-2 mb-md-0">
        <select id="search_tonghop_kho" class="form-select">
            <option value="1">-- Tên kho --</option>
            <?php
            $sql = 'SELECT DISTINCT tenkho FROM vattu WHERE tenkho != NULL OR tenkho != ""';
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_array($result)) {
                ?>
                <option value="<?php echo $row['tenkho'] ?>"><?php echo $row['tenkho'] ?></option>
            <?php }
            ?>
        </select>
    </div>


</div>


<div class="row mb-3 d-md-none flex">
    <div class="col-md-8 col-sm-12 mb-2" style="width: 70%;">
        <input id="searchMobile" type="text" class="form-control" placeholder="Tìm kiếm vật tư...">

    </div>
    <div style="width:30%" class="col-md-4 col-sm-12">
        <input id="searchMobile" type="text" class="form-control" placeholder="Tìm kiếm vật tư...">

    </div>
</div>

<!-- Desktop Table -->
<div class="table-responsive">
    <table class="table table-bordered align-middle">
        <thead class="table-primary">
            <tr>
                <th>Mã vật tư</th>
                <th>Tên vật tư</th>
                <th>ĐVT</th>
                <th>Nhập</th>
                <th>Xuất</th>
                <th>Ứng</th>
                <th>Tồn kho</th>
                <th>Tên kho</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody id="bang_tonghop">

            <?php

            $sql = '
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

                LEFT JOIN (
                    SELECT 
                        ctn.id_vattu,
                        SUM(ctn.soluong) AS tong_nhap
                    FROM chitiet_phieunhap ctn
                    JOIN phieunhap pn ON pn.id = ctn.id_phieunhap
                    WHERE 1=1
                    
                    GROUP BY ctn.id_vattu
                ) nhap ON vt.id = nhap.id_vattu

                -- tổng xuất
                LEFT JOIN (
                    SELECT 
                        ctx.id_vattu,
                        SUM(ctx.soluong) AS tong_xuat
                    FROM chitiet_phieuxuat ctx
                    JOIN phieuxuat px ON px.id = ctx.id_phieuxuat
                    WHERE 1=1
                        
                    GROUP BY ctx.id_vattu
                ) xuat ON vt.id = xuat.id_vattu

                LEFT JOIN (
                    SELECT 
                        cu.id_vattu,
                        SUM(cu.soluong) AS tong_ung
                    FROM chitiet_phieuung cu
                    JOIN phieuung pu ON pu.id = cu.id_phieuung
                    WHERE 1=1
                    
                    GROUP BY cu.id_vattu
                ) ung ON vt.id = ung.id_vattu

                WHERE 1=1
                
                ORDER BY vt.id DESC
                LIMIT 100
                
                ;
            ';
            $list = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($list)) {
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
                        <button data-id="<?php echo $row['id'] ?>" data-bs-toggle="modal"
                            data-bs-target="#thongKeNhaMayModal" class="btn_thongke btn btn-sm btn-outline-warning"
                            title="Nhà máy">
                            <i class="bi bi-house-gear"></i>
                        </button>
                        <?php
                        if ($_SESSION['quyen'] == 1) {
                            ?>


                            <a href="?v=chitiet_vattu&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary"
                                title="Chi tiết">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <button data-id="<?php echo $row['id'] ?>" class="btn_remove btn btn-sm btn-outline-danger"
                                title="Xóa">
                                <i class="bi bi-trash"></i>
                            </button>
                            <?php
                        }
                        ?>

                    </td>

                </tr>
            <?php } ?>



        </tbody>
    </table>

</div>

<!-- Mobile Cards -->
<div class="d-md-none" id="bang_vattu_mobile">
    <?php
    // Reset lại kết quả vì đã dùng 1 lần ở trên
    mysqli_data_seek($list, 0);
    while ($row = mysqli_fetch_assoc($list)) {
        ?>
        <a style="color:#000; text-decoration:none" href="?v=chitiet_vattu&id=<?php echo $row['id']; ?>">


            <div data-id="<?php echo $row['id'] ?>" class="card-vattu">

                <div class="info">
                    <div class="name"><?php echo $row['tenhang']; ?></div>
                    <div class="location"><?php echo $row['vitri'] ?? 'Chưa cập nhật'; ?></div>
                </div>
                <div class="qty"><?php echo $row['soluong'] . "  " . $row['dvt']; ?></div>
            </div>
        </a>
    <?php } ?>
</div>

<!-- Lịch sử vật tư -->
<div class="modal fade" id="lichSuMobileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">📜 Lịch sử vật tư</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="lichSuMobileContent">
                Đang tải dữ liệu...
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="lichSuXuatModal" tabindex="-1" aria-labelledby="lichSuXuatLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">📜 Lịch sử nhập/xuất kho</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                <div id="lichSuContent" class="table-responsive text-center text-muted">
                    Đang tải dữ liệu...
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Thống kê nhà máy -->
<div class="modal fade" id="thongKeNhaMayModal" tabindex="-1" aria-labelledby="lichSuXuatLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tổng hợp nhập-xuất-tồn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                <div id="thongKeContent" class="table-responsive text-center text-muted">
                    Đang tải dữ liệu...
                </div>
            </div>
        </div>
    </div>
</div>



<a href="#" class="btn_add_mobile fab-add d-md-none">+</a>


<script>
    let element = document.getElementById('tonghop');
    element.classList.add('active');



    function exportExcel() {
        let tenkho = $('#search_tonghop_kho').val();
        let tenvt = $('#search_tonghop_ten').val();
        let date_start = $('#date_start_tonghop').val();
        let date_end = $('#date_end_tonghop').val();

        let url = "model/tonghop.php?mode=excel"
            + "&tenkho=" + tenkho
            + "&tenvt=" + tenvt
            + "&date_start=" + date_start
            + "&date_end=" + date_end;
        
    }
</script>