<?php
if ($_SESSION['quyen'] == 1) {

?>
  <!DOCTYPE html>
  <html lang="vi">

  <head>
    <meta charset="UTF-8">
    <title>Tạo Phiếu Xuất Vật Tư</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
  </head>

  <h4 class="mb-4">Tạo Phiếu Xuất Vật Tư</h4>

  <form id="form-phieu-xuat">
    <div class="row g-3 mb-3">
      <div class="col-md-3 col-6">
        <label class="form-label">Số phiếu</label>
        <input type="text" name="so_phieu" class="form-control" required>
      </div>
      <div class="col-md-3 col-6">
        <label class="form-label">Ngày</label>
        <input type="date" name="ngay_xuat" class="form-control" value="<?= date('Y-m-d') ?>">
      </div>
      <div class="col-md-3 col-6">
        <label class="form-label">Tổ</label>
        <select name="to" class="form-select">
          <option value="0"> --- </option>
          <?php
          $toList = mysqli_query($conn, "SELECT id, tento FROM to_nhanvien");
          while ($row = mysqli_fetch_assoc($toList)) {
            echo "<option value='{$row['id']}'>{$row['tento']}</option>";
          }
          ?>
        </select>
      </div>
      <div class="col-md-3 col-6">
        <label class="form-label">Người đề nghị</label>
        <select name="nguoi_de_nghi" class="form-select">
          <option value="0"> --- </option>
        </select>
      </div>
      <div class="col-md-3 col-6">
        <label class="form-label">Ghi chú</label>
        <input type="text" name="ghichu" class="form-control">
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
            <th>STT</th>
            <th>Mã VT</th>
            <th>Tên vật tư</th>
            <th>ĐVT</th>
            <th>Tồn kho</th>
            <th>SL Xuất</th>
            <th>Mục đích</th>
            <th></th>
          </tr>
        </thead>
        <tbody id="ds-vattu-da-chon"></tbody>
      </table>
    </div>

    <button type="submit" class="btn btn-success mt-3">Lưu Phiếu Xuất</button>
  </form>


  <script>
    let element = document.getElementById('phieuxuat');
    element.classList.add('active');
  </script>

<?php  } ?>