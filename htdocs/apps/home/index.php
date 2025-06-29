<?php
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

require '../../assets/470cloud.php';
$session = new loginManager();
if (!$session->checkLogin()) {
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
    addMD();
    ?>
    <link rel="prefetch" href="/apps/reminders">
    <link rel="prefetch" href="/apps/files">
    <link rel="prefetch" href="/apps/settings">
</head>
<body>
    <?php showMenue(); ?>
</body>
</html>
