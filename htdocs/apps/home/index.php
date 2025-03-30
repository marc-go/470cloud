<?php
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
    <script src="/assets/menue.js"></script>
</head>
<body>
    <md-tabs>
        <a onclick='openApp("home")'><md-primary-tab active>Home</md-primary-tab></a>
        <a onclick='openApp("files")'><md-primary-tab>Files</md-primary-tab></a>
        <a onclick='openApp("reminders")'><md-primary-tab>ToDo</md-primary-tab></a>
        <a onclick='openApp("settings")'><md-primary-tab>Settings</md-primary-tab></a>
    </md-tabs>
</body>
</html>