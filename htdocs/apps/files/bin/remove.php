<?php
//Check Login

require "../../../assets/470cloud.php";
$session = new loginManager();
if (!$session->checkLogin()) {
	die('{"status":500, "error":"Bitte lade die Seite neu."}');
}

function removeFolder($dir) {
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


//Ckeck File
if (isset($_GET["name"])) {
    $name = $_GET["name"];
	if (is_file("../" . $name)) {
		if (unlink("../" . $name)) {
			echo '{"status":200}';
		}else{
			echo '{"status":500, "error":"Fehler beim löschen der Datei."}';
		}
	}elseif (is_dir("../" . $name)) {
		removeFolder("../" . $name);
		
		die('{"status":200}');
	}else{
		die('{"status":500, "error":"Der Ordner oder die Datei exestiert nicht."}');
	}
}else{
    echo '{"status":400, "error": "Fehler beim übertragen der Datei ID."}';
}
?>