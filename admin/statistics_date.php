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
            $order = '';
            if(isset($_POST["statistics-sort-btn"])) {
                if($_POST["statistic-sort"] == 1) {
                    $order = 'ORDER BY LASTTABLE.total ASC';
                }
                if($_POST["statistic-sort"] == 0) {
                    $order = 'ORDER BY LASTTABLE.total DESC';
                }
            }
        ?>
        <?php 
            include('components/navbar.php');
            include('components/sidebar.php');
        ?>
        <div class="main-content main-content-order">
            <h2>Báo cáo Doanh thu</h2>
            <div class="sale-statistics-nav">
                <a style="background-color: #06c806" href="statistics_product.php">Theo sản phẩm</a>
                <a style="background-color: #06c806" href="statistics_cate.php">Theo loại sản phẩm</a>
                <a style="background-color: #01640d" href="statistics_date.php">Theo ngày</a>
                <a style="background-color: #06c806" href="statistics_month.php">Theo tháng</a>
                <a style="background-color: #06c806" href="statistics_year.php">Theo năm</a>
            </div>
            <form action="statistics_order_date.php" method="POST" style="display: inline-block;">
                <input name="statistics-date" style="margin: 20px 0 0 0;" type="date" placeholder="Tìm kiếm theo ngày...">
                <button name="statistics-date-btn" style="min-width: 40px; padding: 12px 12px; border-radius: 20px; background-color: green;"><i class="fa fa-search"></i></button>
            </form>
            <form action="" method="POST" style="display: inline-block;">
                <select name="statistic-sort" id="" style="width: 190px">
                    <option value="" selected style="display: none">Xếp theo doanh thu</option>
                    <option value="0">Doanh thu giảm dần</option>
                    <option value="1">Doanh thu tăng dần</option>
                </select>
                <button name="statistics-sort-btn" style="min-width: 40px; padding: 12px 12px; border-radius: 20px; background-color: green;"><i class="fa fa-search"></i></button>
            </form>
            
            <table>
            <tr>
                <th>Ngày</th>
                <th>Số đơn</th>
                <th>Doanh thu</th>
            </tr>
            <?php
                include('dbconnection.php');
                $query = 'SELECT LASTTABLE.order_date, LASTTABLE.day, LASTTABLE.month, LASTTABLE.year, sum(LASTTABLE.total) AS sale, count(*) AS order_num
                FROM (
                SELECT orderID, total, order_date, order_status, DAY(order_date) AS day, MONTH(order_date) AS month, YEAR(order_date) AS year
                    FROM (
                        (SELECT * 
                        FROM(
                            (SELECT orderID, sum(price*quantity) AS total from products, ordered_product WHERE id = productID GROUP BY orderID) E 
                            JOIN orders F ON E.orderID = F.id)) X JOIN user_account Y ON X.userID_ordcus = Y.id
                    ) WHERE order_status = "Hoàn thành" ) LASTTABLE GROUP BY LASTTABLE.order_date '.$order;
                $result = mysqli_query($link, $query); 
            ?>
            <?php
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <tr>
                    <td style="font-weight: bold"><?php echo date("d-m-Y", strtotime($row["order_date"]))?></td>
                    <td style="background-color: #f8ffd4"><?php echo number_format($row["order_num"])?></td>
                    <td style="background-color: #f5ffc1"><?php echo number_format($row["sale"])."vnđ"?></td>
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