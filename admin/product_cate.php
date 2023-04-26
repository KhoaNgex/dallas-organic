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
            include('dbconnection.php');
            $query_category = 'SELECT * from category';
            $result_category = mysqli_query($link, $query_category);
        ?>
        <?php 
            include('components/navbar.php');
            include('components/sidebar.php');
        ?>
        <div class="main-content main-content-product">
            <h2>Quản lý sản phẩm</h2>
            <a href="product_add.php"><button style="background-color: #3cba3c; margin-top: 20px; padding: 15px 20px;">Thêm sản phẩm</button></a>
            <input type="text" placeholder="Tìm kiếm">
            <button style="min-width: 40px; padding: 12px 12px; border-radius: 20px; background-color: green;"><i class="fa fa-search"></i></button>
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
            <table>
                <tr>
                    <th>ID</th>
                    <th  style="width: 35%">Tên sản phẩm</th>
                    <th>Loại</th>
                    <th>Giá</th>
                    <th>Đơn vị</th>
                    <th>Đã bán</th>
                    <th>Kho</th>
                    <th>Thao tác</th>
                </tr>
                <?php
                    include('dbconnection.php');
                    $query = 'SELECT products.id, product_name, cate_name, price, unit, sold_number, remain_number 
                            from products, category WHERE category_id = category.id && category.id ='. $_GET['cate_id'];
                    $result = mysqli_query($link, $query);
                ?>
                <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                    <tr>
                        <td><?php echo $row["id"]?></td>
                        <td style="width: 35%"><?php echo $row["product_name"]?></td>
                        <td><?php echo $row["cate_name"]?></td>
                        <td><?php echo $row["price"]?></td>
                        <td><?php echo $row["unit"]?></td>
                        <td><?php echo $row["sold_number"]?></td>
                        <td><?php echo $row["remain_number"]?></td>
                        <td>
                            <a href=<?php echo "product_detail.php?id=".$row["id"] ?>><button style="background-color: blue">Chi tiết</button></a>
                            <a href=<?php echo "product_edit.php?id=".$row["id"] ?>><button style="background-color: orange">Chỉnh sửa</button></a>
                            <button style="background-color: red" onClick="displayDeleteModal()">Xóa</button>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </table>
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
    </div>
</body>

</html>