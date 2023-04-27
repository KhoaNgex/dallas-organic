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
        <?php
        include('dbconnection.php');
        $query1 = 'SELECT COUNT(*) AS total_product, SUM(remain_number) AS stock FROM products';
        $result1 = mysqli_query($link, $query1);
        $query2 = 'SELECT COUNT(*) AS total_custom FROM customer_account';
        $result2 = mysqli_query($link, $query2);
        $query3 = 'SELECT COUNT(*) AS total_order FROM orders';
        $result3 = mysqli_query($link, $query3);
        $product_count = mysqli_fetch_array($result1);
        $customer_count = mysqli_fetch_array($result2);
        $order_count = mysqli_fetch_array($result3);
        ?>
        <div class="main-content main-content-dashboard">
            <div class="cards">
                <div class="card">
                    <p class="card-name">Khách hàng</p>
                    <p class="card-number"><?php echo $customer_count["total_custom"]?></p>
                    <i class="icon fa fa-users"></i>
                </div>
                <div class="card">
                    <p class="card-name">Sản phẩm</p>
                    <p class="card-number"><?php echo $product_count["total_product"]?></p>
                    <i class="icon fa fa-shopping-bag"></i>
                </div>
                <div class="card">
                    <p class="card-name">Đơn hàng</p>
                    <p class="card-number"><?php echo $order_count["total_order"]?></p>
                    <i class="icon fa fa-shopping-cart"></i>
                </div>
                <div class="card">
                    <p class="card-name">Kho</p>
                    <p class="card-number"><?php echo $product_count["stock"]?></p>
                    <i class="icon fa fa-boxes"></i>
                </div>
            </div>
            <div class="charts">
                <div class="chart">
                    <p>Top 5 sản phẩm bán chạy</p>
                    <canvas id="bar-chart-1" ></canvas>
                </div>
                <div class="chart">
                    <p style="margin-bottom: 50px;">Top 3 loại sản phẩm bán chạy</p>
                    <canvas id="bar-chart-2" ></canvas>
                </div>
                <div class="chart">
                    <p>Doanh thu trong 12 tháng gần nhất</p>
                    <canvas id="line-chart"></canvas>
                </div>
                
                <div class="chart">
                    <p>Sản phẩm đã bán (theo loại)</p>
                    <canvas id="bar-chart-3" ></canvas>
                </div>
            </div>
        </div>
        <?php
            mysqli_close($link)
        ?>
    </div>
    <?php
        include('dbconnection.php');
        $query = 'SELECT product_name, sold_number from products ORDER BY sold_number DESC LIMIT 5';
        $result = mysqli_query($link, $query);
        $product_name = array();
        $sold_number = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $product_name[] = $row["product_name"];
            $sold_number[] = $row["sold_number"];
        }
        mysqli_close($link)
    ?>
    <?php
        include('dbconnection.php');
        $query1 = 'SELECT A.category_id, A.cate_name, count(*) AS products_num, sum(A.sold_number) AS sold_num, sum(A.sale) AS sale_total 
                FROM ( 
                    SELECT products.id, product_name, price, sold_number, products.category_id, category.cate_name, sold_number*price as sale 
                    FROM products JOIN category ON products.category_id = category.id ) A 
                GROUP BY A.cate_name ORDER BY sold_num DESC LIMIT 3';
        $result1 = mysqli_query($link, $query1);
        $cate_name = array();
        $cate_sold_number = array();
        while ($row = mysqli_fetch_assoc($result1)) {
            $cate_name[] = $row["cate_name"];
            $cate_sold_number[] = $row["sold_num"];
        }
        mysqli_close($link)
    ?>
    <?php
        include('dbconnection.php');
        $query2 = 'SELECT LASTTABLE.order_date, LASTTABLE.day, LASTTABLE.month, LASTTABLE.year, sum(LASTTABLE.total) AS sale, count(*) AS order_num
        FROM (
        SELECT orderID, total, order_date, order_status, DAY(order_date) AS day, MONTH(order_date) AS month, YEAR(order_date) AS year
            FROM (
                (SELECT * 
                FROM(
                    (SELECT orderID, sum(price*quantity) AS total from products, ordered_product WHERE id = productID GROUP BY orderID) E 
                    JOIN orders F ON E.orderID = F.id)) X JOIN user_account Y ON X.userID_ordcus = Y.id
            ) WHERE order_status = "Hoàn thành" ) LASTTABLE GROUP BY LASTTABLE.order_date ORDER BY LASTTABLE.year, LASTTABLE.month DESC LIMIT 12';
        $result2 = mysqli_query($link, $query2);
        $month_name = array();
        $month_sale = array();
        while ($row = mysqli_fetch_assoc($result2)) {
            $month_name[] = $row["month"]."/".$row["year"];
            $month_sale[] = $row["sale"];
        }
        mysqli_close($link)
    ?>
    <?php
        include('dbconnection.php');
        $query3 = 'SELECT cate_name, SUM(sold_number) as numofsold
                    from products, category WHERE products.category_id = category.id
                    GROUP by category_id';
        $result3 = mysqli_query($link, $query3);
        $cate_name_pie = array();
        $cate_sold_number_pie = array();
        while ($row = mysqli_fetch_assoc($result3)) {
            $cate_name_pie[] = $row["cate_name"];
            $cate_sold_number_pie[] = $row["numofsold"];
        }
        mysqli_close($link)
    ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const product_label_lst = <?php echo json_encode($product_name); ?>;
        const sold_number_lst = <?php echo json_encode($sold_number); ?>;
        const cate_label_lst = <?php echo json_encode($cate_name); ?>;
        const cate_sold_number_lst = <?php echo json_encode($cate_sold_number); ?>;
        const month_name_lst = <?php echo json_encode($month_name); ?>;
        const month_sale_lst = <?php echo json_encode($month_sale); ?>;
        const cate_label_pie_lst = <?php echo json_encode($cate_name_pie); ?>;
        const cate_sold_pie_lst = <?php echo json_encode($cate_sold_number_pie); ?>;
        
        const ctx1 = document.getElementById('bar-chart-1');

        new Chart(ctx1, {
            type: 'bar',
            data: {
            labels: product_label_lst,
            datasets: [{
                label: '# Số lượng đã bán',
                data: sold_number_lst,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)'
                ],
                borderWidth: 1
            }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 50
                        },
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 9,
                            },
                            maxRotation: 75,
                            minRotation: 75
                        }
                    }
                }
            }
        });
        const ctx2 = document.getElementById('bar-chart-2');

        new Chart(ctx2, {
            type: 'bar',
            data: {
            labels: cate_label_lst,
            datasets: [{
                label: '# Số lượng đã bán',
                data: cate_sold_number_lst,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 206, 86, 0.8)'
                ],
                borderWidth: 1
            }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 50
                        },
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 11,
                            },
                            maxRotation: 75,
                            minRotation: 75
                        }
                    }
                }
            }
        });
        
        const ctx3 = document.getElementById('line-chart');

        new Chart(ctx3, {
            type: 'line',
            data: {
            labels: month_name_lst.reverse(),
            datasets: [{
                label: '# Doanh thu',
                data: month_sale_lst.reverse(),
                borderWidth: 1
            }]
            },
            options: {
            scales: {
                y: {
                beginAtZero: true
                }
            }
            }
        });
        
        const ctx4 = document.getElementById('bar-chart-3');

        new Chart(ctx4, {
            type: 'pie',
            data: {
                labels: cate_label_pie_lst,
                datasets: [{
                    label: '# Số lượng đã bán',
                    data: cate_sold_pie_lst
                }]
            }
        });
    </script>
</body>
</html>