<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

define("PATH", "../../../");

require "../../../assets/admin.php";
$session = new loginManager();
if (!$session->checkLogin()) {
	echo '{"status":500}';
}

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data["name"]) && isset($data["date"])) {
    require "../../../assets/db.php";
    
    $sql = "INSERT INTO reminders (name, user, date, trash) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Fehler beim Vorbereiten der SQL-Anweisung: " . $conn->error);
    }

	$trash = 0;
    @$stmt->bind_param("sssi", $data["name"], $session->getUserName(), $data["date"], $trash);
    
    if ($stmt->execute() === TRUE) {
        echo json_encode(['status' => 200]);
    } else {
        echo json_encode(['status' => 500, 'error' => $stmt->error]);
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 400, 'error' => 'Fehlende Parameter']);
}
?>