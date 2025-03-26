<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define("PATH", "../../../");

require "../../../assets/admin.php";
$session = new loginManager();
if (!$session->checkLogin()) {
    echo json_encode(["status" => 500, "error" => "Du bist nicht angemeldet. Bitte lade die Seite neu."]);
    exit();
}

if (isset($_GET["pw1"]) && isset($_GET["pw2"]) && isset($_GET["pw3"])) {
    $pw1 = $_GET["pw1"];
    $pw2 = $_GET["pw2"];
    $pw3 = $_GET["pw3"];

    if ($pw2 !== $pw3) {
        echo json_encode(["status" => 500, "error" => "Die Passwörter stimmen nicht überein."]);
        exit();
    }

    $pw_new = hash("sha256", $pw2);
    $pw = hash("sha256", $pw1);

    require "../../../assets/db.php";

    $username = $session->getUserName();
    $sql = "UPDATE users SET password = ? WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $pw_new, $username, $pw);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(["status" => 200]);
        } else {
            echo json_encode(["status" => 500, "error" => "Das alte Passwort ist falsch oder der Benutzername stimmt nicht."]);
        }
    } else {
        echo json_encode(["status" => 500, "error" => "Es gab einen Fehler bei der Passwortänderung."]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => 500, "error" => "Das Passwort konnte nicht abgerufen werden."]);
}
?>