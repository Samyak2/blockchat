<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'blockchat');
define('DB_PASSWORD', 'webwarriors');
define('DB_NAME', 'blockchat');
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

define('SERVER_NAME', "https://blockchat-webwarriors.herokuapp.com/")
// define('SERVER_NAME', "http://127.0.0.1:8000/")
?>