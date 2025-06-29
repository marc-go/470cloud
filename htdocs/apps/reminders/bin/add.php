<?php
require "../../../assets/470cloud.php";
$session = new loginManager();
if (!$session->checkLogin()) {
	echo '{"status":500}';
}

if (isset($_POST["name"]) && isset($_POST["date"])) {
    $conn = startDB();
    
    $sql = "INSERT INTO reminders (name, user, date, trash) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Fehler beim Vorbereiten der SQL-Anweisung: " . $conn->error);
    }

	$trash = 0;
    @$stmt->bind_param("sssi", $_POST["name"], $session->getUserName(), $_POST["date"], $trash);
    
    if ($stmt->execute() === TRUE) {
        header("Location: /apps/reminders");
    } else {
        header("Location: /apps/reminders/?error=There was an error: " . $stmt->error);
    }
    
    $stmt->close();
    $conn->close();
} else {
    header("Location: /apps/reminders/?error=There was an error: No Params");
}
?>