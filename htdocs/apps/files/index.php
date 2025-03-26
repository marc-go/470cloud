<?php
define("PATH", "../../");

require "../../assets/admin.php";

$session = new loginManager();
if ($session->checkLogin()) {
	$session->createNewSession();
}else{
	header("Location: ../../login.php?from=apps/files");
	exit;
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>470Cloud // Home</title>
    <link rel="stylesheet" href="../../assets/css/main.css">
	<style>
		body {
			background-image: url('../../assets/background.php?user=<?php echo $session->getUserName(); ?>');
			background-size: cover;
			background-repeat: no-repeat;
			background-attachment: fixed;
		}
		
		.widget {
			position: fixed;
			z-index: 9999;
			display: none;
			border: 2px solid #000000;
			border-radius: 30px;
			height: auto;
			width: auto;
			background-color: #ffffff;
			color: #000000;
			padding: 20px;
		}
		
		#overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
        }
		
		a {
			color: #000000;
			text-decoration: none;
		}
		
		a:hover {
			text-decoration: underline;
		}
	</style>
</head>
<body>
	<div id="overlay"></div>
    <div id="menu">
		<a href="#">Home</a>
        <a href="../files/">Dateien</a>
        <a href="../reminders/">ToDo</a>
		<a href="../store/">App Store</a>
        <a href="../../login.php?action=logout">Logout</a>
    </div>
    <div id="content">
		<form action="upload.php" method="post" enctype="multipart/form-data">
			<input type="hidden" name="submit" value="true">
			<?php
			if (isset($_GET["folder"])) {
				echo '<input type="hidden" name="folder" value="' . $_GET["folder"] . '">';
			}
			?>
			<input type="file" name="data">
			<input type="submit" value="Hochladen">
		</form>
		<a onclick="newFolder('open')">Ordner hinzufügen</a>
		<span id="error"><?php if (isset($error)) { echo $error; } ?></span>
		<div class="files">
			<?php
			ini_set('display_errors', 1);
			ini_set('display_startup_errors', 1);
			error_reporting(E_ALL);
			
			if (isset($_GET["folder"])) {
				$path = "../../data/users/" . $session->getUserName() . "/files/root/" . $_GET["folder"] . "/";
				$path2 = $_GET["folder"];
				$dir = scandir($path);
			}else{
				$path = "../../data/users/" . $session->getUserName() . "/files/root/";
				$path2 = "";
				$dir = scandir($path);
			}
			
			echo '<div id="file" style="display: none;">' . $path . '</div>';
			
			$json = [];
			
			foreach ($dir as $element) {
				if ($element != "." && $element != "..") {
					array_push($json, $path . $element);
					if (is_dir($path . $element)) {
						echo '
						<div class="item" id="' . $element . '">
							<input type="checkbox" id="checkbox-' . $element . '">
							<a href="?folder=' . $path2 . "/" . $element . '">' . $element . '</a>
							<a onclick=\'edit("' . $element . '")\'>...</a>
						</div>';
					}elseif (file_exists($path . $element)) {
						echo '
						<div class="item" id="' . $element . '">
							<a href="file.php?file=' . $path2 . "/" . $element . '">' . $element . '</a>
							<a onclick=\'edit("' . $element . '")\'>...</a>
						</div>';
					}else{
						echo '
						<script>
							document.getElementById("errorwidget").style.display = "block";
						</script>';
					}
				}
			}
			
			echo '<div id="all-files" style="display: none;">' . json_encode($json) . '</div>';
			?>
		</div>
    </div>
	<span id="error"></span>
	<div class="widget" id="widget">
		<a onclick="closeWidget()">X</a><br>
		<div id="error"></div>
		<h3>Objekt umbennenen</h3>
		<form onsubmit="rename(event)">
		    <input type="text" id="name" placeholder="Neuer Name">
		    <button type="submit">Umbennenen</button>
        </form>
		<h3>Objekt löschen</h3>
		<p>Ausgewähltes Objekt in den Papierkorb verschieben</p>
		<button onclick="remove()">Bestätigen</button>
		<h3>Objekt teilen</h3>
		<ul>
			<li>
				<a href="#" onclick="share(1)">Dateien sind nur Lesbar</a>
			</li>
			<li>
				<a href="#" onclick="share(2)">Es können nur Dateien hochgeladen werden</a>
			</li>
			<li>
			    <a href="#" onclick="share(3)">Vollzugriff</a>
		    </li>
	    </ul>
    </div>
	<div class="widget" id="widget2">
		<a onclick="closeF()">X</a><br>
		<span id="f-error"></span>
		<h3>Neuer Ordner</h3>
		<form onsubmit="addFolder(event)">
			<input type="text" id="f-name" placeholder="Ordner Name"><br>
			<button type="submit">Erstellen</button>
		</form>
	</div>
	<div class="widget" id="errorwidget">
		<h3>Fehler</h3>
		<p>Fehler beim Laden einer Datei.</p>
		<button onclick="closeError()">Schließen</button>
	</div>
    <div id="js-tmp" style="display: none;"></div>
	<script src="options.js"></script>
</body>
</html>