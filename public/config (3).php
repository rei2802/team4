<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$host = "localhost";
$user = "bprfqeyz_2k2juser";
$password = "websysqm2k2j";
$database = "bprfqeyz_2k2j";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}
?>