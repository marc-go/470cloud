<?php
define("PATH", "../../../");

require "../../../assets/admin.php";
$session = new loginManager();
if (!$session->checkLogin()) {
	die('{"status":500, "error":"Bitte lade die Seite neu."}');
}

if (isset($_GET["name"]) && isset($_GET["path"]) && isset($_GET["file"])) {
	$name = $_GET["name"];
	$path = $_GET["path"];
	$file = $_GET["file"];
	
	$old = "../" . $path . $file;
	$new = "../" . $path . $name;
	
	if (!rename($old, $new)) {
		die('{"status":500, "error":"Es gab einen Fehler beim umbennennen."}');
	}else{
		die('{"status":200}');
	}
}else{
	die('{"status":500, "error":"Fehler beim übertragen der erforderlichen Parameter."}');
}
?>