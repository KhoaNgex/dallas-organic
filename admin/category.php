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
            if(isset($_POST['cate-name-edit-btn'])) {
                $new_name = $_POST['cate-name-edit'];
                $cate_id = $_POST['cate-id-edit'];
                include('dbconnection.php');
                $query = "UPDATE category SET cate_name='$new_name' WHERE id = $cate_id";
                mysqli_query($link, $query);
                mysqli_close($link);
            }
        ?>
        <?php 
            include('components/navbar.php');
            include('components/sidebar.php');
        ?>
        <div class="main-content main-content-product">
            <h2>Quản lý phân loại sản phẩm</h2>
            <form action="category_add.php" method="POST">
                <input name="cate-name-add" type="text" placeholder="Thêm loại sản phẩm..." style="margin-left: 0">
                <button name="cate-name-add-btn" style="background-color: #3cba3c; margin-top: 20px; padding: 15px 20px;">Thêm</button>
            </form>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Phân Loại</th>
                    <th>Số lượng mặt hàng</th>
                    <th>Đã bán</th>
                    <th>Kho</th>
                    <th style="width: 530px;">Thao tác</th>
                </tr>
                <?php
                    include('dbconnection.php');
                    $query = 'SELECT category_id, cate_name, COUNT(*) as numofproducts, SUM(sold_number) as numofsold, SUM(remain_number) as numofremain 
                            from products, category WHERE products.category_id = category.id
                            GROUP by category_id';
                    $result = mysqli_query($link, $query);
                ?>
                <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                    <tr>
                        <td><?php echo $row["category_id"]?></td>
                        <td style="font-weight: bold"><?php echo $row["cate_name"]?></td>
                        <td><?php echo number_format($row["numofproducts"])?></td>
                        <td><?php echo number_format($row["numofsold"])?></td>
                        <td><?php echo number_format($row["numofremain"])?></td>
                        <td>
                            <a href=<?php echo "product_cate.php?cate_id=".$row["category_id"] ?>><button style="background-color: blue">Chi tiết</button></a>
                            <form action="" method="POST" style="display: inline-block;">
                                <input name="cate-id-edit" style="display: none" value=<?php echo $row["category_id"]?>>
                                <input name="cate-name-edit" type="text" required placeholder="Sửa tên phân loại..." style="width: 230px; margin-left: 0;">
                                <button name="cate-name-edit-btn" style="background-color: orange">Chỉnh sửa</button>
                            </form>
                            <div style="display: inline-block;">
                                <input style="display: none" value=<?php echo $row["category_id"]?>>
                                <button style="background-color: red"  onClick="displayConfirmDeleteModal('<?php echo "delmodal-".$row["category_id"]?>')">Xóa</button>
                                <div class="delete-modal" id="<?php echo "delmodal-".$row["category_id"]?>">
                                    <div class="delete-modal-container">
                                        <p>Bạn chắc chắn muốn xóa loại sản phẩm?</p>
                                        <div style="text-align: center;">
                                            <a href=<?php echo "category_delete.php?id=".$row["category_id"]?>><button style="margin-right: 50px; background-color: #ff7800a3; color: #000;">Xác nhận</button></a>
                                            <button style="margin-left: 50px; color: #000;" onClick="removeDeleteModal()">Quay lại</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php
                }
                mysqli_close($link);
                ?>
                <?php
                    include('dbconnection.php');
                    $query = 'SELECT id, cate_name from category 
                            WHERE id NOT IN (SELECT category_id from products, category WHERE category_id = category.id)';
                    $result = mysqli_query($link, $query);
                ?>
                <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                    <tr>
                        <td><?php echo $row["id"]?></td>
                        <td style="font-weight: bold"><?php echo $row["cate_name"]?></td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>
                            <a href=<?php echo "product_cate.php?cate_id=".$row["id"] ?>><button style="background-color: blue">Chi tiết</button></a>
                            <form action="" method="POST" style="display: inline-block;">
                                <input name="cate-id-edit" style="display: none" value=<?php echo $row["id"]?>>
                                <input name="cate-name-edit" type="text" required placeholder="Sửa tên phân loại..." style="width: 230px; margin-left: 0;">
                                <button name="cate-name-edit-btn" style="background-color: orange">Chỉnh sửa</button>
                            </form>
                            <div style="display: inline-block;">
                                <input style="display: none" value=<?php echo $row["id"]?>>
                                <button style="background-color: red"  onClick="displayConfirmDeleteModal('<?php echo "delmodal-".$row["id"]?>')">Xóa</button>
                                <div class="delete-modal" id="<?php echo "delmodal-".$row["id"]?>">
                                    <div class="delete-modal-container">
                                        <p>Bạn chắc chắn muốn xóa loại sản phẩm?</p>
                                        <div style="text-align: center;">
                                            <a href=<?php echo "category_delete.php?id=".$row["id"]?>><button style="margin-right: 50px; background-color: #ff7800a3; color: #000;">Xác nhận</button></a>
                                            <button style="margin-left: 50px; color: #000;" onClick="removeDeleteModal()">Quay lại</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php
                }
                mysqli_close($link);
                ?>
            </table>
        </div>
        
        <script>
            function displayConfirmDeleteModal(value) {
                console.log(value);
                document.getElementById(value).classList.add("open");
            }

        </script>
        <script src="admin.js"></script>
    </div>
</body>

</html>