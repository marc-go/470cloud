<?php
define("PATH", "../../");

require '../../assets/admin.php';
$session = new loginManager();
if ($session->checkLogin() === true) {
    $session->createNewSession();
} else {
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
    <link rel="stylesheet" href="../../assets/css/main.css">
    <style>
        body {
            background-image: url('../../assets/background.php?user=<?php echo $session->getUserName(); ?>');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
    </style>
    <?php
    require "../../assets/md3.php";
</head>
<body>
    <div id="menu">
        <a href="#">Home</a>
        <a href="../files/">Dateien</a>
        <a href="../reminders/">ToDo</a>
		<a href="../store/">App Store</a>
        <a href="../../login.php?action=logout">Logout</a>
    </div>
    <div id="clear-content">
        <?php echo '<h1>Willkommen, ' . $session->getUserName() . '</h1>'; ?>
    </div>
</body>
</html>