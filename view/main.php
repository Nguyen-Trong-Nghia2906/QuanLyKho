<?php
$v = isset($_GET['v']) ? $_GET['v'] : 'vattu';
switch ($v) {
    case 'vattu':
        include('vattu.php');
        break;
    case 'chitiet_vattu':
        include('chitiet_vattu.php');
        break;
    case 'phieuxuat':
        include('phieuxuat.php');
        break;
    case 'tao_phieuxuat':
        include('tao_phieuxuat.php');
        break;
    case 'sua_phieuxuat':
        include('sua_phieuxuat.php');
        break;
    case 'phieunhap':
        include('phieunhap.php');
        break;
    case 'sua_phieunhap':
        include('sua_phieunhap.php');
        break;
    case 'tao_phieunhap':
        include('tao_phieunhap.php');
        break;

    case 'to':
        include('to.php');
        break;
    case 'nhanvien':
        include('nhanvien.php');
        break;
    case 'phieuung':
        include('phieuung.php');
        break;
    case 'sua_phieuung':
        include('sua_phieuung.php');
        break;
    case 'tao_phieuung':
        include('tao_phieuung.php');
        break;
    case 'tonghop':
        include('tonghop.php');
        break;
    case 'logout':
        session_destroy();
        echo "<script>window.location.href='index.php';</script>";
        break;
}
