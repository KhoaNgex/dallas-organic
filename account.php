<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Management</title>
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
            <h2>Quản lý Tài khoản</h2>
            <a href="#"><button style="background-color: #3cba3c; margin-top: 20px;">Thêm tài khoản</button></a>
            <input type="text" placeholder="Tìm kiếm">
            <button style="min-width: 40px; padding: 12px 12px; border-radius: 20px; background-color: green;"><i class="fa fa-search"></i></button>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Tên đăng nhập</th>
                    <th>Tên người dùng</th>
                    <th>SĐT</th>
                    <th>Email</th>
                    <th>Quyền</th>
                    <th>Thao tác</th>
                </tr>
                <?php
                    include('dbconnection.php');
                    $query = 
                        'SELECT userID, username, fullname, phonenumber, email, "Khách hàng" AS func
                        from user_account, customer_account where userID = userID_customer
                        UNION
                        SELECT userID, username, fullname, phonenumber, email, "Admin" AS func
                        from user_account, admin_account where userID = userID_admin
                        ORDER BY userID
                        ';
                    // $query = 'SELECT * from products, category WHERE category_id = cate_id';
                    $result = mysqli_query($link, $query);
                ?>
                <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                    <tr>
                        <td><?php echo $row["userID"]?></td>
                        <td><?php echo $row["username"]?></td>
                        <td><?php echo $row["fullname"]?></td>
                        <td><?php echo $row["phonenumber"]?></td>
                        <td><?php echo $row["email"]?></td>
                        <td><?php echo $row["func"]?></td>
                        <td>
                            <a href=<?php echo "product_detail.php?id=".$row["userID"] ?>><button style="background-color: blue">Chi tiết</button></a>
                            <a href=<?php echo "product_edit.php?id=".$row["userID"] ?>><button style="background-color: orange">Chỉnh sửa</button></a>
                            <button style="background-color: red" onClick="displayDeleteModal()">Xóa</button>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </table>
        </div>
    </div>
    <script src="admin.js"></script>
</body>
</html>