<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php
    require "../assets/470cloud.php";
    addMD();
    ?>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
            font-family: Roboto, sans-serif;
        }

        .loader-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        md-circular-progress {
            --md-circular-progress-size: 48px;
            --md-circular-progress-active-indicator-color: #6200ee;
        }
    </style>
</head>
<body>
    <div class="loader-container">
        <md-circular-progress indeterminate></md-circular-progress>
        <span>Install...</span>
        <?php
        if (isset($_GET["error"])) {
            echo '<span>' . $_GET["error"] . '</span>';
        }
        ?>
    </div>
    <script>
        fetch("install.php?id=<?php echo $_GET["id"]; ?>")
            .then(response => response.json())
            .then(data => {
                if (data.status == 200) {
                    window.location.href = "/apps/store";
                }else{
                    document.getElementById("error").innerHTML = data.error;
                }
            })
    </script>
</body>
</html>

<?php
$file = file_get_contents("https://api.470cloud.marc-goering.de/app/cloud/app-store/all.json");
$file = json_decode($file, true);

$id = intval($_GET["id"]);
$app = $file[$id];

$url = $app["download-url"];
$path = $root . "/tmp/app_store/" . $app["url_name"] . ".zip";

if (!file_put_contents($path, fopen($url, "r"))) {
    header("Location: ?error=App%20cannot%20download.");
    exit;
}

$zip = new ZipArchive();

if ($zip->open($path)) {
    $zip->extractTo($root . "/apps/");
    $zip->close();
}else{
    header("Location: ?error=Zip File cannot open.");
    exit;
}

unlink($path);

$conn = startDB();
$sql = "INSERT INTO apps (name, app_id, url_name) VALUES (?, ?, ?)";
$stmt =  $conn->prepare($sql);
$stmt->bind_param("sss", $app["name"], $id, $app["url_name"]);

if (!$stmt->execute()) {
    header("Location: ?error=SQL%20Error.");
    exit;
}

$conn->close();
$stmt->close();

header("Location: /apps/" . $app["url_name"] . "/install.php");
exit;
?>