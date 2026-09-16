
function showToast(message, type = 'success') {
  const toastEl = document.getElementById('liveToast');
  const toastMsg = document.getElementById('toastMessage');

  toastMsg.textContent = message;

  toastEl.classList.remove('bg-success', 'bg-danger', 'bg-warning');
  toastEl.classList.add('bg-' + type);

  const toast = new bootstrap.Toast(toastEl, { delay: 1000 }); // Tắt sau 1s
  toast.show();
}


// // const toggleButton = document.getElementById('menuToggle');
// // const navbarMenu = document.getElementById('navbarMenu');

// // Toggle menu khi bấm nút
// toggleButton.addEventListener('click', function (e) {
//   e.stopPropagation(); // Ngăn click lan ra document
//   navbarMenu.classList.toggle('show');
// });

// // Đóng menu khi bấm ra ngoài
// document.addEventListener('click', function (e) {
//   if (window.innerWidth < 992 && navbarMenu.classList.contains('show')) {
//     const isClickInsideMenu = navbarMenu.contains(e.target) || toggleButton.contains(e.target);
//     if (!isClickInsideMenu) {
//       navbarMenu.classList.remove('show');
//     }
//   }
// });

// // Tự đóng khi resize lại lớn hơn
// window.addEventListener('resize', function () {
//   if (window.innerWidth >= 992) {
//     navbarMenu.classList.remove('show');
//   }
// });

