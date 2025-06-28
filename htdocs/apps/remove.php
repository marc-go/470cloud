<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);

function deleteFolder($folderPath) {
    if (!is_dir($folderPath)) {
        return false;
    }

    $items = scandir($folderPath);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }

        $itemPath = $folderPath . DIRECTORY_SEPARATOR . $item;

        if (is_dir($itemPath)) {
            deleteFolder($itemPath);
        } else {
            unlink($itemPath);
        }
    }

    return rmdir($folderPath);
}


require $_SERVER["DOCUMENT_ROOT"] . "/assets/470cloud.php";
$session = new loginManager();
if (!$session->checkLogin()) {
    header("Location: /login.php?from=apps/store");
    exit;
}

$id = intval($_GET["id"]);

$conn = startDB();
$sql = "SELECT * FROM apps WHERE app_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("The App is not installed.");
}

$row = $result->fetch_assoc();

$json = file_get_contents($root . "/apps/" . $row["url_name"] . "/app.json");
$json = json_decode($json, true);

if ($json["database"] !== false) {
    $sql = "DELETE TABLE " . $json["database"];
    if (!$conn->query($sql)) {
        die("SQL Error.");
    }
}

deleteFolder($root . "/apps/" . $row["url_name"]);

$sql = "DELETE FROM apps WHERE app_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if (!$stmt->execute()) {
    echo "SQL Error";
}else{
    header("Location: /apps/store");
    exit;
}
?>