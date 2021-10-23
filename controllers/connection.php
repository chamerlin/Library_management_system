<?php 
$cn = mysqli_connect("localhost", "root", "", "library_management");

if(mysqli_connect_errno()) {
    echo "Falied to connect to MYSQL: ". mysqli_connect_error();
    die();
}
?>