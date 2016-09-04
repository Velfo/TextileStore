<?php
$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "TextileShopUseres";

// Create connection
$con = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
} 
echo "Connected successfully";
//$con = mysqli_connect('root', 'password', '','content');
?>