<?php
session_start();
if (!isset($_SESSION['quyen'])) {
    include('view/dangnhap.php');
} else {
    include("model/ham.php");
    include('model/config.php');
    include('view/header.php');
    include('view/footer.php');
}
?>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.js"></script>
<script src="js/main.js"></script>
<script src="js/jquery.js"></script>