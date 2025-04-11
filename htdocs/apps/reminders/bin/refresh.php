<?php
define("PATH", "../../../");

require "../../../assets/admin.php";
$session = new loginManager();
if (!$session->checkLogin()) {
	die('{"status":500, "error":"No Login."}');
}

if (isset($_GET["id"])) {
	$id = intval($_GET["id"]);
	
	require "../../../assets/db.php";
	
	$sql = "UPDATE reminders SET trash = 0 WHERE id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("i", $id);
	
	if ($stmt->execute() === TRUE) {
		die('{"status":200}');
	}else{
		die('{"status":500, "error":"MySQL Error."}');
	}
	$stmt->close();
	$conn->close();
}else{
	die('{"status":500, "error":"No Object found."}');
}
?>