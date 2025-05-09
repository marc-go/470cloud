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
	<md-list style="background-color: #ffffff;">
		<h3 class="md-typescale-display-medium">Today</h3>
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
            	echo '<p class="md-typescale-body-medium">Für heute alles erledigt.</p>';
        	} else {
           	 	while ($row = $result->fetch_assoc()) {
                	echo '
					<md-list-item>
						<md-checkbox touch-target="wrapper" onclick="remove(' . $row["id"] . ')"></md-checkbox>
    					<div slot="headline" onclick="info(' . $row["id"] . ')">' . $row["name"] . '</div>
    					<div slot="supporting-text">' . $row["date"] . '</div>
					</md-list-item>
					';
            	}
				echo '<div id="js-tmp-1" style="display: none;">' . $result->num_rows . '</div>';
        	}
        	?>
		</div>
		<h3 class="md-typescale-display-medium">Alle</h3>
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
            	echo '<p class="md-typescale-body-medium">Hier gibt es nichts.</p>';
        	} else {
          	 	while ($row = $result->fetch_assoc()) {
                	echo '
                	<md-list-item>
						<md-checkbox touch-target="wrapper" onclick="remove(' . $row["id"] . ')"></md-checkbox>
    					<div slot="headline">' . $row["name"] . '</div>
    					<div slot="supporting-text">' . $row["date"] . '</div>
					</md-list-item>';
            	}
				echo '<div id="js-tmp-2" style="display: none;">' . $result->num_rows . '</div>';
       		}
        	?>
		</div>
        <h3 class="md-typescale-display-medium">Gelöscht</h3>
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
                echo '<p class="md-typescale-body-medium">Hier gibt es nichts.</p>';
            } else {
                while ($row = $result->fetch_assoc()) {
                    echo '
                    <md-list-item onclick="deleteWidgetOpen(' . $row["id"] . ')">
    					<div slot="headline">' . $row["name"] . '</div>
    					<div slot="supporting-text">' . $row["date"] . '</div>
					</md-list-item>
					';
                }
            }
            ?>
    	</div>
	</md-list>
		<md-dialog id="add">
			<div slot="headline">
			  Add Task
			</div>
			<form slot="content" id="form-id" method="post" action="bin/add.php">
				<md-filled-text-field name="name" label="Task" value="" type="text"></md-filled-text-field><br><br>
				<md-filled-text-field name="date" label="Date" value="" type="date"></md-filled-text-field>
			</form>
			<div slot="actions">
			  <md-text-button onclick="closeAdd()">Cancle</md-text-button>
			  <md-text-button form="form-id">Add</md-text-button>
			</div>
		  </md-dialog>
		  <md-dialog id="rmWidget">
			<div slot="headline">Options</div>
			<form slot="content" id="form-id" method="dialog">
			  Here you can restore or delete the remind.
			</form>
			<div slot="actions">
			  <md-filled-button onclick="restoreRemind()">Restore</md-filled-button>
			  <md-filled-button onclick="deleteRemind()">Delete</md-filled-button>
			  <md-text-button onclick="deleteWidgetClose()">Cancle</md-text-button>
			</div>
		  </md-dialog>
	<div id="js-tmp" style="display: none;"></div>
</body>
</html>