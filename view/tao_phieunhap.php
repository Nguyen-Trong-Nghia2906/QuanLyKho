<?php if ($_SESSION['quyen'] == 1) {
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

      .chon-vattu-nhap {
        padding: 10px;
        cursor: pointer;
        border-bottom: 1px solid #eee;
      }

      .chon-vattu-nhap:hover {
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


  <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 ">
    <h4 class="mb-4">Tạo Phiếu Nhập Vật Tư</h4>
    <?php
    if ($_SESSION['quyen'] == 1) {
      ?>
      <a href="#" class="btn_add btn btn-success">Thêm mới</a>
      <?php
    }
    ?>
  </div>

  <!-- Modal Thêm mới -->
  <div class="modal fade" id="modalThemMoi" tabindex="-1" aria-labelledby="modalThemMoiLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <form id="form-them-moi">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalThemMoiLabel">Thêm Vật Tư Mới</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
          </div>
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Mã hàng</label>
                <input type="text" class="form-control" name="mahang">
              </div>
              <div class="col-md-6">
                <label class="form-label">Tên hàng</label>
                <input type="text" class="form-control" name="tenhang" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Vị trí</label>
                <input type="text" class="form-control" name="vitri">
              </div>
              <div class="col-md-6">
                <label class="form-label">Kho</label>
                <input type="text" class="form-control" name="tenkho">
              </div>
              <div class="col-md-6">
                <label class="form-label">Đơn vị tính</label>
                <input type="text" class="form-control" name="dvt">
              </div>
              <div class="col-md-6">
                <label class="form-label">Giá trị</label>
                <input type="number" class="form-control" name="giatri">
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


  <form id="form-phieu-nhap">
    <div class="row g-3 mb-3">
      <div class="col-md-3 col-6">
        <label class="form-label">Ngày</label>
        <input type="date" name="ngay_nhap" class="form-control" value="<?= date('Y-m-d') ?>">
      </div>
      <div class="col-md-3 col-6">
        <label class="form-label">Nhà cung cấp</label>
        <input type="text" name="nhacungcap" class="form-control">
      </div>
    </div>

    <div class="mb-3 position-relative">
      <input type="text" id="tim-vattu-nhap" class="form-control" placeholder="Tìm vật tư...">
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
            <th>SL Nhập</th>
            <th></th>
          </tr>
        </thead>
        <tbody id="ds-vattu-da-chon"></tbody>
      </table>
    </div>

    <button type="submit" class="btn btn-success mt-3">Lưu Phiếu Nhập</button>
  </form>

  <script>
    let element = document.getElementById('phieunhap');
    element.classList.add('active');
  </script>

<?php } ?>