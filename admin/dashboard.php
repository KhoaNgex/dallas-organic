<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
        <div class="main-content main-content-dashboard">
            <div class="cards">
                <div class="card">
                    <p class="card-name">Khách hàng</p>
                    <p class="card-number">125</p>
                    <i class="icon fa fa-users"></i>
                </div>
                <div class="card">
                    <p class="card-name">Sản phẩm</p>
                    <p class="card-number">72</p>
                    <i class="icon fa fa-shopping-bag"></i>
                </div>
                <div class="card">
                    <p class="card-name">Đơn hàng</p>
                    <p class="card-number">1200</p>
                    <i class="icon fa fa-shopping-cart"></i>
                </div>
                <div class="card">
                    <p class="card-name">Kho</p>
                    <p class="card-number">12500</p>
                    <i class="icon fa fa-boxes"></i>
                </div>
            </div>
            <div class="charts">
                <div class="chart">
                    <p>Top 5 sản phẩm bán chạy</p>
                    <canvas id="bar-chart"></canvas>
                </div>
                <div class="chart">
                    <p>Doanh thu (12 tháng)</p>
                    <canvas id="line-chart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx1 = document.getElementById('bar-chart');

        new Chart(ctx1, {
            type: 'bar',
            data: {
            labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple'],
            datasets: [{
                label: '# Số lượng đã bán',
                data: [124, 111, 109, 91, 50],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.4)',
                    'rgba(54, 162, 235, 0.4)',
                    'rgba(255, 206, 86, 0.4)',
                    'rgba(75, 192, 192, 0.4)',
                    'rgba(153, 102, 255, 0.4)'
                ],
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

        const ctx2 = document.getElementById('line-chart');

        new Chart(ctx2, {
            type: 'line',
            data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: '# Doanh thu',
                data: [12122000, 9120000, 9350000, 10120000, 9550000, 13123456, 9876543, 8888888, 1357579, 9753531, 12468864, 11980000],
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
    </script>
</body>
</html>