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
            if(isset($_POST['blog-add-btn'])) {
                $title = $_POST['title'];
                $content = $_POST['content'];
                $sub_title =$_POST["sub-title"];
                $min_read =$_POST["min-read"];
                $blog_image =$_POST["blog-image"];
                include('dbconnection.php');
                $query = "INSERT INTO blogs (title, subtitle, min_read, content, image) VALUES ('$title', '$sub_title','$min_read', '$content', '$blog_image')";
                mysqli_query($link, $query);
                mysqli_close($link);
                header("location: blog.php");
            }
        ?>
        <?php
        include('components/navbar.php');
        include('components/sidebar.php');
        ?>
        <div class="main-content main-content-productadd">
            <h2>Thêm Blog</h2>
            <form method="POST">
                <div>
                    <label for="title">Tiêu đề<span style="color: red">*</span>:</label>
                    <input type="text" name="title" required placeholder="Tiêu đề...">
                </div>
                <div>
                    <label for="sub-title">Tiêu đề phụ<span style="color: red">*</span>:</label>
                    <input type="text" name="sub-title" required placeholder="Tiêu đề phụ...">
                </div>
                <div>
                    <label for="created-by">Người đăng<span style="color: red">*</span>:</label>
                    <input type="text" name="created-by" required placeholder="Người đăng..." value="Admin" disabled>
                </div>
                <div>
                    <label for="min-read">Min-read<span style="color: red">*</span>:</label>
                    <input type="number" name="min-read" required placeholder="Min-read...">
                </div>
                <div>
                    <label for="content">Nội dung<span style="color: red">*</span>:</label>
                    <textarea style="min-height: 400px; width: 800px;" type="text" name="content" required placeholder="Nội dung..."></textarea>
                </div>
                <div>
                    <label for="blog-image">Link hình ảnh<span style="color: red">*</span>:</label>
                    <input type="text" name="blog-image" required placeholder="Link hình ảnh...">
                </div>
                <button type="submit" name="blog-add-btn" style="margin: 10px 30px 0 0; padding: 15px 20px; background-color: green;">Xác nhận</button>
                <a class="btn" style="background-color: gray; padding: 15px 20px;" onClick="turnBackToBlog()">Quay lại</a>
            </form>
        </div>
    </div>
    <script>
        function turnBackToBlog() {
            window.location = "blog.php";
        }
    </script>
    <script src="admin.js"></script>
</body>

</html>