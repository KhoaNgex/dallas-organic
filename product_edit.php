<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Edition</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="./assets/fonts/themify-icons-font/themify-icons/themify-icons.css">
</head>
<body>
    <?php
        include('dbconnection.php');
        $query_product = 'SELECT * from products, category WHERE product_id ='. $_GET['id'];
        $query_category = 'SELECT * from category';
        $result_product = mysqli_query($link, $query_product);
        $result_category = mysqli_query($link, $query_category);
        $product = mysqli_fetch_array($result_product);

    ?>
    <div class="container">
        <?php 
            include('components/navbar.php');
            include('components/sidebar.php');
        ?>
        <div class="main-content main-content-productedit">
            <h2>Chỉnh sửa sản phẩm</h2>
            <form>
                <div>
                    <label for="product-name">Tên sản phẩm<span style="color: red">*</span>:</label>
                    <input type="text" name="product-name" required value="<?php echo $product["product_name"]?>">
                </div>
                <div>
                    <label for="product-cate">Phân loại:<span style="color: red">*</span>:</label>
                    <select name="product-cate">
                        <?php
                            while ($row = mysqli_fetch_assoc($result_category)) {
                        ?>
                        <option 
                            value="<?php echo $row["cate_id"]?>"
                            <?php
                                if ($row["cate_id"] == $product["category_id"]) {
                                    echo "selected";
                                }
                            ?>
                        >
                            <?php echo $row["cate_name"]?>
                        </option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <div>
                    <label for="product-price">Giá<span style="color: red">*</span>:</label>
                    <input type="number" name="product-price" required value="<?php echo $product["price"]?>">
                </div>
                <div>
                    <label for="product-unit">Đơn vị<span style="color: red">*</span>:</label>
                    <input type="text" name="product-unit" required value="<?php echo $product["unit"]?>">
                </div>
                <div>
                    <label for="product-origin">Xuất xứ<span style="color: red">*</span>:</label>
                    <input type="text" name="product-origin" required value="<?php echo $product["origin"]?>">
                </div>
                <div>
                    <label for="product-remain">Kho<span style="color: red">*</span>:</label>
                    <input type="number" name="product-remain" required value="<?php echo $product["remain_number"]?>">
                </div>
                <div>
                    <label for="product-image">Link hình ảnh<span style="color: red">*</span>:</label>
                    <input type="text" name="product-image" required value="<?php echo $product["image"]?>">
                </div>
                <div>
                    <label for="product-description">Mô tả<span style="color: red">*</span>:</label>
                    <textarea name="product-description" required><?php echo $product["description"]?></textarea>
                </div>
                <a class="btn" style="margin: 10px 30px 0 0; background-color: green;" onClick="turnBack()">Xác nhận</a>
                <a class="btn" style="background-color: blue;" onClick="turnBack()">Quay lại</a>
            </form>
        </div>
    </div>
    <script src="admin.js"></script>
</body>
</html>