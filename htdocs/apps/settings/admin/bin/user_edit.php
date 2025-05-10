<?php
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

require $_SERVER["DOCUMENT_ROOT"] . "/assets/admin.php";
$session = new loginManager();
if (!$session->checkLogin()) {
    die('{"status":500, "error":"You are not login."}');
}

$POST = json_decode(file_get_contents("php://input"), true);

if (isset($POST["name"]) && isset($POST["mail"]) && isset($POST["pw"]) && isset($POST["pw2"]) && isset($POST["id"])) {
    
    $id = $POST["id"];
    $name = $POST["name"];
    $mail = $POST["mail"];
    $pw = hash("sha256", $POST["pw"]);
    $pw2 = hash("sha256", $POST["pw2"]);

    require $_SERVER["DOCUMENT_ROOT"] . "/assets/db.php";
    
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$session->getAdmin()) {
        if ($session->getUserName() !== $row["username"]) {
            die('{"status":500, "error":"Premisson Denied"}');
        }
    }


    if ($pw !== $pw2) {
        die('{"status":500, "error":"The passwords are false."}');
    }

    $sql = "UPDATE users SET username = ?, mail = ?, password = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $name, $mail, $pw2, $id);
    
    if (!$stmt->execute()) {
        die('{"status":500, "error":"SQL Error"}');
    }else{
        die('{"status":200}');
    }
}else{
    die('{"status":500, "error":"No Params."}');
}
?>