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

    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/resources/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/resources/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/resources/nice-select.css" type="text/css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/resources/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/resources/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/resources/slicknav.min.css" type="text/css">

    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/main.css?v=1" type="text/css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/components/header.css" type="text/css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/components/footer.css" type="text/css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/pages/product.css?v=1" type="text/css">
</head>

<body>
    <?php
    include("components/preload.php");
    include("components/humberger.php");
    include("components/header.php");
    ?>

    <section class="hero">
        <div class="breadcrumb-container">
            <div class="breadcrumb__subtitle-group">
                <span class="breadcrumb__page-subtitle"><a class="breadcrumb__subtitle-link" href='#'>Trang chủ
                    </a></span>
                <span class="breadcrumb__page-subtitle breadcrumb__page-subtitle--main">| Cửa hàng</span>
            </div>
            <h1 class="breadcrumb__page-title">Sản phẩm</h1>
        </div>
    </section>

    <section class="product-list">
        <div class="row justify-content-center">
            <div class="col-md-10 mb-5 text-center">
                <ul class="product-category">
                    <li><a href="<?= ROOT ?>/shop" id="cate-0">Tất cả</a>
                    </li>
                    <ul>
                        <?php
                        $my_url = $_GET['url'];
                        $URL = explode("/", trim($my_url, "/"));
                        if (count($URL) > 1) {
                            $add_url = 'cate-';
                        } else {
                            $add_url = $my_url . '/cate-';
                        }
                        if (!empty($category_list)) {
                            foreach ($category_list as $category) {
                                echo '<li><a id="cate-' . $category->id . '" onclick="return filterCate(\'' . $add_url . '\',\'' . $category->id . '\');">' . $category->cate_name . '</a></li>';
                            }
                        }
                        ?>
                    </ul>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-lg-2 product-filter-container">
                <div class="product-sort-engine">
                    <div class="product-sort-engine__criteria">
                        <p style="font-size: 20px; font-weight: 700; color: #111;">Sắp xếp bởi</p>
                        <input type="radio" id="price" name="sort_field" value="price">
                        <label for="price">Giá cả</label><br>
                        <input type="radio" id="hot" name="sort_field" value="sold_number">
                        <label for="hot">Bán chạy</label><br>
                        <input type="radio" id="remain" name="sort_field" value="remain_number">
                        <label for="remain">Số lượng có</label><br>
                    </div>
                    <hr class="solid">
                    <div class="product-sort-engine__direction">
                        <p style="font-weight: 600; color: #111;">Thứ tự</p>
                        <input type="radio" id="asc" name="sort_direct" value="asc">
                        <label for="asc">Tăng dần</label><br>
                        <input type="radio" id="desc" name="sort_direct" value="desc">
                        <label for="hot">Giảm dần</label><br>
                    </div>
                    <button class="product-sort-engine__btn primary-btn">Áp dụng</button>
                </div>
            </div>
            <div class="col-12 col-lg-10">
                <ul id="paginated-list" data-current-page="1" aria-live="polite" class="row current-products">
                    <?php
                    foreach ($product_list as $product) {
                        echo '<li class="col-md-6 col-lg-3 product-id-' . $product->category_id . '">';
                        echo '<div class="product">';
                        echo '<a href="#" class="product-img">';
                        echo '<img class="product-img__fluid" src="' . $product->image . '" alt="product">';
                        echo '</a>';

                        echo '<div class="product-info">';
                        echo '<p class="product-name-container"><a href="#" class="product-name">' . $product->product_name . '</a></p>';
                        echo '<p class="product-price">' . number_format($product->price) . 'đ</p>';
                        echo '</div>';
                        echo '</div>';
                        echo '</li>';
                    }
                    ?>
                </ul>
            </div>
        </div>

        <nav class="pagination-container">
            <button class="pagination-button" id="prev-button" aria-label="Previous page" title="Previous page">
                &lt;
            </button>

            <div id="pagination-numbers">

            </div>

            <button class="pagination-button" id="next-button" aria-label="Next page" title="Next page">
                &gt;
            </button>
        </nav>

        </div>
    </section>


    <?php
    include("components/footer.php");
    ?>

    <!-- Js Plugins -->
    <script src="<?= ROOT ?>/assets/js/plugin/jquery-3.3.1.min.js"></script>
    <script src="<?= ROOT ?>/assets/js/plugin/bootstrap.min.js"></script>
    <script src="<?= ROOT ?>/assets/js/plugin/jquery.nice-select.min.js"></script>
    <script src="<?= ROOT ?>/assets/js/plugin/jquery-ui.min.js"></script>
    <script src="<?= ROOT ?>/assets/js/plugin/jquery.slicknav.js"></script>
    <script src="<?= ROOT ?>/assets/js/plugin/mixitup.min.js"></script>
    <script src="<?= ROOT ?>/assets/js/plugin/owl.carousel.min.js"></script>
    <script src="<?= ROOT ?>/assets/js/main.js"></script>
    <script src="<?= ROOT ?>/assets/js/tools/productFilter.js"></script>
    <script src="<?= ROOT ?>/assets/js/tools/pagination.js"></script>
</body>

</html>