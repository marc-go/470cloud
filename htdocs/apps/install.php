<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script type="importmap">
        {
            "imports": {
                "@material/web/": "https://esm.run/@material/web/"
            }
        }
    </script>
    <script type="module">
        import '@material/web/all.js';
        import {styles as typescaleStyles} from '@material/web/typography/md-typescale-styles.js';

        document.adoptedStyleSheets.push(typescaleStyles.styleSheet);
    </script>
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
$path = $_SERVER["DOCUMENT_ROOT"] . "/tmp/app_store/" . $app["url_name"] . ".zip";

if (!file_put_contents($path, fopen($url, "r"))) {
    header("Location: ?error=App%20cannot%20download.");
    exit;
}

$zip = new ZipArchive();

if ($zip->open($path)) {
    $zip->extractTo($_SERVER["DOCUMENT_ROOT"] . "/apps/");
    $zip->close();
}else{
    header("Location: ?error=Zip File cannot open.");
    exit;
}

unlink($path);

require $_SERVER["DOCUMENT_ROOT"] . "/assets/db.php";

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