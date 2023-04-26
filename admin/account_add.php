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
        <div class="main-content main-content-productadd">
            <h2>Chỉnh sửa thông tin người dùng</h2>
            <form id="add-user-form">
                <div>
                    <label for="user-name">Tên đăng nhập<span style="color: red">*</span>:</label>
                    <input type="text" name="user-name" required placeholder="Tên đăng nhập...">
                </div>
                <div>
                    <label for="user-pw">Mật khẩu<span style="color: red">*</span>:</label>
                    <input type="password" name="user-pw" required placeholder="Mật khẩu...">
                </div>
                <div>
                    <label for="user-fullname">Họ và tên<span style="color: red">*</span>:</label>
                    <input type="text" name="user-fullname" required placeholder="Họ và tên...">
                </div>
                <div>
                    <label for="user-sex">Giới tính<span style="color: red">*</span>:</label>
                    <select name="user-sex" id="user-sex" required>
                        <option value="Nam">Nam</option>
                        <option value="Nữ">Nữ</option>
                    </select>
                </div>
                <div>
                    <label for="user-dob">Ngày sinh<span style="color: red">*</span>:</label>
                    <input type="date" name="user-dob" required placeholder="Ngày sinh...">
                </div>
                <div>
                    <label for="user-phonenumber">Số điện thoại<span style="color: red">*</span>:</label>
                    <input type="text" name="user-phonenumber" required placeholder="Số điện thoại...">
                </div>
                <div>
                    <label for="user-email">Email<span style="color: red">*</span>:</label>
                    <input type="text" name="user-email" required placeholder="Email...">
                </div>
                <div>
                    <label for="user-address">Địa chỉ<span style="color: red">*</span>:</label>
                    <input type="text" name="user-address" required placeholder="Địa chỉ...">
                </div>
                <div>
                    <label for="user-avatar">Link avatar<span style="color: red">*</span>:</label>
                    <input type="text" name="user-avatar" required placeholder="Link avatar...">
                </div>
                <button type="submit" style="margin: 10px 30px 0 0; padding: 15px 20px; background-color: green;">Xác nhận</button>
                <a class="btn" style="background-color: gray; padding: 15px 20px;" onClick="turnBack()">Quay lại</a>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="admin.js"></script>
    <script src="js/APIs/account-add.js"></script>
</body>

</html>