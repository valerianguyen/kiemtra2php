<?php
    $server = "localhost";
    $user = "root";
    $pass = "" ;
    $db_name = "kiemtra2";
    $conn = new mysqli($server, $user, $pass, $db_name);
    if($conn->connect_error){
        die("lỗi kết nối".$conn->connect_error);
    }
    //echo "kết nối thành công";

?>