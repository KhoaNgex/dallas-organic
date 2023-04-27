<?php
    if(isset($_GET['id'])) {
        $cate_id = $_GET['id'];
        include('dbconnection.php');
        $query = "DELETE FROM category WHERE id = $cate_id";
        mysqli_query($link, $query);
        mysqli_close($link);
    }
    header("location: category.php");
?>