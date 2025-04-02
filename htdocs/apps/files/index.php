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
    <?php
	require "../../assets/md3.php";
	?>
	<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100&icon_names=add" rel="stylesheet">
</head>
<body>
	<md-tabs>
        <md-primary-tab id="home">Home</md-primary-tab>
        <md-primary-tab id="files" active>Files</md-primary-tab>
        <md-primary-tab id="reminders">ToDo</md-primary-tab>
        <md-primary-tab id="settings">Settings</md-primary-tab>
    </md-tabs>

	<span style="position: relative">
		<md-filled-button id="usage-anchor">Create</md-filled-button>
		<md-menu id="usage-menu" anchor="usage-anchor">
		  <md-menu-item>
			<div onclick="openDialog('create_file')" slot="headline">Create File</div>
		  </md-menu-item>
		  <md-menu-item>
			<div onclick="openDialog('upload_file')" slot="headline">Upload File</div>
		  </md-menu-item>
		  <md-menu-item>
			<div onclick="openDialog('create_folder')" slot="headline">Create Folder</div>
		  </md-menu-item>
		</md-menu>
	  </span>

	  <script>
		function openDialog(id) {
			document.getElementById(id).setAttribute("open", "");
		}
	  </script>
	  
	  <script type="module">
		// This example uses anchor as an ID reference
		const anchorEl = document.body.querySelector('#usage-anchor');
		const menuEl = document.body.querySelector('#usage-menu');
	  
		anchorEl.addEventListener('click', () => { menuEl.open = !menuEl.open; });
	  </script>

	<md-dialog id="upload_file">
		<div slot="headline">
		  Upload File
		</div>
		<form slot="content" id="form-1" action="upload.php" method="post" enctype="multipart/form-data">
			<input type="hidden" name="submit" value="true">
		  	<?php
		  	if (isset($_GET["folder"])) {
				echo '<input type="hidden" name="folder" value="' . $_GET["folder"] . '">';
		  	}
		  	?>
		  	<input type="file" name="data"><br>
		  	<input type="submit" value="Hochladen">
	  	</form>
		<div slot="actions">
		  <md-text-button onclick="document.getElementById('upload_file').removeAttribute('open')" form="form-id">Ok</md-text-button>
		</div>
	  </md-dialog>

	  <md-dialog id="create_folder">
		<div slot="headline">
		  Create Folder
		</div>
		<form slot="content" id="form-2" action="bin/folder.php" method="get">
			<?php
			if (isset($_GET["folder"])) {
				echo '<input type="hidden" name="folder" value="' . $_GET["folder"] . '">';
			}
			?>
			<md-outlined-text-field type="text" name="name" label="New Folder" value=""></md-outlined-text-field><br>
			<md-filled-button type="submit">Create Folder</md-filled-button>
		</form>
		<div slot="actions">
		  <md-text-button onclick="document.getElementById('create_folder').removeAttribute('open')" form="form-id">Ok</md-text-button>
		</div>
	  </md-dialog>

	  <md-dialog id="create_file">
		<div slot="headline">
		  Create File
		</div>
		<form slot="content" id="form-3" action="bin/file.php" method="post">
			<?php
			if (isset($_GET["folder"])) {
				echo '<input type="hidden" name="folder" value="' . $_GET["folder"] . '">';
			}
			?>
			<md-outlined-text-field type="text" name="name" label="Filename" value=""></md-outlined-text-field><br>
			<md-filled-button type="submit">Create File</md-filled-button>
		</form>
		<div slot="actions">
		  <md-text-button onclick="document.getElementById('create_file').removeAttribute('open')" form="form-id">Ok</md-text-button>
		</div>
	</md-dialog>
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
	<md-fab aria-label="add">
  		<md-icon slot="icon">add</md-icon>
	</md-fab>
</body>
</html>
