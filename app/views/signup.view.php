<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="description" content="Dallas Organic">
    <meta name="keywords" content="organic, dallas, vegetable, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sign up</title>
    <link rel="icon" type="image/jpeg" href="<?= ROOT ?>/assets/favicon.jpeg" sizes="96x96">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/font-awesome.min.css" type="text/css">
    <!-- Bootstrap core CSS -->
<link href="<?=ROOT?>/assets/css/frameworks/bootstrap.min.css" rel="stylesheet">

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }
      .home > a {
        font-size: 30px;
        -webkit-font-smoothing: antialiased;
        font-family: "Nunito", sans-serif;
        text-decoration: none;
        font-weight: 600;
      }
      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
        .home > a {
          font-size: 30px;
          -webkit-font-smoothing: antialiased;
          font-family: "Nunito", sans-serif;
          text-decoration: none;
          font-weight: 600;
        }
      }
    </style>

    
    <!-- Custom styles for this template -->
  </head>
  <body class="col-5 m-auto">
    <main>
      <form method="post">
        <?php if(!empty($errors)):?>
          <div class="alert alert-danger">
            <?= implode("<br>", $errors)?>
          </div>
        <?php endif;?>
        <div class="home text-center">
          <a href="<?= ROOT ?>" class="text-success">DALLAS Organic</a>
        </div>
        <h1 class="h3 mb-3 fw-normal text-center">Đăng kí tài khoản</h1>
        <table class="w-100">
          <tr>
            <td class="col-4">Họ và tên:</td>
            <td class="col"><input type="text" class="form-control" id="fullname" name="fullname"></td>
          </tr>
          <tr>
            <td class="col-4">Tên đăng nhập:</td>
            <td class="col"><input type="text" class="form-control" id="username" name="username"></td>
          </tr>
          <tr>
            <td class="col-4">Mật khẩu:</td>
            <td class="col position-relative">
              <input type="password" class="form-control" id="password" name="password">
              <span class="position-absolute top-0 end-0 pe-3 pt-1" id="togglePassword" style="cursor: pointer;">
                <i class="fa fa-eye"></i>
              </span>
            </td>
            <script>
              const passwordInput = document.querySelector('#password');
              const togglePassword = document.querySelector('#togglePassword');
              togglePassword.addEventListener('click', function () {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                togglePassword.querySelector('i').classList.toggle('fa-eye');
                togglePassword.querySelector('i').classList.toggle('fa-eye-slash');
                document.getElementById("password").focus();
              });
            </script>
          </tr>
          <tr>
            <td class="col-4">Xác nhận mật khẩu:</td>
            <td class="col position-relative">
              <input type="password" class="form-control" id="confirm-password" name="confirm-password">
              <span class="position-absolute top-0 end-0 pe-3 pt-1" id="toggle_Password" style="cursor: pointer;">
                <i class="fa fa-eye"></i>
              </span>
            </td>
            <script>
              const password_confirm = document.querySelector('#confirm-password');
              const toggle_Password = document.querySelector('#toggle_Password');
              toggle_Password.addEventListener('click', function () {
                const type = password_confirm.getAttribute('type') === 'password' ? 'text' : 'password';
                password_confirm.setAttribute('type', type);
                toggle_Password.querySelector('i').classList.toggle('fa-eye');
                toggle_Password.querySelector('i').classList.toggle('fa-eye-slash');
                document.getElementById("confirm-password").focus();
              });
            </script>
          </tr>
          <tr>
            <td class="col-4">Giới tính:</td>
            <td class="col">
              <input type="radio" value="Male" name="gender"> Nam
              <input type="radio" value="Female" name="gender"> Nữ
            </td>
          </tr>
          <tr>
            <td class="col-4">Ngày sinh:</td>
            <td class="col">
              <select name="day" id="day">
                <optgroup label="Ngày">
                  <?php
                    for ($i = 1; $i <= 31; $i++) {
                      $selected = isset($_POST['day']) && $_POST['day'] == $i ? 'selected' : '';
                      if (isset($_POST["sign_up"])) {
                        echo "<option $selected>$i</option>";
                      } else echo "<option>$i</option>";
                    }
                  ?>
                </optgroup>
              </select>
              <select name="month" id="month">
                <optgroup label="Tháng">
                  <?php
                    for ($i = 1; $i <= 12; $i++) {
                      $selected = isset($_POST['month']) && $_POST['month'] == $i ? 'selected' : '';
                      if (isset($_POST["sign_up"])) {
                        echo "<option $selected>$i</option>";
                      } else echo "<option>$i</option>";
                    }
                  ?>
                </optgroup>
              </select>
              <select name="year" id="year">
                <optgroup label="Năm">
                  <?php
                    for ($i = 2022; $i >= 1900; $i--) {
                      $selected = isset($_POST['year']) && $_POST['year'] == $i ? 'selected' : '';
                      if (isset($_POST["sign_up"])) {
                        echo "<option $selected>$i</option>";
                      } else echo "<option>$i</option>";
                    }
                  ?>
                </optgroup>
              </select>
            </td>
          </tr>
          <tr>
            <td class="col-4">Email:</td>
            <td class="col"><input type="email" class="form-control" id="email" name="email"></td>
          </tr>
          <tr>
            <td class="col-4">Địa chỉ:</td>
            <td class="col"><input type="text" class="form-control" id="address" name="address"></td>
          </tr>
          <tr>
            <td class="col-4">SĐT:</td>
            <td class="col"><input type="text" class="form-control" id="phone-number" name="phone-number"></td>
          </tr>
        </table>
        <div class="checkbox mb-3 text-center">
          <label>
            <input name="terms" type="checkbox" value="1"> Tôi chấp nhận mọi điều khoản
          </label>
        </div>
        <div class="text-center">
          <button class="w-50 btn btn-lg btn-success mb-4 text-center" type="submit">Đăng kí</button>
        </div>
        <div class="sign_up text-center">
          Bạn đã có tài khoản? Đăng nhập
          <a href="<?=ROOT?>/login">tại đây</a>
        </div>
      </form>
    </main>
  </body>
</html>
