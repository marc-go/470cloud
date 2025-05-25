<?php
require "assets/admin.php";

$session = new loginManager();

echo $session->checkLogin() . "<br>";
echo $session->gunfI(7);

?>