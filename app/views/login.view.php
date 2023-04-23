<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="description" content="Dallas Organic">
  <meta name="keywords" content="organic, dallas, vegetable, html">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Sign in</title>
  <link rel="icon" type="image/jpeg" href="<?= ROOT ?>/assets/favicon.jpeg" sizes="96x96">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/font-awesome.min.css" type="text/css">

  <!-- Bootstrap core CSS -->
  <link href="<?= ROOT ?>/assets/css/frameworks/bootstrap.min.css" rel="stylesheet">

  <style>
    .bd-placeholder-img {
      font-size: 1.125rem;
      text-anchor: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      user-select: none;
    }
    .form-floating {
      padding-bottom: 5px;
      padding-top: 5px;
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
      .form-floating {
        padding-bottom: 5px;
        padding-top: 5px;
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
  <link href="<?= ROOT ?>/assets/css/pages/signin.css" rel="stylesheet">
</head>

<body class="text-center">

  <main class="form-signin">
    <form method="post">

      <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
          <?= implode("<br>", $errors) ?>
        </div>
      <?php endif; ?>
      <div class="home">
        <a href="<?= ROOT ?>" class="text-success">DALLAS Organic</a>
      </div>
      
      <h1 class="h3 mb-3 fw-normal">Đăng nhập</h1>

      <div class="form-floating">
        <input name="username" type="text" class="form-control" id="floatingInput" placeholder="Tên đăng nhập">
        <label for="floatingInput">Tên đăng nhập</label>
      </div>
      <div class="form-floating position-relative mb-3">
        <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Mật khẩu">
        <label for="floatingPassword">Mật khẩu</label>
        <span class="position-absolute top-50 end-0 pe-3" id="togglePassword" style="cursor: pointer;">
          <i class="fa fa-eye"></i>
        </span>
      </div>

      <div class="checkbox">
        <label>
          <input type="checkbox" value="remember-me"> Remember me
        </label>
      </div>
      <button class="w-100 btn btn-lg btn-success mb-5" type="submit">Đăng nhập</button>
      <div class="sign_up">
        Bạn chưa có tài khoản? Đăng kí
        <a href="<?= ROOT ?>/signup">tại đây</a>
      </div>
      <script>
        const passwordInput = document.querySelector('#floatingPassword');
        const togglePassword = document.querySelector('#togglePassword');
        togglePassword.addEventListener('click', function () {
          const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
          passwordInput.setAttribute('type', type);
          togglePassword.querySelector('i').classList.toggle('fa-eye');
          togglePassword.querySelector('i').classList.toggle('fa-eye-slash');
          document.getElementById("floatingPassword").focus();
        });
      </script>
    </form>
  </main>
</body>
</html>