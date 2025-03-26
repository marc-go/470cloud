<?php
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

function removeAppFiles($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
            foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (is_dir($dir . "/" . $object) && !is_link($dir . "/" . $object)) {
                    removeAppFiles($dir . "/" . $object);
                }else{
                    unlink($dir . "/" . $object);
                }
            }
        }
        rmdir($dir);
    }
}

define("PATH", "../");

require "../assets/admin.php";
$session = new loginManager();
if (!$session->checkLogin()) {
	die('{"status":500, "error":"Du bist nicht angemeldet.<br>Bitte lade die Seite neu."}');
}

$user = $session->getUserName();
require "../assets/uinfo.php";
if (!$uinfo["admin"] === true) {
    die('{"status":500, "error":"Premission denied: You are not an admin."}');
}

if (isset($_GET["id"])) {
    $id = intval($_GET["id"]);

    require "../assets/db.php";

    $sql = "SELECT * FROM apps WHERE app_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
	if ($result->num_rows <= 0) {
		$stmt->close();
		$conn->close();
		die('{"status":500, "error":"Die App ist nicht installiert."}');
	}
	$row = $result->fetch_assoc();

    $dir = $row["url_name"];
    removeAppFiles($dir);

    if ($row["data_folder"] = 1) {
        removeAppFiles("../data/" . $row["url_name"]);
    }
    if ($row["data_table"] == 1) {
        $sql = "DROP TABLE " . $row["url_name"];

        if (!$conn->query($sql) === TRUE) {
			$stmt->close();
			$conn->close();
            die('{"status":500, "error":"Fehler beim löschen der App Daten."}');
        }
    }
	
	$sql = "DELETE FROM apps WHERE app_id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("i", $id);
	
	if (!$stmt->execute() === TRUE) {
		$stmt->close();
		$conn->close();
		die('{"status":500, "error":"Fehler beim ausführen der SQL Abfrage."}');
	}else{
		$stmt->close();
		$conn->close();
		die('{"status":200}');
	}
}else{
	die('{"status":500, "error":"Keine App angegeben."}');
}
?>