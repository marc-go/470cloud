<?php
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
    
    return rmdir($fPath);
}

require "../../../../assets/470cloud.php";

$session = new loginManager();
if (!$session->checkLogin()) {
    die('{"status":500, "error":"You are not login. Please reload."}');
}

if (!$session->getAdmin()) {
    die('{"status":500, "error":"Premission denied"}');
}

$POST = json_decode(file_get_contents("php://input"), true);

if (isset($POST["user"])) {
    $id = intval($POST["user"]);

    r$conn = startDB();

    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if (!$stmt->execute()) {
        die('{"status":500, "error":"SQL Error."}');
    }

    $stmt->close();
    $conn->close();

    if (is_dir($root . "/data/users/" . $session->gunfI($id))) {
        if (!rmFolder($root . "/data/users/" . $session->gunfI($id))) {
            die('{"status":500, "error":"Failed to remove user folder."}');
        }
    }

    die('{"status":200}');
}
?>