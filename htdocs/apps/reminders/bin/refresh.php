<?php
define("PATH", "../../../");

require "../../../assets/admin.php";
$session = new loginManager();
if (!$session->checkLogin()) {
	header("Location: /apps/reminders/?error=No Login.");
	exit;
}

if (isset($_GET["id"])) {
	$id = intval($_GET["id"]);
	
	require "../../../assets/db.php";
	
	$sql = "UPDATE reminders SET trash = 0 WHERE id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("i", $id);
	
	if ($stmt->execute() === TRUE) {
		header("Location: /apps/reminders");
		exit;
	}else{
		header("Location: /apps/reminders/?error=MySQL Error");
		exit;
	}
	$stmt->close();
	$conn->close();
}else{
	header("Location: /apps/reminders/?error=No Object Found");
	exit;
}
?>