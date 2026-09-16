<?php
include '../model/config.php';

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $result = mysqli_query($conn, "SELECT * FROM nhanvien WHERE id = $id");
    $row = mysqli_fetch_assoc($result);

?>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalChinhSuaLabel">Chỉnh Sửa Nhân Viên Mới</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
        </div>
        <div class="modal-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Họ tên</label>
                    <input id="edit_id_nv" type="hidden" name="id" value="<?php echo $row['id'] ?>"  >
                    <input id="edit_hoten" value="<?php echo $row['hoten'] ?>" type="text" class="form-control" name="hoten">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tổ</label>
                    <select id="edit_id_to" name="id_to" class="form-select">
                        <option value="0" selected disabled> --- </option>
                        <?php
                        $toList = mysqli_query($conn, "SELECT id, tento FROM to_nhanvien");
                        while ($to = mysqli_fetch_assoc($toList)) {
                            if ($to['id'] == $row['id_to']) {
                                echo "<option selected value='{$to['id']}'>{$to['tento']}</option>";
                            } else {
                                echo "<option value='{$to['id']}'>{$to['tento']}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Số điện thoại</label>
                    <input id="edit_sdt" value="<?php echo $row['sdt'] ?>" type="text" class="form-control" name="sdt">
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