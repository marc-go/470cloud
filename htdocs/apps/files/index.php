<?php
require "../../assets/470cloud.php";

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
	addMD();
	?>
	<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100&icon_names=add" rel="stylesheet">
	<link rel="prefetch" href="/apps/home">
    <link rel="prefetch" href="/apps/reminders">
    <link rel="prefetch" href="/apps/settings">
</head>
<body>
	<?php showMenue(); ?>

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
			<input type="hidden" name="upload" value="true">
		  	<?php
		  	if (isset($_GET["folder"])) {
				echo '<input type="hidden" name="folder" value="' . $_GET["folder"] . '">';
		  	}
		  	?>
		  	<input type="file" name="data"><br>
	  	</form>
		<div slot="actions">
		  <md-filled-button onclick="document.getElementById('form-1').submit()">Upload</md-filled-button>
		  <md-text-button onclick="document.getElementById('upload_file').removeAttribute('open')" form="form-id">Cancle</md-text-button>
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
		</form>
		<div slot="actions">
		  <md-filled-button onclick="document.getElementById('form-3').submit()">Create File</md-filled-button>
		  <md-text-button onclick="document.getElementById('create_file').removeAttribute('open')" form="form-id">Cancle</md-text-button>
		</div>
	</md-dialog>
		<span id="error"><?php if (isset($error)) { echo $error; } ?></span>
		<div class="files">
			<?php
			ini_set('display_errors', 1);
			ini_set('display_startup_errors', 1);
			error_reporting(E_ALL);

			if (!is_dir($root . "/data/users/" . $session->getUserName() . "/files/root")) {
				mkdir($root . "/data/users/" . $session->getUserName() . "/files");
				mkdir($root . "/data/users/" . $session->getUserName() . "/files/root");
			}
			
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
						<md-list-item
							id="' . $element . '"
						    type="link"
      						href="file.php?file=' . $path2 . '/' . $element . '"
      						target="_blank">
    							<div slot="headline">' . $element . '</div>
 						 </md-list-item>';
					}elseif (file_exists($path . $element)) {
						echo '
						<md-list-item
							id="' . $element . '"
						    type="link"
      						href="file.php?file=' . $path2 . '/' . $element . '"
      						target="_blank">
    							<div slot="headline">' . $element . '</div>
 						 </md-list-item>';
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
	<br>
	<span style="position: relative">
		<md-fab id="usage-anchor" aria-label="Edit">
  			<md-icon slot="icon">add</md-icon>
		</md-fab>
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
	
</body>
</html>
