<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="./assets/fonts/themify-icons-font/themify-icons/themify-icons.css">
</head>
<body>
    <div class="container">
        <?php 
            include('components/navbar.php');
            include('components/sidebar.php');
        ?>
        <div class="main-content main-content-productdetail">
            <h2>Chi tiết sản phẩm</h2>
            <div class="detail-container">
                <?php
                    include('dbconnection.php');
                    $query_product = 'SELECT * from products, category WHERE category_id = cate_id and product_id ='. $_GET['id'];
                    $result_product = mysqli_query($link, $query_product);
                    $product = mysqli_fetch_array($result_product);
                ?>
                <div class="big-img">
                    <img src=<?php echo $product['image']?> alt="">
                    <div class="btn-container">
                        <a href=<?php echo "product_edit.php?id=".$product["product_id"] ?>><button style="background-color: orange">Chỉnh sửa</button></a>
                        <button style="background-color: red" onClick="displayDeleteModal()">Xóa</button>
                        <a href="product.php"><button style="background-color: blue">Quay lại</button></a>
                    </div>
                </div>
                <div class="detail-info">
                    <h1><?php echo $product['product_name']?></h1>
                    <h3>Giá: <?php echo $product['price']?> đồng/<?php echo $product['unit']?></h3>
                    <p>Phân loại: <?php echo $product['cate_name']?></p>
                    <p>Đã bán: <?php echo $product['sold_number']?></p>
                    <p>Kho: <?php echo $product['remain_number']?></p>
                    <p>Xuất xứ: <?php echo $product['origin']?></p>
                </div>
                <div class="detail-desc">
                    <p>Mô tả:</p>
                    <p><?php echo $product['description']?></p>
                </div>
            </div>
        </div>
        <div class="delete-modal">
            <div class="delete-modal-container">
                <p>Bạn chắc chắn muốn xóa sản phẩm?</p>
                <div style="text-align: center;">
                    <button style="margin-right: 50px; background-color: #ff7800a3; color: #000;" onClick="removeDeleteModal()">Xác nhận</button>
                    <button style="margin-left: 50px; color: #000;" onClick="removeDeleteModal()">Quay lại</button>
                </div>
            </div>
        </div>
    </div>
    <script src="admin.js"></script>
</body>
</html>