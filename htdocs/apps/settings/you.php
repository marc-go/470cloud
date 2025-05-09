<?php
define("PATH", "../../");

require "../../assets/admin.php";
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
	require "../../assets/md3.php";
	?>
</head>
<body>
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
			<md-list-item type="link" href="users.php">
    			<div slot="headline">User</div>
    			<div slot="supporting-text">User Managment</div>
  			</md-list-item>';
		}
		?>
		<md-list-item>Your Settings</md-list-item>
		<md-divider></md-divider>
		<md-list-item type="link" href="you.php">Your Account</md-list-item>
	</md-list>
    <md-outlined-text-field id="user" label="Username"></md-outlined-text-field><br><br><br>
    <md-outlined-text-field id="mail" label="E-Mail"></md-outlined-text-field><br><br><br>
    <md-outlined-text-field id="password" label="Password"></md-outlined-text-field><br><br><br>
    <md-outlined-text-field id="password2" label="Confirm Password"></md-outlined-text-field><br><br><br>
</body>
</html>