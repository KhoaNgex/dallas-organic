<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Dallas Organic">
    <meta name="keywords" content="organic, dallas, vegetable, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dallas Organic</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="../../public/css/resources/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="../../public/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="../../public/css/resources/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="../../public/css/resources/nice-select.css" type="text/css">
    <link rel="stylesheet" href="../../public/css/resources/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet" href="../../public/css/resources/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="../../public/css/resources/slicknav.min.css" type="text/css">

    <link rel="stylesheet" href="../../public/css/main.css" type="text/css">
    <link rel="stylesheet" href="../../public/css/components/header.css" type="text/css">
    <link rel="stylesheet" href="../../public/css/components/footer.css" type="text/css">
    <link rel="stylesheet" href="../../public/css/pages/home.css" type="text/css">
    <script src="../../public/js/main.js"></script>
</head>

<body>
    <?php
    include("../components/preload.php");
    include("../components/humberger.php");
    include("../components/header.php");
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
                            <!-- <?php
                            if (!empty($category_list)) {
                                foreach ($category_list as $category) {
                                    echo '<li><a href="#">' . $category->cate_name . '</a></li>';
                                }
                            }
                            ?> -->
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
                        <ul class="carousel-indicators">
                            <li data-target="#hero-slider" data-slide-to="0" class="active"></li>
                            <li data-target="#hero-slider" data-slide-to="1"></li>
                        </ul>

                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <div class="hero__item set-bg" data-setbg="../../public/images/slide-home-1.jpg">
                                    <div class="hero__text">
                                        <span>TRÁI CÂY SẠCH</span>
                                        <h2>Tươi ngon <br />100% Organic</h2>
                                        <p>Miễn phí giao hàng với đơn có số lượng lớn</p>
                                        <a href="#" class="primary-btn">KHÁM PHÁ NGAY</a>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="hero__item set-bg" data-setbg="../../public/images/slide-home-2.jpg">
                                    <div class="hero__text">
                                        <span>VƯỜN RAU HỮU CƠ</span>
                                        <h2>Xanh sạch<br />100% Nature</h2>
                                        <p>Nông trại chúng tôi nói không với thuốc bảo vệ thực vật</p>
                                        <a href="#" class="primary-btn">Thăm nông trại</a>
                                    </div>
                                </div>
                            </div>
                        </div>

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
    include("../components/footer.php");
    ?>

    <script src="../../public/js/plugin/jquery-3.3.1.min.js"></script>
    <script src="../../public/js/plugin/bootstrap.min.js"></script>
    <script src="../../public/js/plugin/jquery.nice-select.min.js"></script>
    <script src="../../public/js/plugin/jquery-ui.min.js"></script>
    <script src="../../public/js/plugin/jquery.slicknav.js"></script>
    <script src="../../public/js/plugin/mixitup.min.js"></script>
    <script src="../../public/js/plugin/owl.carousel.min.js"></script>
</body>

</html>