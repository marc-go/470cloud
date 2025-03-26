<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define("PATH", "../../");

require "../../assets/admin.php";
$session = new loginManager();
if (!$session->checkLogin()) {
	header("../../login?from=apps/files");
	exit;
}

if (isset($_POST["submit"])) {
	$tmp_name = $_FILES["data"]["tmp_name"];
	$name = basename($_FILES["data"]["name"]);
	
	if (isset($_POST["folder"])) {
		$folder = $_POST["folder"];
	}else{
		$folder = "/";
	}
	
	$path = "../../data/users/" . $session->getUserName() . "/files/root" . $folder . "/" . $name;
	
	if (!move_uploaded_file($tmp_name, $path)) {
		$error = "Es gab einen Fehler beim hochladen: ";
		
		switch ($_FILES["data"]["error"]) {
    		case UPLOAD_ERR_INI_SIZE:
        		$error .= "Die hochgeladene Datei überschreitet die upload_max_filesize Direktive in php.ini.";
            	break;
       		case UPLOAD_ERR_FORM_SIZE:
            	$error .= "Die hochgeladene Datei überschreitet die MAX_FILE_SIZE Direktive, die im HTML-Formular angegeben wurde.";
            	break;
      		case UPLOAD_ERR_PARTIAL:
           		$error .= "Die Datei wurde nur teilweise hochgeladen.";
            	break;
       		case UPLOAD_ERR_NO_FILE:
           		$error .= "Es wurde keine Datei hochgeladen.";
            	break;
       		case UPLOAD_ERR_NO_TMP_DIR:
        		$error .= "Fehlendes temporäres Verzeichnis.";
            	break;
        	case UPLOAD_ERR_CANT_WRITE:
            	$error .= "Fehler beim Schreiben der Datei auf die Festplatte.";
            	break;
        	case UPLOAD_ERR_EXTENSION:
            	$error .= "Eine PHP-Erweiterung hat den Datei-Upload gestoppt.";
            	break;
        	default:
           		$error .= "Unbekannter Fehler.";
            	break;
        }
		header("Location: ../files/?error=true&msg=" . $error);
		exit;
    }else{
		if (isset($_POST["folder"])) {
			header("Location: ../files/?error=false&folder=" . $_POST["folder"]);
			exit;
		}else{
			header("Location: ../files/?error=false");
			exit;
		}
	}
}
?>