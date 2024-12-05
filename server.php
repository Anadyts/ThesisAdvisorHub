<?php
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'thesis_advisor';

    $conn = mysqli_connect($host, $username, $password, $database);
    if($conn){
        
    }else{
        die(mysqli_connect_error());
    }


?>