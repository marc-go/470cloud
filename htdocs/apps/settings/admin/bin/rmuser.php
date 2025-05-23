<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);

function rmFolder($fPath) {
    if (!is_dir($fPath)) {
        return false;
    }

    $items = scandir($fPath);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }

        $iPath = $fPath . DIRECTORY_SEPARATOR . $item;

        if (is_dir($iPath)) {
            rmFolder($iPath);
        }else{
            unlink($iPath);
        }
    }
    
    return rmdir($folderPath);
}

require $_SERVER["DOCUMENT_ROOT"] . "/assets/admin.php";

$session = new loginManager();
if (!$session->checkLogin()) {
    die('{"status":500, "error":"You are not login. Please reload."}');
}

if (!$session->getAdmin()) {
    die('{"status":500, "error":"Premission denied"}');
}

if (isset($_GET["user"])) {
    require $_SERVER["DOCUMENT_ROOT"] . "/assets/db.php";

    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_GET["id"]);

    if (!$stmt->execute()) {
        die('{"status":500, "error":"SQL Error."}');
    }

    $stmt->close();
    $conn->close();

    if (is_dir($_SERVER["DOCUMENT_ROOT"] . "/data/users/" . $session->gunfI($_GET["id"]))) {
        if (!rmFolder($_SERVER["DOCUMENT_ROOT"] . "/data/users/" . $session->gunfI($_GET["id"]))) {
            die('{"status":500, "error":"Failed to remove user folder."}');
        }
    }

    die('{"status":200}');
}
?>