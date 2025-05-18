<?php
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

define("PATH", "../../");

require '../../assets/admin.php';
$session = new loginManager();
if ($session->checkLogin() === true) {
    $session->createNewSession();
}else{
    header("Location: ../../login.php?from=apps/home");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>470Cloud // Home</title>
    <?php
    require "../../assets/md3.php";
    ?>
    <link rel="prefetch" href="/apps/reminders">
    <link rel="prefetch" href="/apps/files">
    <link rel="prefetch" href="/apps/settings">
</head>
<body>
    <md-tabs>
        <md-primary-tab id="home" active>Home</md-primary-tab>
        <md-primary-tab id="files">Files</md-primary-tab>
        <md-primary-tab id="reminders">ToDo</md-primary-tab>
        <md-primary-tab id="settings">Settings</md-primary-tab>
    </md-tabs>
</body>
</html>
