<?php
include '../model/config.php';
include '../model/ham.php';
session_start();
// Lấy dữ liệu từ POST và gán giá trị mặc định nếu bỏ trống
$idvattu = isset($_POST['idvattu']) ? $_POST['idvattu'] : '';
$idpx = isset($_POST['idpx']) ? $_POST['idpx'] : '';

// Tạo câu truy vấn (giá trị rỗng vẫn được thêm)
$sql = " SELECT ct.xuat_kho as xk , vt.tenhang as tenvt
 FROM phieuxuat px JOIN chitiet_phieuxuat ct ON px.id = ct.id_phieuxuat JOIN vattu vt ON vt.id = ct.id_vattu
 WHERE px.id = '$idpx' AND ct.id_vattu = '$idvattu'";

if ($query = mysqli_query($conn, $sql)) {
    $row = mysqli_fetch_assoc($query);
    ?>
    <div class="col-md-6">
        <label class="form-label">Tên vật tư</label>
        <input value="<?php echo $row['tenvt'] ?>" type="text" class="form-control" disabled>
        <input hidden value="<?php echo $idpx ?>" type="text" class="form-control"  name="idphieu" >
        <input hidden value="<?php echo $idvattu ?>" type="text" class="form-control"  name="idvattu" >
    </div>

    <div class="col-md-6">
        <label class="form-label">Số lượng còn lại</label>
        <input value="<?php echo $row['xk'] ?>" type="number" class="form-control" disabled>
    </div>
    <div class="col-md-6">
        <label class="form-label">Số phiếu</label>
        <input id="maPXK" type="text" class="form-control" name="maphieu" required>
        <div id="goiYPhieuXuat" class="list-group pt-1 w-100" style="z-index: 1050;">
        </div>
    </div>
    <div class="col-md-6">
        <label class="form-label">Số lượng xuất</label>
        <input min="0" step="0.01" max="<?php echo $row['xk'] ?>" type="number" class="form-control" name="soluong" required>
    </div>
<?php } else {
    echo "Lỗi: " . mysqli_error($conn);
}
