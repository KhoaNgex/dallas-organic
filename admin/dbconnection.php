<?php
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $db = 'dallasorganic';

    $link = mysqli_connect ($host, $username, $password) ;
    if (!$link) {
        die('Could not connect: ' . mysqli_error($link));
    }

    $db_selected = mysqli_select_db($link, $db);
    if (!$db_selected) {
        die ('Can\'t use shop : ' . mysqli_error($link));
    }
?>