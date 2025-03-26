<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define("PATH", "../../");

require "../../assets/admin.php";
$session = new LoginManager();
if (!$session->checkLogin()) {
	header("Location: ../../login.php");
	exit;
}else{
	$session->createNewSession();
}

$user = $session->getUserName();

require "../../assets/uinfo.php";

if (!$uinfo["admin"] === true) {
	die ("Premission denied: You are not an admin.");
}
?>
<!DOCTYPE html>
<html lang="en">
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
		
		img {
			width: 175px;
			height: 175px;
		}
		
		.widget {
            top: 50%;
            left: 50%;
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
			width: 100%;
			height: 100%;
			background: rgba(0, 0, 0, 0.7);
			position: fixed;
			z-index: 1000;
			top: 0;
			bottom: 0;
			left: 0;
			right: 0;
			margin: 0;
		}
		
		.loader {
			width: 100px;
			height: 100px;
			border: 16px solid #f3f3f3;
			border-top: 16px solid #3498db;
			animation: load 2s linear infinite;
			border-radius: 300px;
		}
		
		@keyframes load {
			0% {
				transform: rotate(0deg);
			}
			
			25% {
				transform: rotate(90deg);
			}
			
			50% {
				transform: rotate(180deg);
			}
			
			75% {
				transform: rotate(270deg);
			}
			
			100% {
				transform: rotate(360deg);
			}
		}
		
		#widget-loader,
		#widget-remove-app-loader {
			display: none;
		}
		
		.apps {
			display: flex;
		}
		
		.app {
			padding: 10px;
		}
	</style>
</head>
<body>
    <div id="menu">
		<a href="#">Home</a>
        <a href="../files/">Dateien</a>
        <a href="../reminders/">ToDo</a>
		<a href="../store/">App Store</a>
        <a href="../../login.php?action=logout">Logout</a>
    </div>
    <div id="content">
		<h1>App Store</h1>
		<div class="apps">
			<?php
			require "../../assets/db.php";
			
			$url = "https://api.470cloud.marc-goering.de/app/cloud/app-store/all.json";
			$file = file_get_contents($url);
			$array = json_decode($file, true);
			
			foreach($array as $id => $json) {				
				$sql = "SELECT * FROM apps WHERE app_id = ?";
				$stmt = $conn->prepare($sql);
				$stmt->bind_param("i", $id);
				$stmt->execute();
				
				echo '
				<div class="app">
					<img src="' . $json["logo"] . '"><br>
					<h3>' . $json["name"] . '</h3>
					<p>' . $json["text"] . '</p>';
				
				$result = $stmt->get_result();
				if (!$result->num_rows > 0) {
					echo '<button onclick="install(' . $id . ')">Installieren</button>';
				}else{
					echo '
					<a href="../' . $json["url_name"] . '">
						<button>Öffnen</button>
					</a>
					<button onclick="remove(' . $id . ')">Deinstallieren</button>';
				}
				echo '</div>';
			}
			?>
		</div>
    </div>
	<script src="appstore.js"></script>
	<div id="widget" class="widget">
		<div id="widget-content">
			<h3>App installieren</h3>
			<span>Möchtest du diese App installieren?</span><br>
			<button onclick="submitInstall()">Ja</button><br>
			<button onclick="closeInstallConfirm()">Nein</button>
		</div>
		<div id="widget-loader">
			<div class="loader"></div>
		</div>
		<div id="widget-success" style="display: none;">
			<h3>Erfolgreich!</h3>
			<span>Deine App wurde erfolgreich installiert!</span>
			<button onclick="window.location.reload()">Schließen</button>
		</div>
		<div id="widget-error" style="display: none;">
			<h3>Fehler</h3>
			<span id="error"></span>
			<button onclick="closeInstallConfirm()">Schließen</button>
		</div>
	</div>
	<div id="widget-remove-app" class="widget">
		<div id="widget-remove-app-content">
			<h3>App löschen</h3>
			<p>Möchtest du diese App wirklich mitsamt Daten löschen?</p><br>
			<button onclick="submitRemoveApp()">Ja</button><br>
			<button onclick="closeRemoveApp()">Nein</button><br>
		</div>
		<div id="widget-remove-app-loader">
			<div class="loader"></div>
		</div>
		<div id="widget-remove-app-success" style="display: none;">
			<h3>Erfolgreich!</h3>
			<p>Deine App wurde gelöscht.</p>
			<button onclick="window.location.reload()">Schließen</button>
		</div>
		<div id="widget-remove-app-error" style="display: none;">
			<h3>Fehler</h3>
			<span id="widget-remove-app-span-error"></span><br>
			<button onclick="closeRemoveApp()">Schließen</button>
		</div>
	</div>
	<div id="overlay"></div>
	<div id="js-tmp"></div>
</body>
</html>