<?php
define("PATH", "../../../");

require "../../../assets/admin.php";
$session = new loginManager();
if (!$session->checkLogin()) {
	die('{"status":500}');
}

if (isset($_GET["id"])) {
	$id = intval($_GET["id"]);
	
	require "../../../assets/db.php";
	
	$sql = "UPDATE reminders SET trash = 0 WHERE id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("i", $id);
	
	if ($stmt->execute() === TRUE) {
		echo '{"status":200}';
	}else{
		echo '{"status":500, "error":"Fehler beim wiederherstellen."}';
	}
	$stmt->close();
	$conn->close();
}else{
	echo '{"status":500, "error":"Kein Objekt gefunden."}';
}
?>