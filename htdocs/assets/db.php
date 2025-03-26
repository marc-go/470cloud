<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

$servername = "localhost";
$username = "470user";
$password = "470affe470";
$dbname = "470Cloud";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
