<?php
    if(isset($_GET['id'])) {
        $blog_id = $_GET['id'];
        include('dbconnection.php');
        $query = "DELETE FROM blogs WHERE id = $blog_id";
        mysqli_query($link, $query);
        mysqli_close($link);
    }
    header("location: blog.php");
?>