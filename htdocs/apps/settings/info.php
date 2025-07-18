<?php
define("PATH", "../../");

require "../../assets/470cloud.php";
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
	addMD();
	?>
</head>
<body>
	<?php showMenue(); ?>
	<md-list>
		<?php
		if ($session->getAdmin()) {
			echo '
			<md-list-item>Admin Settings</md-list-item>
			<md-list-item type="link" href="admin/users.php">
    			<div slot="headline">User</div>
    			<div slot="supporting-text">User Managment</div>
  			</md-list-item>
			<md-divider></md-divider>
			';
		}
		?>
		<md-list-item>Your Settings</md-list-item>
		<md-list-item type="link" href="you.php">
			<div slot="headline">Your Account</div>
			<div slot="supporting-text">Manage your Account</div>
		</md-list-item>
	</md-list>
</body>
</html>