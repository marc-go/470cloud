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
    <script src="reminders.js"></script>
    <?php
    require "../../assets/md3.php";
    ?>
    <link rel="prefetch" href="/apps/home">
    <link rel="prefetch" href="/apps/files">
    <link rel="prefetch" href="/apps/settings">
</head>
<body>
	<md-tabs>
        <md-primary-tab id="home">Home</md-primary-tab>
        <md-primary-tab id="files">Files</md-primary-tab>
        <md-primary-tab id="reminders" active>ToDo</md-primary-tab>
        <md-primary-tab id="settings">Settings</md-primary-tab>
    </md-tabs>
		<md-filled-button onclick="add()">Add</md-filled-button>
        <h3 class="md-typescale-display-medium">Today</h3>
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
                	</div>
					<md-list-item>
						<md-checkbox touch-target="wrapper" onclick="remove(' . $row["id"] . ')"></md-checkbox>
    					<div slot="headline">' . $row["name"] . '</div>
    					<div slot="supporting-text">' . $row["date"] . '</div>
					</md-list-item>
					';
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
