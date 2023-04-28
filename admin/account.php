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
            <h2>Quản lý Tài khoản</h2>
            <a href="account_add.php"><button style="background-color: #3cba3c; margin-top: 20px; padding: 15px 20px;">Thêm tài khoản</button></a>
            <!-- <input type="text" placeholder="Tìm kiếm">
            <button style="min-width: 40px; padding: 12px 12px; border-radius: 20px; background-color: green;"><i class="fa fa-search"></i></button> -->
            <table id="user-list">
            </table>
            
            <div class="delete-modal">
                <div class="delete-modal-container">
                    <p>Bạn chắc chắn muốn xóa tài khoản này?</p>
                    <div style="text-align: center;">
                        <button style="margin-right: 20px; background-color: #ff7800a3; color: #000;"
                            onClick="deleteModal()">Xác nhận</button>
                        <button style="margin-left: 20px; color: #000;" onClick="removeDeleteModal()">Quay lại</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="admin.js"></script>
    <script src="js/APIs/account.js"></script>
</body>
</html>

