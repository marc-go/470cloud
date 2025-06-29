<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "../../../../assets/470cloud.php";

$session = new loginManager();
if (!$session->checkLogin()) {
    die('{"status":500, "error":"You are not login. Please reload."}');
}

if (isset($_GET["id"])) {
    $conn = startDB();

    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_GET["id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $answer["status"] = 200;
    $answer["name"] = $row["username"];
    $answer["mail"] = $row["mail"];
    $answer["admin"] = $row["admin"] == 1 ? true : false;

    die(json_encode($answer));
}else{
    die('{"status":500, "error":"No Parameters"}');
}
?>