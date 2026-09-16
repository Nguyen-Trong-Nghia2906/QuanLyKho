<?php
$id = $_GET['id'] ?? 0;
$sql = "SELECT * FROM vattu WHERE id = $id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
?>

<style>
    body {
        background: #f5f5f5;
        padding: 10px;
    }

    .form-control {
        border-radius: 0.5rem;
    }


    .image-wrapper {
        position: relative;
        display: inline-block;
        margin-right: 10px;
        margin-bottom: 10px;
    }

    .image-wrapper img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #ccc;
    }

    .remove-btn {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #ccc;
        color: white;
        border: none;
        border-radius: 50%;
        font-size: 14px;
        width: 22px;
        height: 22px;
        line-height: 18px;
        text-align: center;
        cursor: pointer;
    }

    .image-preview {
        position: relative;
        margin-bottom: 15px;
    }

    .preview-img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 8px;
        margin-right: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
    }


    .img-add-icon {
        font-size: 36px;
        color: #28a745;
        cursor: pointer;
    }


    .image-preview img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        margin-right: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
    }

    .image-actions {
        position: absolute;
        top: 10px;
        right: 10px;
        display: flex;
        gap: 5px;
    }

    .image-actions i {
        color: white;
        background: rgba(0, 0, 0, 0.5);
        padding: 5px;
        border-radius: 50%;
    }

    .image-list {
        display: flex;
        gap: 5px;
        overflow-x: auto;
    }

    .image-list img {
        height: 80px;
        border-radius: 5px;
    }
</style>

<div class="mb-3">
    <label class="form-label">Tên vật tư</label>
    <textarea type="text" <?php if ($_SESSION['quyen'] != 1){ echo 'disabled'; } ?>  class="form-control"><?php echo $row['tenhang'] ?></textarea>
</div>

<div class="mb-3">
    <label class="form-label">Mã vật tư</label>
    <input type="text" class="form-control" value="<?php echo $row['mahang'] ?>">
</div>

<div class="row mb-3">
    <div class="col-6">
        <label class="form-label">Vị trí</label>
        <input <?php if ($_SESSION['quyen'] != 1){ echo 'disabled'; } ?> type="text" class="form-control" value="<?php echo $row['vitri'] ?>">
    </div>
    <div class="col-6">
        <label class="form-label">Kho</label>
        <input  type="text" class="form-control" value="<?php echo $row['tenkho'] ?>">
    </div>

</div>

<div class="row mb-3">
    <div class="col-6">
        <label class="form-label">Số lượng</label>
        <input readonly type="number" class="form-control" value="<?php echo $row['soluong'] ?>">
    </div>
    <div class="col-6">
        <label class="form-label">Đơn vị tính</label>
        <input <?php if ($_SESSION['quyen'] != 1){ echo 'disabled'; } ?> type="text" class="form-control" value="<?php echo $row['dvt'] ?>">
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Giá trị</label>
    <input type="text" class="form-control" value="<?php echo $row['giatri'] ?>">
</div>


<div class="d-flex  mb-2">

    <button id="btn_save" class="btn btn-success">
        <i class="bi bi-save"></i> Lưu
    </button>

    <button data-id="<?php echo $id ?>" style="margin-left: 15px;" class="btn_remove btn btn-sm btn-danger fab-add d-md-none">
        Xóa
    </button>

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
                Bạn có chắc chắn muốn xóa vật tư này không?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="btnXacNhanXoaMobile">Xóa</button>
            </div>
        </div>
    </div>
</div>

<script>
    let element = document.getElementById('vattu');
    element.classList.add('active');
</script>