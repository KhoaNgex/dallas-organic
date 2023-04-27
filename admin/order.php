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
            if(isset($_POST['status-edit-btn'])) {
                $new_status = $_POST['status-edit'];
                $order_id = $_POST['order-id-edit'];
                include('dbconnection.php');
                $query = "UPDATE orders SET order_status='$new_status' WHERE id = $order_id";
                mysqli_query($link, $query);
                mysqli_close($link);
            }
        ?>
        <?php 
            include('components/navbar.php');
            include('components/sidebar.php');
        ?>
        <div class="main-content main-content-order">
            <h2>Quản lý Đơn hàng</h2>
            <input style="margin: 20px 0 0 0;" type="text" placeholder="Tìm kiếm">
            <button style="min-width: 40px; padding: 12px 12px; border-radius: 20px; background-color: green;"><i class="fa fa-search"></i></button>
        <table>
            <tr>
                <th style="width: 50px;">Mã đơn hàng</th>
                <th style="width: 180px;">Họ tên người nhận</th>
                <th>Tài khoản</th>
                <th>Số điện thoại</th>
                <th>Ngày đặt</th>
                <th>Tổng đơn</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
            <?php
                include('dbconnection.php');
                $query = 'SELECT * 
                FROM (
                    (SELECT * 
                    FROM(
                        (SELECT orderID, sum(price*quantity) AS total from products, ordered_product WHERE id = productID GROUP BY orderID) E 
                        JOIN 
                        orders F
                        ON E.orderID = F.id
                    )) X 
                    JOIN 
                    user_account Y 
                    ON X.userID_ordcus = Y.id
                )';
                $result = mysqli_query($link, $query); 
            ?>
            <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    $total = $row["total"] + $row["ship_fee"]
            ?>
                <tr>
                    <td><?php echo $row["orderID"]?></td>
                    <td><?php echo $row["fullname"]?></td>
                    <td><?php echo $row["username"]?></td>
                    <td><?php echo $row["recieve_phonenum"]?></td>
                    <td><?php echo $row["order_date"]?></td>
                    <td><?php echo $total." vnđ"?></td>
                    <?php
                    if($row["order_status"] == "Đang chuẩn bị") {
                        echo "<td style=\"color: red\">".$row["order_status"]."</td>";
                    }
                    else if($row["order_status"] == "Đang giao hàng") {
                        echo "<td style=\"color: #cbcb22\">".$row["order_status"]."</td>";
                    }
                    else if($row["order_status"] == "Hoàn thành") {
                        echo "<td style=\"color: green\">".$row["order_status"]."</td>";
                    }
                    else if($row["order_status"] == "Đã hủy") {
                        echo "<td style=\"color: Grey\">".$row["order_status"]."</td>";
                    }
                    ?>
                    <td>
                    <a href=<?php echo "order_detail.php?order_id=".$row["orderID"] ?>><button style="background-color: blue">Chi tiết</button></a>
                    <form action="" method="POST" style="display: inline-block;">
                        <input name="order-id-edit" style="display: none" value=<?php echo $row["orderID"]?>>
                        <select name="status-edit" id=""  style="width: 170px; margin-left: 0;">
                            <option value="Đang chuẩn bị" <?php if($row["order_status"] == "Đang chuẩn bị") {echo "selected";}?>>Đang chuẩn bị</option>
                            <option value="Đang giao hàng" <?php if($row["order_status"] == "Đang giao hàng") {echo "selected";}?>>Đang giao hàng</option>
                            <option value="Hoàn thành" <?php if($row["order_status"] == "Hoàn thành") {echo "selected";}?>>Hoàn thành</option>
                            <option value="Đã hủy" <?php if($row["order_status"] == "Đã hủy") {echo "selected";}?>>Đã hủy</option>
                        </select>
                        <button name="status-edit-btn" style="background-color: orange">Chỉnh sửa</button>
                    </form>
                    </td>
                </tr>
            <?php
            }
            mysqli_close($link);
            ?>
        </table>
        </div>
        <script src="admin.js"></script>
    </div>
</body>
</html>