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
        <div id="settings-menue">
			<a onclick="closeMenue()">Schließen</a><br><br><br>
			<a id="link" href="info.php">Info</a><br><br>
			<span id="selectet">Deine Infos</span>
		</div>
		<div class="content">
			<br><br><a onclick="openMenue()" id="menue-open">Menü</a>
			<div class="inner-content">
				<h2>Deine Infos</h2>
				<pre><h2><?php echo $session->getUserName(); ?></h2></pre>
				<h3>Passwort</h3>
				<p>Ändere dein Anmelde Passwort.</p>
				<input type="password" id="pw-1" placeholder="Aktuelles Passwort"><br><br>
				<input type="password" id="pw-2" placeholder="Neues Passwort"><br>
				<input type="password" id="pw-3" placeholder="Passwort wiederholden"><br>
				<button onclick="changePasswort()">Ändern</button>
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
		
		function changePasswort() {
			const pw1 = document.getElementById("pw-1").value;
			const pw2 = document.getElementById("pw-2").value;
			const pw3 = document.getElementById("pw-3").value;
			
			if (pw2 === pw3) {
				fetch("bin/changepasswort.php?pw1=" + pw1 + "&pw2=" + pw2 + "&pw3=" + pw3)
					.then(response => response.json())
					.then(data => {
						if (data.status == 500) {	
							document.getElementById("widget").style.display = "block";
							document.getElementById("overlay").style.display = "block";
							document.getElementById("error").innerHTML = data.error;
						}
					})
					.catch(error => {
						document.getElementById("widget").style.display = "block";
						document.getElementById("overlay").style.display = "block";
						document.getElementById("error").innerHTML = error;
					})
			}else{
				document.getElementById("widget").style.display = "block";
				document.getElementById("overlay").style.display = "block";
				document.getElementById("error").innerHTML = "Die Passwörter stimmen nicht überein.";
			}
		}
		
		function closeWidget() {
			document.getElementById("widget").style.display = "none";
			document.getElementById("overlay").style.display = "none";
		}
	</script>
	<div class="widget" id="widget">
		<h3>Fehler</h3>
		<span id="error"></span>
		<button onclick="closeWidget()">Schließen</button>
	</div>
	<div id="overlay"></div>
</body>
</html>