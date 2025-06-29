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
	<div id="js-tmp" style="display: none;">
		<?php
		$conn = startDB();
		$sql = "SELECT id FROM users WHERE username = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("s", $session->getUserName());
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();
		$id = $row["id"];
		echo $id;
		?>
	</div>
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
	<?php
	$sql = "SELECT * FROM users WHERE username = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("s", $session->getUserName());
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	?>
    <md-outlined-text-field id="user" label="Username" value="<?php echo $row["username"]; ?>"></md-outlined-text-field><br><br>
    <md-outlined-text-field id="mail" label="E-Mail" value="<?php echo $row["mail"]; ?>"></md-outlined-text-field><br><br>
    <md-outlined-text-field id="password" label=" New password"></md-outlined-text-field><br><br>
    <md-outlined-text-field id="password2" label="Confirm new password"></md-outlined-text-field><br><br>
	<md-filled-button onclick="saveSettings()">Save</md-filled-button>

	<script src="js/you.js"></script>
</body>
</html>