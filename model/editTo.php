<?php
include '../model/config.php';

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $result = mysqli_query($conn, "SELECT * FROM to_nhanvien WHERE id = $id");
    $row = mysqli_fetch_assoc($result);

?>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalChinhSuaLabel">Chỉnh Sửa Tổ/Phòng ban</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
        </div>
        <div class="modal-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Tên tổ</label>
                    <input id="edit_id_to" type="hidden" name="id" value="<?php echo $row['id'] ?>"  >
                    <input id="edit_tento" value="<?php echo $row['tento'] ?>" type="text" class="form-control" name="tento">
                </div>
               

            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Lưu</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        </div>
    </div>

<?php
}
?>