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

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>

    
    <!-- Custom styles for this template -->
    <link href="<?=ROOT?>/assets/css/pages/signin.css" rel="stylesheet">
  </head>
  <body class="text-center">
    
<main class="form-signin">
  <form method="post">

    <?php if(!empty($errors)):?>
      <div class="alert alert-danger">
        <?= implode("<br>", $errors)?>
      </div>
    <?php endif;?>
  
    <h1 class="h3 mb-3 fw-normal">Create account</h1>

    <div class="form-floating">
      <input name="email" type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
      <label for="floatingInput">Email address</label>
    </div>
    <div class="form-floating">
      <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password">
      <label for="floatingPassword">Password</label>
    </div>

    <div class="checkbox mb-3">
      <label>
        <input name="terms" type="checkbox" value="1"> Accept terms
      </label>
    </div>
    <button class="w-100 btn btn-lg btn-primary" type="submit">Create</button>
    <a href="<?=ROOT?>">Home</a>
    <a href="<?=ROOT?>/login">Login</a>
    <p class="mt-5 mb-3 text-muted">&copy; 2017-2021</p>
  </form>
</main>


    
  </body>
</html>
