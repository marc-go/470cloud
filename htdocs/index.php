<?php
define("PATH", "");

require "assets/admin.php";
$session = new loginManager();
if (!$session->checkLogin()) {
	header("Location: login.php");
	exit;
}else{
	header ("Location: apps/home");
	exit;
}
?>