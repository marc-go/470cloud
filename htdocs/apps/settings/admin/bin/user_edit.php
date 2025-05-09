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

if (isset($POST["name"]) && isset($POST["mail"]) && isset($POST["pw"]) && isset($POST["pw2"])) {
    if ($session->getAdmin()) {
        if (!isset($POST["id"])) {
            die('{"status":500, "error:"No Params."}');
        }
        $id = $POST["id"];
    }else{
        require $_SERVER["DOCUMENT_ROOT"] . "/assets/db.php";
        $sql = "SELECT id FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $id = $row["id"];
    }

    $name = $POST["name"];
    $mail = $POST["mail"];
    $pw = hash("sha256", $POST["pw"]);
    $pw2 = hash("sha256", $POST["pw2"]);

    if ($pw !== $pw2) {
        die('{"status":500, "error":"The passwords are false."}');
    }

    require $_SERVER["DOCUMENT_ROOT"] . "/assets/db.php";

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