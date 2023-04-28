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
        <div class="main-content main-content-order">
            <h2>Quản lý Blog</h2>
            <a href="blog_add.php"><button style="background-color: #3cba3c; margin-top: 20px; padding: 15px 20px;">Thêm Blog</button></a>
            <!-- <input style="margin: 20px 0 0 0;" type="text" placeholder="Tìm kiếm">
            <button style="min-width: 40px; padding: 12px 12px; border-radius: 20px; background-color: green;"><i class="fa fa-search"></i></button> -->
        <table>
            <tr>
                <th>ID</th>
                <th>Tiêu đề</th>
                <th>Thời gian đăng</th>
                <th style="width: 200px;">Thao tác</th>
            </tr>
            <?php
                include('dbconnection.php');
                $query = 'SELECT * FROM blogs';
                $result = mysqli_query($link, $query); 
            ?>
            <?php
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <tr>
                    <td><?php echo $row["id"]?></td>
                    <td  style="font-weight: bold"><?php echo $row["title"]?></td>
                    <td><?php echo $row["created_at"]?></td>
                    <td>
                        <a href=<?php echo "blog_detail.php?blog_id=".$row["id"] ?>><button style="background-color: blue">Chi tiết</button></a>
                        <div style="display: inline-block;">
                            <button style="background-color: red"  onClick="displayConfirmDeleteModal('<?php echo "delmodal-".$row["id"]?>')">Xóa</button>
                            <div class="delete-modal" id="<?php echo "delmodal-".$row["id"]?>">
                                <div class="delete-modal-container">
                                    <p>Bạn chắc chắn muốn xóa loại sản phẩm?</p>
                                    <div style="text-align: center;">
                                        <a href=<?php echo "blog_delete.php?id=".$row["id"]?>><button style="margin-right: 50px; background-color: #ff7800a3; color: #000;">Xác nhận</button></a>
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
        <div class="delete-modal">
            <div class="delete-modal-container">
                <p>Bạn chắc chắn muốn xóa sản phẩm?</p>
                <div style="text-align: center;">
                    <a><button style="margin-right: 50px; background-color: #ff7800a3; color: #000;">Xác nhận</button></a>
                    <button style="margin-left: 50px; color: #000;" onClick="removeDeleteModal()">Quay lại</button>
                </div>
            </div>
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