<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dallas Organic - Admin</title>
    <link rel="icon" type="image/x-icon" href="https://icon-library.com/images/organic-icon-png/organic-icon-png-4.jpg">
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
        <div class="main-content main-content-productadd">
            <h2>Thêm sản phẩm</h2>
            <form id="product-form">
                <div>
                    <label for="product-name">Tên sản phẩm<span style="color: red">*</span>:</label>
                    <input type="text" name="product-name" required placeholder="Tên sản phẩm...">
                </div>
                <div>
                    <label for="product-cate">Phân loại:<span style="color: red">*</span>:</label>
                    <select name="product-cate" id="product-cate">

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
                <button type="submit" style="margin: 10px 30px 0 0; padding: 15px 20px; background-color: green;">Xác nhận</button>
                <a class="btn" style="background-color: gray; padding: 15px 20px;" onClick="turnBack()">Quay lại</a>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script src="admin.js"></script>
    <script src="js/APIs/product-add.js"></script>
</body>

</html>