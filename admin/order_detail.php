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
            if(isset($_POST['recieve-btn'])) {
                $id = $_POST['order-id'];
                $new_phonenum = $_POST['reciever-phone'];
                $new_address = $_POST['reciever-address'];
                $new_note = $_POST['reciever-note'];
                include('dbconnection.php');
                $query0 = "UPDATE orders SET recieve_phonenum='$new_phonenum', recieve_address='$new_address', note='$new_note' WHERE id = $id";
                mysqli_query($link, $query0);
                mysqli_close($link); 
            }
        ?>
        <?php
        include('components/navbar.php');
        include('components/sidebar.php');
        ?>
        <?php
        include('dbconnection.php');
        $query = 'SELECT * FROM	
        (SELECT * 
        FROM (
            (SELECT E.orderID, E.total, F.recieve_address, F.recieve_phonenum, F.note, F.order_date, F.order_status, F.ship_fee, F.userID_ordcus
            FROM(
                (SELECT orderID, sum(price*quantity) AS total from products, ordered_product WHERE id = productID GROUP BY orderID) E 
                JOIN 
                orders F
                ON E.orderID = F.id
            )) X 
            JOIN 
            user_account Y 
            ON X.userID_ordcus = Y.id
        )) LASTTABLE WHERE LASTTABLE.orderID ='. $_GET["order_id"];
        $result = mysqli_query($link, $query);
        $order = mysqli_fetch_array($result);
        ?>
        <div class="main-content main-content-orderdetail">
            <h2>Thông tin đơn hàng</h2>
            <div class="orderdetail-content-container">
                <div class="fixed-order-infor">
                    <div>
                        <p style="display: inline-block; width: 180px; font-weight: bold;">Mã đơn hàng:</p>
                        <p style="display: inline-block"><?php echo $order["orderID"];?></p>
                    </div>
                    <div>
                        <p style="display: inline-block; width: 180px; font-weight: bold;">Tài khoản đặt hàng:</p>
                        <p style="display: inline-block"><?php echo $order["username"];?></p>
                    </div>
                    <div>
                        <p style="display: inline-block; width: 180px; font-weight: bold;">Ngày đặt hàng:</p>
                        <p style="display: inline-block"><?php echo $order["order_date"];?></p>
                    </div>
                    <div>
                        <p style="display: inline-block; width: 180px; font-weight: bold;">Trạng thái:</p>
                        <?php
                        if($order["order_status"] == "Đang chuẩn bị") {
                            echo "<p style=\"display: inline-block; color: red\">".$order["order_status"]."</p>";
                        }
                        else if($order["order_status"] == "Đang giao hàng") {
                            echo "<p style=\"display: inline-block; color: #cbcb22\">".$order["order_status"]."</p>";
                        }
                        else if($order["order_status"] == "Hoàn thành") {
                            echo "<p style=\"display: inline-block; color: green\">".$order["order_status"]."</p>";
                        }
                        else if($order["order_status"] == "Đã hủy") {
                            echo "<p style=\"display: inline-block; color: grey\">".$order["order_status"]."</p>";
                        }
                        ?>
                    </div>
                    <div>
                        <p style="display: inline-block; width: 180px; font-weight: bold;">Thông tin sản phẩm:</p>
                        <div class="order-product-info-container">
                        <?php
                        include('dbconnection.php');
                        $query1 = 'SELECT productID, orderID, quantity, product_name, price, unit, image
                                    FROM ordered_product, products 
                                    WHERE productID = id and orderID ='.$_GET["order_id"];
                        $result1 = mysqli_query($link, $query1);
                        ?>
                        <?php
                            while ($row = mysqli_fetch_assoc($result1)) {
                                $total_product = $row["quantity"]*$row["price"];
                        ?>
                            <div class="order-product-item">
                                <div class="order-product-info">
                                    <p style="font-weight: bold;"><?php echo $row["quantity"]."x"?></p>
                                    <p style="font-weight: bold; font-size: 17px;"><?php echo $row["product_name"]?></p>
                                    <p><?php echo $total_product." vnđ"?></p>
                                </div>
                                <div class="order-product-img">
                                    <img src="<?php echo $row["image"]?>" alt="">
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                            <div>
                                <p style="margin-bottom: 10px;"><span style="display: inline-block; font-weight: bold; width: 130px;">Tiền hàng: </span><?php echo $order["total"]." vnđ"?></p>
                                <p style="margin-bottom: 10px;"><span style="display: inline-block; font-weight: bold; width: 130px;">Phí giao hàng: </span><?php echo $order["ship_fee"]." vnđ"?></p>
                                <hr>
                                <p style="margin-top: 10px;"><span style="display: inline-block; font-weight: bold; width: 130px;">Tổng cộng: </span><?php echo $order["total"] + $order["ship_fee"]." vnđ"?></p>
                            </div>
                        <?php
                        mysqli_close($link);
                        ?>
                        </div>
                    </div>
                </div>
                <div class="adjustable-order-infor">
                    <h3>Thông tin người nhận:</h3>
                    <form action="" method="POST">
                        <input name="order-id" value="<?php echo $_GET["order_id"];?>" style="display: none;">
                        <div>
                            <label for="reciever-name">Tên người nhận:</label>
                            <input name="reciever-name"  type="text" placeholder="Tên người nhận..." value="<?php echo $order["fullname"];?>" disabled>
                        </div>
                        <div>
                            <label for="reciever-phone">Số điện thoại:</label>
                            <input name="reciever-phone" type="text" placeholder="Số điện thoại..." value="<?php echo $order["recieve_phonenum"];?>">
                        </div>
                        <div>
                            <label for="reciever-address">Địa chỉ:</label>
                            <input name="reciever-address" type="text" placeholder="Địa chỉ..." value="<?php echo $order["recieve_address"];?>">
                        </div>
                        <div>
                            <label for="reciever-note">Ghi chú:</label>
                            <textarea name="reciever-note" type="text" placeholder="Ghi chú..."><?php echo $order["note"];?></textarea>
                        </div>
                        <div style="display: flex; justify-content: space-around; margin-top: 40px">
                            <a class="btn" style="background-color: gray; padding: 15px 20px;" href="order.php">Quay lại</a>
                            <button style="padding: 15px 20px;background-color: green;" name="recieve-btn">Xác nhận</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
    </div>

    <script src="admin.js"></script>
</body>

</html>