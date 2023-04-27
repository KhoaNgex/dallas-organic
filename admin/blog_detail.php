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
            if(isset($_POST['blog-edit-btn'])) {
                $id = $_POST['blog-edit-btn'];
                $title = $_POST['title'];
                $content = $_POST['content'];
                $sub_title =$_POST["sub-title"];
                $min_read =$_POST["min-read"];
                $blog_image =$_POST["blog-image"];
                include('dbconnection.php');
                $query = "UPDATE blogs SET title='$title', subtitle='$sub_title', min_read='$min_read', content='$content', image='$blog_image' WHERE id = $id";
                mysqli_query($link, $query);
                mysqli_close($link);
            }
        ?>
        <?php
        include('components/navbar.php');
        include('components/sidebar.php');
        ?>
        <?php
        include('dbconnection.php');
        $query = 'SELECT * FROM blogs WHERE id='.$_GET["blog_id"];
        $result = mysqli_query($link, $query);
        $blog = mysqli_fetch_array($result);
        ?>
        <div class="main-content main-content-productadd">
            <h2>Chi tiết - Chỉnh sửa Blog</h2>
            <form method="POST">
                <div>
                    <label for="title">Tiêu đề<span style="color: red">*</span>:</label>
                    <input type="text" name="title" required placeholder="Tiêu đề..." value="<?php echo $blog["title"]?>">
                </div>
                <div>
                    <label for="sub-title">Tiêu đề phụ<span style="color: red">*</span>:</label>
                    <input type="text" name="sub-title" required placeholder="Tiêu đề phụ..."  value="<?php echo $blog["subtitle"]?>">
                </div>
                <div>
                    <label for="created-by">Người đăng<span style="color: red">*</span>:</label>
                    <input type="text" name="created-by" required placeholder="Người đăng..."  value="<?php echo $blog["created_by"]?>" disabled>
                </div>
                <div>
                    <label for="created-at">Thời gian đăng<span style="color: red">*</span>:</label>
                    <input type="date" name="created-at" required placeholder="Thời gian đăng..."  value=<?php echo $blog["created_at"]?> disabled>
                </div>
                <div>
                    <label for="min-read">Min-read<span style="color: red">*</span>:</label>
                    <input type="number" name="min-read" required placeholder="Min-read..."  value="<?php echo $blog["min_read"]?>">
                </div>
                <div>
                    <label for="content">Nội dung<span style="color: red">*</span>:</label>
                    <textarea style="min-height: 400px; width: 800px;" type="text" name="content" required placeholder="Nội dung..."><?php echo $blog["content"]?></textarea>
                </div>
                <div>
                    <label for="blog-image">Link hình ảnh<span style="color: red">*</span>:</label>
                    <input type="text" name="blog-image" required placeholder="Link hình ảnh..."  value="<?php echo $blog["image"]?>">
                </div>
                <button type="submit" name="blog-edit-btn" value="<?php echo $_GET["blog_id"]?>" style="margin: 10px 30px 0 0; padding: 15px 20px; background-color: green;">Chỉnh sửa</button>
                <a class="btn" style="background-color: gray; padding: 15px 20px;" onClick="turnBackToBlog()">Quay lại</a>
            </form>
            <?php
                mysqli_close($link);
            ?>
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