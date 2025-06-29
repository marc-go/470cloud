<?php
require "../../assets/470cloud.php";

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
    addMD();
    ?>
    <style>
        .red-button {
            --md-elevated-button-container-color: red;
        }
    </style>
</head>
<body>
    <?php showMenue(); ?>
    <md-list>
        <?php
        $file = file_get_contents("https://api.470cloud.marc-goering.de/app/cloud/app-store/all.json");
        $file = json_decode($file, true);

        $conn = startDB();

        foreach ($file as $id => $json) {
            $sql = "SELECT * FROM apps WHERE app_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 0) {
                echo '<md-list-item type="link" onclick="info(' . $id . ')">' . $json["name"] . '<img slot="start" style="width: 56px;" src="' . $json["logo"] . '"></md-list-item>';
                echo '<md-dialog id="' . $id . '">
                        <div slot="headline">' . $json["name"] . '</div>
                        <form slot="content" method="dialog">
                            ' . $json["text"] . '
                        </form>
                        <div slot="actions">
                            <a href="/apps/install.php?id=' . $id . '"><md-filled-button>Install</md-filled-button></a>
                            <md-text-button onclick="closeWG(' . $id . ')">Close</md-text-button>
                        </div>
                    </md-dialog>';
            }else{
                echo '<md-list-item type="link" onclick="info(' . $id . ')">' . $json["name"] . '<img slot="start" style="width: 56px;" src="' . $json["logo"] . '"></md-list-item>';
                echo '<md-dialog id="' . $id . '">
                        <div slot="headline">' . $json["name"] . '</div>
                        <form slot="content" method="dialog">
                            ' . $json["text"] . '
                        </form>
                        <div slot="actions">
                            <a href="/apps/remove.php?id=' . $id . '"><md-filled-button class="red-button">Remove</md-filled-button></a>
                            <md-text-button onclick="closeWG(' . $id . ')">Close</md-text-button>
                        </div>
                    </md-dialog>';
            }
        }
        ?>
    </md-list>
    <script src="install.js"></script>
</body>
</html>