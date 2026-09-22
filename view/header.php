<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kho Vật Tư</title>

  <!-- Bootstrap + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="icon" href="img/box.png">
<link rel="manifest" href="/manifest.json">
  <style>
    body {
      background-color: #f5f9ff;
    }

    .navbar-brand {
      color: #00913e !important;
      font-weight: bold;
    }

    .btn-success {
      background-color: #28a745;
    }

    .user-info {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .mobile {
      display: none;
    }

    .navbar-nav .nav-link {
      font-weight: 500;
      color: #333;
      transition: all 0.3s ease;
    }

    .navbar-nav .nav-link:hover {
      color: #28a745;
      transform: translateX(2px);
    }

    .navbar-toggler {
      border: none;
    }

    .navbar-toggler:focus {
      box-shadow: none;
    }

    .navbar-collapse {
      transition: all 0.3s ease-in-out;
    }

    .navbar-nav .nav-item i {
      margin-right: 6px;
    }

    @media (max-width: 768px) {
      .user-info {
        flex-direction: column;
        align-items: flex-start;
        margin-top: 10px;
      }

      .user_info {
        display: none;
      }

      .mobile {
        display: block;
      }

      .text-danger {
        padding: 10px;
      }
    }

    @media (max-width: 991.98px) {
      #navbarMenu {
        display: none;
        flex-direction: column;
        width: 100%;
      }

      .text-danger {
        padding: 0px;
      }

      #navbarMenu.show {
        display: flex;
      }
    }

    /* Dropdown hover */
    .dropdown:hover .dropdown-menu {
      display: block;
      margin-top: 0;
      /* tránh giật */
    }

    .active{
      color: #00913e !important;
    }
  </style>
  
  <script>
navigator.serviceWorker.register('/service-worker.js');
</script>
</head>

<body>
  <!-- Toast Container -->
  <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
    <div id="liveToast" class="toast align-items-center text-white bg-success border-0" role="alert">
      <div class="d-flex">
        <div class="toast-body" id="toastMessage">
          Thành công!
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>
  </div>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
    <div class="container-fluid">
      <img src="img/logo.png" alt="Logo" style="height: 40px; margin-right: 10px;">
      <a class="navbar-brand" href="#">Kho Vật Tư</a>

      <!-- Nút toggle cho mobile -->
      <button class="navbar-toggler" type="button" id="menuToggle">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Menu -->
      <div id="navbarMenu" class="navbar-collapse">
        <!-- Menu trái -->
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li  class="nav-item"><a id="vattu" class="nav-link" href="?v=vattu"><i class="bi bi-box-seam"></i> Vật tư</a></li>
          <li class="nav-item"><a id="phieuxuat"  class="nav-link" href="?v=phieuxuat"><i class="bi bi-arrow-up-right-circle"></i>Phiếu Xuất</a></li>
          <li  class="nav-item"><a class="nav-link"  id="phieuung" href="?v=phieuung"><i class="bi bi-journal-text"></i>Phiếu Ứng</a></li>
          <li   class="nav-item"><a  id="phieunhap" class="nav-link" href="?v=phieunhap"><i class="bi bi-arrow-down-left-circle"></i>Phiếu Nhập</a></li>
          <li  class="nav-item"><a id="nhanvien" class="nav-link" href="?v=nhanvien"><i class="bi bi-people-fill"></i> Nhân viên</a></li>
          <li  class="nav-item"><a id="to" class="nav-link" href="?v=to"><i class="bi bi-microsoft-teams"></i> Tổ</a></li>
          <li  class="nav-item"><a id="phieuxuat_kho" class="nav-link" href="?v=phieuxuat_kho"><i class="bi bi-journal-check"></i> Phiếu xuất kho</a></li>
          
          <!-- <li  class="nav-item"><a id="tonghop" class="nav-link" href="?v=tonghop"><i class="bi bi-reception-4"></i> Tổng hợp</a></li> -->
          <li  class="nav-item mobile">
            <a class="nav-link" href="#"><i class="bi bi-person-circle"></i> <?php echo $_SESSION['hoten'] ?? 'Chưa đăng nhập'; ?></a>
          </li>
          <li class="nav-item mobile">
            <a href="?v=logout" class="text-decoration-none text-danger nav-link" title="Đăng xuất">
              <i class="bi bi-box-arrow-right"></i> Đăng xuất
            </a>
          </li>
        </ul>

        <!-- Thông tin user desktop -->
        <div class="user_info dropdown">
          <a class="dropdown-toggle text-dark text-decoration-none" href="#" role="button">
            <i class="bi bi-person-circle fs-5 me-1"></i>
            <?php echo $_SESSION['hoten'] ?? 'Chưa đăng nhập'; ?>
          </a>
          <ul style="    left: -70px;" class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item text-danger" href="?v=logout"><i class="bi bi-box-arrow-right"></i> Đăng xuất</a></li>
          </ul>
        </div>

      </div>
    </div>
  </nav>

  <!-- Nội dung chính -->
  <div class="container" style="max-width: 100%; padding-top: 80px;">
    <?php include('main.php'); ?>
  </div>

  <!-- Script Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Tự động toggle menu mobile -->
  <script>
    const menuToggle = document.getElementById('menuToggle');
    const navbarMenu = document.getElementById('navbarMenu');
    menuToggle.addEventListener('click', () => {
      navbarMenu.classList.toggle('show');
    });
  </script>
</body>

</html>