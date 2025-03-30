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
        <md-primary-tab><a onclick='openApp("home")'>Home</a></md-primray-tab>
        <md-primary-tab><a onclick='openApp("files")'>Files</a></md-primray-tab>
        <md-primary-tab><a onclick='openApp("reminders")'>ToDo</a></md-primray-tab>
        <md-primary-tab><a onclick='openApp("settings")'>Settings</a></md-primray-tab>
    </md-tabs>
</body>
</html>