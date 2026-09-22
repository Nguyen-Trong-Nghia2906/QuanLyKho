<?php
include '../model/config.php';
include '../model/ham.php';
session_start();
$maphieu = trim($_POST['maphieu'] ?? '');

if ($maphieu == '') {
    exit;
}

$sql = "SELECT id, maphieu, created_at
        FROM phieuxuat_kho
        WHERE maphieu LIKE ?
        ORDER BY created_at DESC, id DESC
        LIMIT 5";

$stmt = mysqli_prepare($conn, $sql);

$search = "%$maphieu%";

mysqli_stmt_bind_param($stmt, "s", $search);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

while ($row = mysqli_fetch_assoc($result)) {

    $id = htmlspecialchars($row['id']);
    $ma = htmlspecialchars($row['maphieu']);

    echo '
        <button type="button"
                class="list-group-item list-group-item-action item-goi-y"
                data-maphieu="' . $ma . '">
            <strong>' . $ma . '</strong>
        </button>
    ';
}