<?php
    if(isset($_POST['cate-name-add-btn'])) {
        if($_POST['cate-name-add'] != "") {
            $new_cate_name = $_POST['cate-name-add'];
            include('dbconnection.php');
            $query = "INSERT INTO category (cate_name) VALUES ('$new_cate_name')";
            mysqli_query($link, $query);
            mysqli_close($link);
            header("location: category.php");
        }
        else {
            header("location: category.php");
        }
    }
?>