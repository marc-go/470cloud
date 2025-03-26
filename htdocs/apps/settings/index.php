<?php
define("PATH", "../../");

require "../../assets/admin.php";

$session = new loginManager();
if ($session->checkLogin()) {
	header("Location: info.php");
	exit;
}else{
	header("Location: ../../login.php?from=apps/settings");
	exit;
}
?>