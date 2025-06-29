<?php
require "../../../assets/470cloud.php";
$session = new loginManager();
if (!$session->checkLogin()) {
	die('{"status":500, "error":"No Login"}');
}

if (isset($_GET["id"])) {
	$conn = startDB();
	$id = intval($_GET["id"]);
	
	$sql = "SELECT user FROM reminders WHERE id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("i", $id);
	$stmt->execute();
	
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	
	if (!$row["user"] == $session->getUserName()) {
		$stmt->close();
		$conn->close();
		die('{"status":500, "error":"False User."}');
	}else{
		$sql = "UPDATE reminders SET trash = 1 WHERE id = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("i", $id);
		
		if (!$stmt->execute() === TRUE) {
			$stmt->close();
			$conn->close();
			die('{"status":500, "error":"MySQL Error"}');
		}else{
			$stmt->close();
			$conn->close();
			die('{"status":200}');
		}
	}
}
?>