<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Dallas Organic">
    <meta name="keywords" content="organic, dallas, vegetable, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dallas Organic</title>

    <link rel="icon" type="image/jpeg" href="<?= ROOT ?>/assets/favicon.jpeg" sizes="96x96">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/frameworks/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/frameworks/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/frameworks/nice-select.css" type="text/css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/frameworks/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/frameworks/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/frameworks/slicknav.min.css" type="text/css">
    
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/main.css" type="text/css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/components/header.css" type="text/css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/components/footer.css" type="text/css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/pages/home.css" type="text/css">
</head>

<body>
    <?php
    include("components/preload.php");
    include("components/humberger.php");
    include("components/header.php");
    ?>

    <section class="hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="hero__categories">
                        <div class="hero__categories__all">
                            <i class="fa fa-bars"></i>
                            <span>Danh mục</span>
                        </div>
                        <ul>
                            <li><a href="#">Rau củ tươi</a></li>
                            <li><a href="#">Trái cây</a></li>
                            <li><a href="#">Nấm</a></li>
                            <li><a href="#">Gia vị</a></li>
                            <li><a href="#">Đậu đỗ</a></li>
                        </ul>
                    </div>
                    <div class="hero__search__phone">
                        <div class="hero__search__phone__icon">
                            <i class="fa fa-phone"></i>
                        </div>
                        <div class="hero__search__phone__text">
                            <h5>(+84)62.646.979</h5>
                            <span>Hỗ trợ 24/7</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div id="hero-slider" class="carousel slide" data-ride="carousel">
                        <!-- Indicators -->
                        <ul class="carousel-indicators">
                            <li data-target="#hero-slider" data-slide-to="0" class="active"></li>
                            <li data-target="#hero-slider" data-slide-to="1"></li>
                        </ul>

                        <!-- The slideshow -->
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <div class="hero__item set-bg"
                                    data-setbg="https://images.unsplash.com/photo-1535228482415-b728d6e690c4?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxfDB8MXxyYW5kb218MHx8fHx8fHx8MTY2NjkwNTE0Mg&ixlib=rb-4.0.3&q=80&utm_campaign=api-credit&utm_medium=referral&utm_source=unsplash_source&w=1080">
                                    <div class="hero__text">
                                        <span>TRÁI CÂY SẠCH</span>
                                        <h2>Tươi ngon <br />100% Organic</h2>
                                        <p>Miễn phí giao hàng với đơn có số lượng lớn</p>
                                        <a href="#" class="primary-btn">KHÁM PHÁ NGAY</a>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="hero__item set-bg"
                                    data-setbg="https://firebasestorage.googleapis.com/v0/b/freshmeals-reactjs.appspot.com/o/slideHeader.jpg?alt=media&token=d897daf6-1dd8-47d9-98f7-28d6d1b63695">
                                    <div class="hero__text">
                                        <span>VƯỜN RAU HỮU CƠ</span>
                                        <h2>Xanh sạch<br />100% Nature</h2>
                                        <p>Nông trại chúng tôi nói không với thuốc bảo vệ thực vật</p>
                                        <a href="#" class="primary-btn">Thăm nông trại</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Left and right controls -->
                        <a class="carousel-control-prev" href="#hero-slider" data-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </a>
                        <a class="carousel-control-next" href="#hero-slider" data-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
    include("components/footer.php");
    ?>

    <!-- Js Plugins -->
    <script src="<?= ROOT ?>/assets/js/frameworks/jquery-3.3.1.min.js"></script>
    <script src="<?= ROOT ?>/assets/js/frameworks/bootstrap.min.js"></script>
    <script src="<?= ROOT ?>/assets/js/frameworks/jquery.nice-select.min.js"></script>
    <script src="<?= ROOT ?>/assets/js/frameworks/jquery-ui.min.js"></script>
    <script src="<?= ROOT ?>/assets/js/frameworks/jquery.slicknav.js"></script>
    <script src="<?= ROOT ?>/assets/js/frameworks/mixitup.min.js"></script>
    <script src="<?= ROOT ?>/assets/js/frameworks/owl.carousel.min.js"></script>
    <script src="<?= ROOT ?>/assets/js/main.js"></script>
</body>

</html>