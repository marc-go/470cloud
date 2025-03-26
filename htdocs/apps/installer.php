<?php
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

define("PATH", "../");

require "../assets/admin.php";
$session = new loginManager();
if (!$session->checkLogin()) {
	die('{"status":500, "error":"Du bist nicht angemeldet.<br>Bitte lade die Seite neu."}');
}

$user = $session->getUserName();
require "../assets/uinfo.php";
if ($uinfo["admin"] !== true) {
	die ('{"status":500, "error":"Premission denied: You are not an admin."}');
}

if (isset($_GET["id"])) {
	$id = intval($_GET["id"]);
	
	$file = file_get_contents("https://api.470cloud.marc-goering.de/app/cloud/app-store/all.json");
	$file = json_decode($file, true);
	
	if (!array_key_exists($id, $file)) {
		die('{"status":500, "error":"Die App wurde nicht gefunden."}');
	} else {
		$json = $file[$id];
	
		require "../assets/db.php";

		$sql = "SELECT name FROM apps WHERE name = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("s", $json["name"]);
		$stmt->execute();
	
		$result = $stmt->get_result();
	
		if ($result->num_rows > 0) {
			$stmt->close();
			$conn->close();
			die('{"status":500, "error":"Die App ist schon installiert."}');
		} else {
			$url = $json["download-url"];
		
			file_put_contents("package.zip", fopen($url, "r"));
		
			$zip = new ZipArchive;
			if ($zip->open("package.zip")) {
				$zip->extractTo(__DIR__);
				$zip->close();
				unlink("package.zip");
			} else {
				die('{"status":500, "error":"Fehler beim Entpacken der Dateien."}');
			}
			$sql = "INSERT INTO apps (name, app_id, url_name) VALUES (?, ?, ?)";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param("sis", $json["name"], $id, $json["url_name"]);
		
			if ($stmt->execute() !== TRUE) {
				$stmt->close();
				$conn->close();
				die('{"status":500, "error":"Fehler beim Ausführen des SQL-Befehls."}');
			} else {
				$stmt->close();
				$conn->close();
				die('{"status":200}');
			}
		}
	}
}
?>