<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Addition</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="./assets/fonts/themify-icons-font/themify-icons/themify-icons.css">
</head>
<body>
    <?php
        include('dbconnection.php');
        $query_category = 'SELECT * from category';
        $result_category = mysqli_query($link, $query_category);
    ?>
    <div class="container">
        <?php 
            include('components/navbar.php');
            include('components/sidebar.php');
        ?>
        <div class="main-content main-content-productadd">
        <h2>Thêm sản phẩm</h2>
            <form>
                <div>
                    <label for="product-name">Tên sản phẩm<span style="color: red">*</span>:</label>
                    <input type="text" name="product-name" required placeholder="Tên sản phẩm...">
                </div>
                <div>
                    <label for="product-cate">Phân loại:<span style="color: red">*</span>:</label>
                    <select name="product-cate">
                        <option value="" style="display: none">Phân loại</option>
                        <?php
                            while ($row = mysqli_fetch_assoc($result_category)) {
                        ?>
                        <option value="<?php echo $row["cate_id"]?>">
                            <?php echo $row["cate_name"]?>
                        </option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <div>
                    <label for="product-price">Giá<span style="color: red">*</span>:</label>
                    <input type="number" name="product-price" required placeholder="Giá...">
                </div>
                <div>
                    <label for="product-unit">Đơn vị<span style="color: red">*</span>:</label>
                    <input type="text" name="product-unit" required placeholder="Đơn vị...">
                </div>
                <div>
                    <label for="product-origin">Xuất xứ<span style="color: red">*</span>:</label>
                    <input type="text" name="product-origin" required placeholder="Xuất xứ">
                </div>
                <div>
                    <label for="product-remain">Kho<span style="color: red">*</span>:</label>
                    <input type="number" name="product-remain" required placeholder="Kho...">
                </div>
                <div>
                    <label for="product-image">Link hình ảnh<span style="color: red">*</span>:</label>
                    <input type="text" name="product-image" required placeholder="Link hình ảnh...">
                </div>
                <div>
                    <label for="product-description">Mô tả<span style="color: red">*</span>:</label>
                    <textarea name="product-description" required></textarea>
                </div>
                <a class="btn" style="margin: 10px 30px 0 0; background-color: green;" onClick="turnBack()">Xác nhận</a>
                <a class="btn" style="background-color: blue;" onClick="turnBack()">Quay lại</a>
            </form>
        </div>
    </div>
    <script src="admin.js"></script>
</body>
</html>