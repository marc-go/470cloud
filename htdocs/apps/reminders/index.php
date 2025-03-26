<?php
require '../../assets/admin.php';
define("PATH", "../../");

$session = new loginManager();
if ($session->checkLogin()) {
	$session->createNewSession();
}else{
	header("Location: ../../login.php?from=apps/reminders");
	exit;
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>470Cloud // ToDo</title>
    <link rel="stylesheet" href="../../assets/css/main.css">
    <style>
        body {
            background-image: url('../../assets/background.php?user=<?php echo $session->getuserName(); ?>');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
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
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
        }
		
		#js-tmp {
			display: none;
		}
    </style>
    <script src="reminders.js"></script>
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
        <button onclick="add()">+</button>
        <h3>Heute</h3>
		<div id="today">
        	<?php
        	error_reporting(E_ALL);
        	ini_set('display_errors', 1);
        	ini_set('display_startup_errors', 1);
        
        	require "../../assets/db.php";
        
        	$date = date("Y-m-d");
        	$show = 0;
        
        	$sql = "SELECT * FROM reminders WHERE user = ? AND date = ? AND trash = ?";
        	$stmt = $conn->prepare($sql);
        	@$stmt->bind_param("ssi", $session->getUserName(), $date, $show);
        	$stmt->execute();
        
        	$result = $stmt->get_result();
        	if ($result->num_rows <= 0) {
            	echo '<span>Für heute alles erledigt.</span>';
        	} else {
           	 	while ($row = $result->fetch_assoc()) {
                	echo '
                	<div id="remind" class="' . $row["id"] . '" type="today" name="' . $row["name"] . '">
                    	<input type="checkbox" onclick="remove(' . $row["id"] . ')">
						<a onclick="info(' . $row["id"] . ')" id="' . $row["id"] . '">' . $row["name"] . '</a>
                	</div>';
            	}
				echo '<div id="js-tmp-1" style="display: none;">' . $result->num_rows . '</div>';
        	}
        	?>
		</div>
		<h3>Alle</h3>
		<div id="all">
			<?php
        	error_reporting(E_ALL);
        	ini_set('display_errors', 1);
        	ini_set('display_startup_errors', 1);
        
        	require "../../assets/db.php";
        
        	$show = 0;
        
        	$sql = "SELECT * FROM reminders WHERE user = ? AND trash = ?";
        	$stmt = $conn->prepare($sql);
       		@$stmt->bind_param("si", $session->getUserName(), $show);
        	$stmt->execute();
        
        	$result = $stmt->get_result();
       		if ($result->num_rows <= 0) {
            	echo '<span>Hier gibt es nichts.</span>';
        	} else {
          	 	while ($row = $result->fetch_assoc()) {
                	echo '
                	<div id="remind" class="' . $row["id"] . '" type="all" name="' . $row["name"] . '">
                    	<input type="checkbox" onclick="remove(' . $row["id"] . ')">
						<a onclick="info(' . $row["id"] . ')" id="' . $row["id"] . '">' . $row["name"] . '</a>
               		</div>';
            	}
				echo '<div id="js-tmp-2" style="display: none;">' . $result->num_rows . '</div>';
       		}
        	?>
		</div>
        <h3>Gelöscht</h3>
        <div id="removed">
            <?php
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
        
            require "../../assets/db.php";
        
            $show = 1;
        
            $sql = "SELECT * FROM reminders WHERE user = ? AND trash = ?";
            $stmt = $conn->prepare($sql);
            @$stmt->bind_param("si", $session->getUserName(), $show);
            $stmt->execute();
        
            $result = $stmt->get_result();
            if ($result->num_rows <= 0) {
                echo '<span id="rm-msg">Hier gibt es nichts.</span>';
            } else {
                while ($row = $result->fetch_assoc()) {
                    echo '
                    <div class="remind" id="' . $row["id"] . '" type="removed">
                        <a onclick="deleteWidgetOpen(' . $row["id"] . ')">' . $row["name"] . '</a>
                    </div>';
                }
            }
            ?>
        </div>
    </div>
    <div class="widget" id="info">
        <a onclick="closeInfo()">X</a>
        <h3>Name</h3>
        <span id="w-name"></span>
        <h3>Datum</h3>
        <span id="w-date"></span>
        <div id="w-temp" style="display: none;"></div>
    </div>
    <div class="widget" id="add">
        <a onclick="closeAdd()">X</a>
        <h3>Neuer Eintrag</h3>
        <input type="text" id="a-name" placeholder="Erinnerung"><br>
        <input type="date" id="a-date" value="<?php echo date("d.m.Y"); ?>"><br>
        <button onclick="submitAdd()">Hinzufügen</button>
    </div>
	<div class="widget" id="delete">
		<a onclick="deleteWidgetClose()">X</a>
		<h3>Löschen</h3>
		<button onclick="refreshRemind()">Wiederherstellen</button><br>
		<button onclick="deleteRemind()">Löschen</button>
	</div>
    <div id="overlay"></div>
	<div id="js-tmp"></div>
</body>
</html>