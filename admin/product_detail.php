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
        include('components/navbar.php');
        include('components/sidebar.php');
        ?>
        <div class="main-content main-content-productdetail">
            <h2>Chi tiết sản phẩm</h2>
            <div class="detail-container" id="product-detail">
                
            </div>
        </div>
        <div class="delete-modal">
            <div class="delete-modal-container">
                <p>Bạn chắc chắn muốn xóa sản phẩm?</p>
                <div style="text-align: center;">
                    <button style="margin-right: 50px; background-color: #ff7800a3; color: #000;"
                        onClick="removeDeleteModal()">Xác nhận</button>
                    <button style="margin-left: 50px; color: #000;" onClick="removeDeleteModal()">Quay lại</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="admin.js"></script>
    <script src="js/APIs/product-detail.js"></script>
</body>

</html>