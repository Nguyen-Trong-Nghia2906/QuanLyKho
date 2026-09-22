<?php
include '../model/config.php';
include '../model/ham.php';
session_start();
// Lấy dữ liệu từ POST và gán giá trị mặc định nếu bỏ trống
$idvattu = isset($_POST['idvattu']) ? $_POST['idvattu'] : '';
$idpx = isset($_POST['idpx']) ? $_POST['idpx'] : '';

// Tạo câu truy vấn (giá trị rỗng vẫn được thêm)
$sql = " SELECT pxk.id as id, ct.xuat_kho as xk , vt.tenhang as tenvt, ct.soluong as sl, pxk.maphieu as maphieu, ct_pxk.soluong as slx
 FROM phieuxuat px JOIN chitiet_phieuxuat ct ON px.id = ct.id_phieuxuat 
    JOIN vattu vt ON vt.id = ct.id_vattu 
    JOIN phieuxuat_kho pxk ON pxk.id_phieuxuat = px.id 
    JOIN chitiet_phieuxuatkho ct_pxk ON ct_pxk.id_phieuxuatkho = pxk.id AND ct_pxk.id_vattu = ct.id_vattu

 WHERE px.id = '$idpx' AND ct.id_vattu = '$idvattu'";

$query = mysqli_query($conn, $sql);
if (mysqli_num_rows($query) > 0) {
    $row = mysqli_fetch_assoc($query);
    ?>
    <table class="table table-bordered table-sm">
        <thead class="table-secondary">
            <tr>
                <th>Tên vật tư</th>
                <th>Số lượng đề nghị</th>

            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo $row['tenvt'] ?></td>
                <td><?php echo $row['sl'] ?></td>

            </tr>
             <tr>
                <td></td>
                <td></td>

            </tr>
        </tbody>
    
        <thead class=" mt-5 table-secondary">
            <tr>
                <th>Số phiếu</th>
                <th>Số lượng</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sum = 0;
            $query2 = mysqli_query($conn, $sql);
            while ($row2 = mysqli_fetch_array($query2)) {
                ?>
                <tr>
                    <td>
                        <a href="?v=sua_phieuxuatkho&id_px=<?php echo $row2['id'] ?>"><?php echo $row2['maphieu'] ?></a>
                        
                    </td>
                    <td>
                        <?php echo $row2['slx']; $sum += $row2['slx'];  ?>
                    </td>
                </tr>
                <?php
            }
            ?>
            <tr>
                <th>Tổng cộng</th>
                <th><?php echo $sum; ?></th>
            </tr>

        </tbody>
    </table>

<?php } else {
    echo "Không có dữ liệu";
}
