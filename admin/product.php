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
        <div class="main-content main-content-product">
            <h2>Quản lý sản phẩm</h2>
            <a href="product_add.php"><button
                    style="background-color: #3cba3c; margin-top: 20px; padding: 15px 20px;">Thêm sản
                    phẩm</button></a>
            <input type="text" placeholder="Tìm kiếm">
            <button style="min-width: 40px; padding: 12px 12px; border-radius: 20px; background-color: green;"><i
                    class="fa fa-search"></i></button>

            <div style="display: inline-block;">
                <select name="cate" id="cate" style="width: 200px;">
                    <option value="" style="display: none">Sản phẩm theo loại</option>
                    <?php
                        while ($row = mysqli_fetch_assoc($result_category)) {
                    ?>
                        <option value="<?php echo $row["id"]?>"><?php echo $row["cate_name"]?></option>
                    <?php
                    }
                    ?>
                </select>
                <button style="min-width: 40px; padding: 12px 12px; border-radius: 20px; background-color: green;" onClick="directToProductCate()">
                    <i class="fa fa-search"></i>
                </button>
            </div>

            <div style="margin-top: 20px;">
                <nav aria-label="Page navigation">
                    <ul class="pagination" id="pagination"></ul>
                </nav>
            </div>

            <table id="product-list">
            </table>
            <div class="delete-modal">
                <div class="delete-modal-container">
                    <p>Bạn chắc chắn muốn xóa sản phẩm?</p>
                    <div style="text-align: center;">
                        <button style="margin-right: 20px; background-color: #ff7800a3; color: #000;"
                            onClick="deleteModal()">Xác nhận</button>
                        <button style="margin-left: 20px; color: #000;" onClick="removeDeleteModal()">Quay lại</button>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script src="admin.js"></script>
        <script src="js/APIs/product.js"></script>
        <script src="js/tools/jquery.twbsPagination.js"></script>
    </div>
</body>

</html>