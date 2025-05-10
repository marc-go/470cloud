<?php
require $_SERVER["DOCUMENT_ROOT"] . "/assets/admin.php";
$session = new loginManager();
if (!$session->checkLogin()) {
    die('{"status":500, "error":"You are not login."}');
}

if (!$session->getAdmin()) {
    die('{"status":500, "error":"Premission denied"}');
}

if (isset($_POST["user"]) && isset($_POST["mail"]) && isset($_POST["pw"]) && isset($_POST["pw2"]) && isset($_POST["admim"])) {
    require $_SERVER["DOCUMENT_ROOT"] . "/assets/db.php";
    $sql = "INSERT INTO users (username, mail, password, admin) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare("sssi");

    $user = $_POST["user"];
    $mail = $_POST["mail"];
    $pw = hash("sha256", $_POST["pw"]);
    $pw2 = hash("sha256", $_POST["pw2"]);
    $admin = $_POST["admin"] == true ? 1 : 0;

    if ($pw !== $pw2) {
        die('{"status":500, "error":"The passwords are false."}');
    }

    $stmt->bind_param("sssi", $user, $mail, $pw, $admin);
    
    if (!$stmt->execute()) {
        die('{"status":500, "error":"SQL Error."}');
    }else{
        die('{"status":200}');
    }
}else{
    die('{"status":500, "error":"No Params."}');
}
?>