<?php
define("PATH", "../../");

require '../../assets/admin.php';
$session = new loginManager();
if ($session->checkLogin() === true) {
    $session->createNewSession();
} else {
    header("Location: ../../login.php?from=apps/settings/info.php");
    exit;
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
		
		#settings-menue {
    		margin: 20px;
    		border-radius: 30px;
    		padding: 13px;
    		height: 100vh;
    		box-shadow: 0px 0px 13px -4px #000000;
			background-color: #007AFF;
			width: 100px;
			z-index: 9999;			
			position: fixed;
		}
		
		#content {
			padding: 0;
		}
		
		.inner-content {
			text-align: center;
			top: 0;
			padding: 20px;
		}
		
		#menue-open {
			text-align: left;
			margin: 20px;
		}
		
		#link {
			color: #000000;
			text-decoration: none;
		}
    </style>
	<link rel="prefetch" href="/apps/home">
    <link rel="prefetch" href="/apps/files">
    <link rel="prefetch" href="/apps/reminders">
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
        <div id="settings-menue">
			<a onclick="closeMenue()">Schließen</a><br><br><br>
			<span id="selectet">Info</span><br><br>
			<a id="link" href="user.php">Deine Infos</a>
		</div>
		<div class="content">
			<br><br><a onclick="openMenue()" id="menue-open">Menü</a>
			<div class="inner-content">
				<h2>Info</h2>
				<?php
				$file = file_get_contents("../../assets/package.json");
				$file = json_decode($file, true);
			
				echo '<h3>' . $file["name"] . '</h3>';
				echo '<p>Version <b>' . $file["version"] . '</b></p>';
				?>
				<h2>470Cloud empfehlen</h2>
				<pre><a id="link" href="https://470cloud.com">https://470cloud.com</a></pre>
				<button onclick="shareCloud()">Kopieren</button>
				
			</div>
		</div>
    </div>
	<script>
		function openMenue() {
			document.getElementById("settings-menue").style.display = "block";
		}
		
		function closeMenue() {
			document.getElementById("settings-menue").style.display = "none";
		}
		
		function shareCloud() {
            const text = "https://470cloud.com";

            if (navigator.clipboard) {
                navigator.clipboard.writeText(text)
                    .then(() => {
                        console.log('Success');
                    })
                    .catch(err => {
                        console.error('Fehler beim Kopieren in die Zwischenablage:', err);
                    });
            } else {
                console.error('Clipboard API wird nicht unterstützt.');
            }
        }
	</script>
</body>
</html>