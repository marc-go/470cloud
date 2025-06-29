<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "../../../assets/470cloud.php";

$session = new loginManager();
if (!$session->checkLogin()) {
	die('{"status":500, "error":"Du bist nicht angemeldet. <br>Bitte lade die Seite neu."}');
}

if (isset($_GET["name"])) {
	$name = "../" . $_GET["name"];
	
	if (is_dir($name)) {
		die('{"status":500, "error":"Der Ordner exestiert schon"}');
	}else{
		if (!mkdir($name, 0700)) {
			die('{"status":500, "error":"Es gab einen Fehler beim erstellen vom Ordner."}');
		}else{
			die('{"status":200}');
		}
	}
}else{
	die('{"status":500, "error":"Fehler beim teilen des Ordernames."}');
}
?>