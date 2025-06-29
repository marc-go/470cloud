<?php
require "../../../assets/470cloud.php";

$session = new loginManager();
if (!$session->checkLogin()) {
	$error["status"] == 500;
	$error["error"] = "Deine Login Sitzung ist ungültig oder abgelaufen.";
}else{
	if (!isset($_GET["file"]) && !isset($_GET["text"])) {
		$error["status"] = 500;
		$error["error"] = "Die Datei wurde nicht gefunden.";
	}else{
		$file = $root . "/data/users/marc/files/root" . $_GET["file"];
		$text = $_GET["text"];
	
		if (!file_put_contents($file, $text)) {
			$error["status"] = 500;
			$error["error"] = "Es gab einen Fehler beim speichern.";
		}else{
			$error["status"] = 200;
		}
	}
}

die(json_encode($error));
?>