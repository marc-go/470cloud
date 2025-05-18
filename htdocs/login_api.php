<?php

echo $_COOKIE["device_id"] . "<br>";
echo $_COOKIE["session_id"] . "<br>";
echo $_COOKIE["user_id"] . "<br>";

require "assets/admin.php";

$session = new loginManager();

echo $session->checkLogin();

?>