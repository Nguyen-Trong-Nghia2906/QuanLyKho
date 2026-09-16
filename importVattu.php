<?php
require __DIR__ . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

// Hiển thị lỗi để dễ debug (chỉ bật khi phát triển)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Kết nối CSDL
require_once 'model/config.php';

// Nếu người dùng nhấn nút submit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel_file'])) {
    
    

    $file = $_FILES['excel_file']['tmp_name'];

    try {
        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        $inserted = 0;

        // Bỏ qua dòng tiêu đề (dòng 0)
        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];

            $mahang  = $row[0] ?? '';
            $tenhang = $row[1] ?? '';
            $dvt     = $row[2] ?? '';
            $soluong = $row[3] ?? 0;
            $giatri  = $row[4] ?? 0;
            $tenkho  = $row[5] ?? '';
            $vitri   = $row[6] ?? '';

            if ($mahang !== '' && $tenhang !== '') {
                $stmt = $conn->prepare("INSERT INTO vattu(mahang, tenhang, dvt, soluong, giatri, tenkho, vitri) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssddss", $mahang, $tenhang, $dvt, $soluong, $giatri, $tenkho, $vitri);
                $stmt->execute();
                $stmt->close();
                $inserted++;
            }
        }

        echo "<div style='padding: 16px; background: #d4edda; color: #155724;'>✅ Nhập thành công {$inserted} dòng.</div>";

    } catch (Exception $e) {
        echo "<div style='padding: 16px; background: #f8d7da; color: #721c24;'>❌ Lỗi đọc file: " . $e->getMessage() . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Import Vật Tư từ Excel</title>
</head>
<body>
    <h2>📥 Import danh sách vật tư từ file Excel</h2>
    <form method="post" enctype="multipart/form-data">
        <input type="file" name="excel_file" accept=".xls,.xlsx" required>
        <button type="submit">Tải lên & Nhập dữ liệu</button>
    </form>

    <p>📌 File Excel cần có thứ tự cột như sau (không cần tiêu đề):</p>
    <ol>
        <li>Mã hàng</li>
        <li>Tên hàng</li>
        <li>ĐVT</li>
        <li>Số lượng</li>
        <li>Giá trị</li>
        <li>Tên kho</li>
        <li>Vị trí</li>
    </ol>
</body>
</html>
