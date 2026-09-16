<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng nhập - Quản lý kho</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      height: 100vh;
      background: #000;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .bg-image {
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      width: 100vw;
      object-fit: cover;
      z-index: -2;
    }

    body::before {
      content: "";
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.4);
      z-index: -1;
    }

    .container {
      width: 100%;
      max-width: 900px;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
      display: flex;
      flex-wrap: wrap;
      overflow: hidden;
    }

    .left,
    .right {
      flex: 1 1 50%;
      padding: 30px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .left img {
      max-width: 100%;
      height: auto;
    }

    .right {
      flex-direction: column;
      padding: 40px 30px;
    }

    .right h2 {
      text-align: center;
      font-size: 24px;
      margin-bottom: 20px;
      color: #333;
    }

    .input-group {
      width: 100%;
      margin-bottom: 20px;
    }

    .input-group input {
      width: 100%;
      padding: 12px 16px;
      border: 1px solid #ccc;
      border-radius: 25px;
      background: #f0f0f0;
      font-size: 16px;
    }

    .btn-login {
      background-color: #00923f;
      color: white;
      padding: 12px;
      width: 100%;
      border: none;
      border-radius: 25px;
      font-size: 16px;
      cursor: pointer;
    }

    .btn-login:hover {
      opacity: 0.9;
    }

    .thongbao {
      text-align: center;
      color: red;
      margin-bottom: 10px;
    }

    @media screen and (max-width: 768px) {
      .left {
        display: none;
      }

      .right {
        flex: 1 1 100%;
        padding: 30px 20px;
      }

      .container {
        margin: 20px;
      }
    }

    #form_dangnhap{
      width: 100%;
    }
  </style>
</head>

<body>
  <img src="img/BACKGROUND.jpg" class="bg-image" alt="background">
  <div class="container">
    <div class="left">
      <img src="img/logo.png" alt="Logo" />
    </div>
    <div class="right">
      <h2>QUẢN LÝ KHO VẬT TƯ</h2>
      <div class="thongbao" id="text_dangnhap"> </div>
      <form id="form_dangnhap">
        <div class="input-group">
          <input name="taikhoan" type="text" required placeholder="Tài khoản">
        </div>
        <div class="input-group">
          <input type="password" name="matkhau" required placeholder="Mật khẩu">
        </div>
        <button type="submit" class="btn-login">ĐĂNG NHẬP</button>
      </form>
    </div>
  </div>
</body>

</html>
