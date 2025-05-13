<?php
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

require $_SERVER["DOCUMENT_ROOT"] . "/assets/admin.php";
$session = new loginManager();
if (!$session->checkLogin()) {
	header("Location: ../../login.php?from=apps/settings");
	exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>470Cloud // Settings</title>
	<?php
	require $_SERVER["DOCUMENT_ROOT"] . "/assets/md3.php";
	?>
	<style>
		md-fab {
			position: absolute;
			bottom: 5%;
			right: 5%;
		}
	</style>
	<script src="js/users.js" defer></script>
</head>
<body>
	<div id="js-tmp" style="display: none;"></div>
	<md-tabs>
        <md-primary-tab id="home">Home</md-primary-tab>
        <md-primary-tab id="files">Files</md-primary-tab>
        <md-primary-tab id="reminders">ToDo</md-primary-tab>
        <md-primary-tab id="settings" active>Settings</md-primary-tab>
    </md-tabs>
	<md-list>
		<?php
		if ($session->getAdmin()) {
			echo '
			<md-list-item>Admin Settings</md-list-item>
			<md-list-item type="link" href="/apps/settings/admin/users.php">
    			<div slot="headline">User</div>
    			<div slot="supporting-text">User Managment</div>
  			</md-list-item>';
		}
		?>
	</md-list>
	<md-divider></md-divider>
	<md-list>
		<?php
		require $_SERVER["DOCUMENT_ROOT"] . "/assets/db.php";

		$sql = "SELECT * FROM users";
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->get_result();

		while ($row = $result->fetch_assoc()) {
			echo '
			<md-list-item type="link" onclick="editUser(' . $row["id"] . ');">
    			<div slot="headline">' . $row["username"] . '</div>
    			<div slot="supporting-text">' . $row["mail"] . '</div>
  			</md-list-item>';
		}
		?>
	</md-list>
	<md-dialog id="editWG">
  		<div slot="headline">
    		Edit User "<span id="wg_title_username"></span>"
  		</div>
  		<form slot="content" id="form-id" method="dialog">
    		<md-outlined-text-field label="Username" id="wg_username" value=""></md-outlined-text-field><br><br>
			<md-outlined-text-field label="E-Mail" id="wg_mail" value=""></md-outlined-text-field><br><br>
			<md-outlined-text-field label="New Password" id="wg_pw" value=""></md-outlined-text-field><br><br>
			<md-outlined-text-field label="Confirm" id="wg_pw2" value=""></md-outlined-text-field><br><br>
			<md-switch id="wg_admin">Admin</md-switch>
		</form>
  		<div slot="actions">
			<md-text-button onclick="closeEdit()">Close</md-text-button>
    		<md-filled-button onclick="saveEdit()">Save</md-filled-button>
  		</div>
	</md-dialog>
	<md-dialog id="addWG">
		<div slot="headline">
			Add User
		</div>
		<form slot="content" id="add-form" method="dialog">
			<md-outlined-text-field id="user" label="Username"></md-outlined-text-field><br><br>
			<md-outlined-text-field id="mail" label="E-Mail"></md-outlined-text-field><br><br>
			<md-outlined-text-field id="pw" type="password" label="Password"></md-outlined-text-field><br><br>
			<md-outlined-text-field id="pw2" type="password" label="Confirm password"></md-outlined-text-field><br><br>
			<md-switch id="admin"></md-switch>
		</form>
		<div slot="actions">
		  <md-text-button onclick="closeAdd()">Cancle</md-text-button>
		  <md-filled-button onclick="addUser()">Save</md-filled-button>
		</div>
	</md-dialog>
	<md-fab onclick="addUserOpen()" aria-label="Edit">
  		<md-icon slot="icon">add</md-icon>
	</md-fab>
</body>
</html>