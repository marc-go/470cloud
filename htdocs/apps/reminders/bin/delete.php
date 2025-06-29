<?php
require "../../../assets/470cloud.php";
$session = new loginManager();
if (!$session->checkLogin()) {
	die('{"status":500}');
}

if (isset($_GET["id"])) {
	$id = intval($_GET["id"]);
	
	$conn = startDB();
	
	$sql = "DELETE FROM reminders WHERE id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("i", $id);
	
	if ($stmt->execute() === TRUE) {
		echo '{"status":200}';
	}else{
		echo '{"status":500, "error":"Fehler beim löschen."}';
	}
	$stmt->close();
	$conn->close();
}else{
	echo '{"status":500, "error":"Kein Objekt gefunden."}';
}
?>