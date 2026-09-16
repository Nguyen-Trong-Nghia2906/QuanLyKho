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
    }
</style>



<!-- Modal Thêm mới -->
<div class="modal fade" id="modalThemMoi" tabindex="-1" aria-labelledby="modalThemMoiLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="form-them-moi-to">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalThemMoiLabel">Thêm Tổ Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="">
                            <label class="form-label">Tên tổ</label>
                            <input required type="text" class="form-control" name="tento">
                        </div>
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

<!-- modal chỉnh sửa -->
<div class="modal fade" id="modalChinhSua" tabindex="-1" aria-labelledby="modalChinhSuaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" id="form-chinh-sua-to">

        </form>
    </div>
</div>

<!-- Modal Xác nhận Xóa -->
<div class="modal fade" id="modalXacNhanXoa" tabindex="-1" aria-labelledby="xacNhanXoaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="xacNhanXoaLabel">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xóa tổ này không?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="btnXacNhanXoaTo">Xóa</button>
            </div>
        </div>
    </div>
</div>


<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 d-none d-md-flex">
    <h3>Danh sách tổ/phòng ban</h3>
    <?php if ($_SESSION['quyen'] == 1) {
    ?>
        <a href="#" class="btn_add btn btn-success">Thêm mới</a>
    <?php } ?>
</div>

<!-- Desktop Table -->
<div class="table-responsive">
    <table class="table table-bordered ">
        <thead class="table-light">
            <tr>
                <th>Tên tổ</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody id="bang_to">
            <?php
            $sql = "SELECT * FROM to_nhanvien ORDER BY id DESC";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <tr data-id="<?php echo $row['id'] ?>">
                    <td><?php echo $row['tento'] ?></td>
                    <td class="text-nowrap">

                        <?php
                        if ($_SESSION['quyen'] ==  1) {
                        ?>
                            <button data-id="<?php echo $row['id']; ?>" class="btn_edit_to btn btn-sm btn-outline-primary" title="Chi tiết">
                                <i class="bi bi-pencil-square"></i>
                            </button>

                            <button data-id="<?php echo $row['id'] ?>" class="btn_remove btn btn-sm btn-outline-danger" title="Xóa">
                                <i class="bi bi-trash"></i>
                            </button>
                        <?php }
                        ?>
                    </td>

                </tr>
            <?php
            }
            ?>

        </tbody>
    </table>

</div>






<a href="#" class="btn_add_mobile fab-add d-md-none">+</a>


<script>
    let element = document.getElementById('to');
    element.classList.add('active');
</script>