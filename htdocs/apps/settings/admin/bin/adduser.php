<?php
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

require "../../../../assets/470cloud.php";
$session = new loginManager();
if (!$session->checkLogin()) {
    die('{"status":500, "error":"You are not login."}');
}

if (!$session->getAdmin()) {
    die('{"status":500, "error":"Premission denied"}');
}

$POST = json_decode(file_get_contents("php://input"), true);

if (isset($POST["user"]) == 1 && isset($POST["mail"]) == 1 && isset($POST["pw"]) == 1 && isset($POST["pw2"]) == 1 && isset($POST["admin"]) == 1) {
    $conn = startDB();
    $sql = "INSERT INTO users (username, mail, password, admin) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    $user = $POST["user"];
    $mail = $POST["mail"];
    $pw = hash("sha256", $POST["pw"]);
    $pw2 = hash("sha256", $POST["pw2"]);
    $admin = $POST["admin"];

    if ($pw !== $pw2) {
        die('{"status":500, "error":"The passwords are false."}');
    }

    $stmt->bind_param("sssi", $user, $mail, $pw, $admin);
    
    if (!$stmt->execute()) {
        die('{"status":500, "error":"SQL Error."}');
    }

    if (!mkdir($root . "/data/users/" . $user)) {
        die('{"status":500, "error":"Dictory could not create."}');
    }
    if (!file_put_contents($root . "/data/users/" . $user . "/sessions.json", '{"array":true}')) {
        die('{"status":500, "error":"Error to create a file"}');
    }

    die('{"status":200}');
}else{
    die('{"status":500, "error":"No Params."}');
}
?>