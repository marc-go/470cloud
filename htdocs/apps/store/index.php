<?php
require $_SERVER["DOCUMENT_ROOT"] . "/assets/admin.php";

$session = new loginManager();
if (!$session->checkLogin()) {
    header("Location: /login.php?from=apps/store");
    exit;
}

if (!$session->getAdmin()) {
    echo "Premmision Denied: You are not an admin.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>470Cloud // App Store</title>
    <?php
    require "../../assets/md3.php";
    ?>
    <link rel="prefetch" href="/apps/reminders">
    <link rel="prefetch" href="/apps/files">
    <link rel="prefetch" href="/apps/settings">
</head>
<body>
    <?php require $_SERVER["DOCUMENT_ROOT"] . "/assets/menue.php"; ?>
    <md-list>
        <?php
        $file = file_get_contents("https://api.470cloud.marc-goering.de/app/cloud/app-store/all.json");
        $file = json_decode($file, true);

        foreach ($file as $id => $json) {
           echo '<md-list-item type="link" onclick="info(' . $id . ')">' . $json["name"] . '<img slot="start" style="width: 56px;" src="' . $json["logo"] . '"></md-list-item>';
        }
        ?>
    </md-list>
</body>
</html>