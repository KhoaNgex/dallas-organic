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
            $order = 'ORDER BY products.id';
            if(isset($_POST["statistics-sort-btn"])) {
                if($_POST["statistic-sort"] == 1) {
                    $order = 'ORDER BY sale ASC';
                }
                if($_POST["statistic-sort"] == 0) {
                    $order = 'ORDER BY sale DESC';
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
                <a style="background-color: #01640d" href="statistics_product.php">Theo sản phẩm</a>
                <a style="background-color: #06c806" href="statistics_cate.php">Theo loại sản phẩm</a>
                <a style="background-color: #06c806" href="statistics_date.php">Theo ngày</a>
                <a style="background-color: #06c806" href="statistics_month.php">Theo tháng</a>
                <a style="background-color: #06c806" href="statistics_year.php">Theo năm</a>
            </div>
            <!-- <div style="display: inline-block;">
                <input style="margin: 20px 0 0 0;" type="text" placeholder="Tìm kiếm">
                <button style="min-width: 40px; padding: 12px 12px; border-radius: 20px; background-color: green;"><i class="fa fa-search"></i></button>
            </div> -->
            
            <form action="" method="POST" style="display: inline-block;">
                <select name="statistic-sort" id="" style="margin: 20px 0 0 0; width: 190px">
                    <option value="" selected style="display: none">Xếp theo doanh thu</option>
                    <option value="0">Doanh thu giảm dần</option>
                    <option value="1">Doanh thu tăng dần</option>
                </select>
                <button name="statistics-sort-btn" style="min-width: 40px; padding: 12px 12px; border-radius: 20px; background-color: green;"><i class="fa fa-search"></i></button>
            </form>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Tên sản phẩm</th>
                    <th>Loại</th>
                    <th>Giá</th>
                    <th>Đã bán</th>
                    <th>Doanh thu</th>
                    <th>Thao tác</th>
                </tr>
                <?php
                    include('dbconnection.php');
                    $query = 'SELECT products.id, product_name, price, unit, sold_number, category.cate_name, sold_number*price as sale 
                            FROM products JOIN category ON products.category_id = category.id '.$order;
                    $result = mysqli_query($link, $query); 
                ?>
                <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                    <tr>
                    <td><?php echo $row["id"]?></td>
                        <td style="font-weight: bold"><?php echo $row["product_name"]?></td>
                        <td><?php echo $row["cate_name"]?></td>
                        <td><?php echo number_format($row["price"])."/".$row["unit"]?></td>
                        <td style="background-color: #f8ffd4"><?php echo number_format($row["sold_number"])?></td>
                        <td style="background-color: #f5ffc1"><?php echo number_format($row["sale"])."vnđ"?></td>
                        <td>
                        <a href="<?php echo "product_detail.php?id=".$row["id"]?>"><button style="background-color: green">Chi tiết</button></a>
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