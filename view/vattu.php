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
        <button type="button" class="btn btn-danger" id="btnXacNhanXoa">Xóa</button>
      </div>
    </div>
  </div>
</div>


<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 ">
  <h3 class="d-none d-md-flex">Danh sách Vật tư</h3>
  <?php
  if ($_SESSION['quyen'] == 1) {
    ?>
    <a href="#" class="btn_add btn btn-success">Thêm mới</a>
    <?php
  }
  ?>
</div>
<div class="d-none d-md-flex row mb-3" style="flex-wrap: nowrap;">
  <div class="col-md-8 col-sm-12 mb-2" style="width: 70%;">
    <input id="search" type="text" class="form-control" placeholder="Tìm kiếm vật tư...">
  </div>
  <div style="width:30%" class="col-md-4 col-sm-12">
    <select id="select_search" class="form-select">
      <option>Tên</option>
      <option>Vị trí</option>
      <option>Kho</option>
    </select>
  </div>
</div>



<div class="row mb-3 d-md-none flex">
  <div class="col-md-8 col-sm-12 mb-2" style="width: 70%;">
    <input id="searchMobile" type="text" class="form-control" placeholder="Tìm kiếm vật tư...">
  </div>
  <div style="width:30%" class="col-md-4 col-sm-12">
    <select id="select_search_mobile" class="form-select">
      <option>Tên</option>
      <option>Vị trí</option>
      <option>Kho</option>
    </select>
  </div>
</div>

<!-- Desktop Table -->
<div class="table-responsive">
  <table class="table table-bordered align-middle">
    <thead class="table-primary">
      <tr>
        <th>Mã vật tư</th>
        <th>Tên vật tư</th>
        <th>Vị trí</th>
        <th>ĐVT</th>
        <th>Số lượng</th>
        <th>Kho</th>

        <th>Hành động</th>
      </tr>
    </thead>
    <tbody id="bang_vattu">

      <?php


      $list = mysqli_query($conn, "SELECT * FROM vattu ORDER BY id DESC LIMIT 100");
      while ($row = mysqli_fetch_assoc($list)) {
        ?>
        <tr data-id="<?php echo $row['id']; ?>">

          <td><?php echo $row['mahang']; ?></td>
          <td><?php echo $row['tenhang']; ?></td>
          <td><?php echo $row['vitri'] ?></td>
          <td><?php echo $row['dvt']; ?></td>
          <td><?php echo $row['soluong']; ?></td>
          <td><?php echo $row['tenkho']; ?></td>

          <td class="text-nowrap">
            <button data-id="<?php echo $row['id'] ?>" data-bs-toggle="modal" data-bs-target="#lichSuXuatModal"
              class="btn_lichsu btn btn-sm btn-outline-success" title="Lịch sử">
              <i class="bi bi-clock-history"></i>
            </button>

            <a href="?v=chitiet_vattu&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary"
              title="Chi tiết">
              <i class="bi bi-pencil-square"></i>
            </a>

            <?php
            if ($_SESSION['quyen'] == 1) {
              ?>


              <button data-id="<?php echo $row['id'] ?>" data-bs-toggle="modal" data-bs-target="#thongKeNhaMayModal"
                class="btn_thongke btn btn-sm btn-outline-warning" title="Nhà máy">
                <i class="bi bi-house-gear"></i>
              </button>
              <button data-id="<?php echo $row['id'] ?>" class="btn_remove btn btn-sm btn-outline-danger" title="Xóa">
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
        <h5 class="modal-title">Thống kê nhà máy đường</h5>
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
  let element = document.getElementById('vattu');
  element.classList.add('active');
</script>