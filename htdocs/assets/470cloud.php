<?php
class loginManager {
    private $root;

    private $user;
	private $admin;

    public function __construct() {
        $root = $_SERVER["DOCUMENT_ROOT"];

        if (substr($root, -1) !== "/") {
            $root = $root . "/";
        }

        $this->root = $root;
    }

    public function checkLogin() {
        if (!isset($_COOKIE["user_id"]) || !isset($_COOKIE["session_id"]) || !isset($_COOKIE["device_id"])) {
            return false;
        }
        require "db.php";
        $sql = "SELECT * FROM users";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $file = file_get_contents($this->root . "data/users/" . $row["username"] . "/sessions.json");
            if (filesize($this->root . "data/users/" . $row["username"] . "/sessions.json") === 0) {
                continue;
            } else {
                $file = json_decode($file, true);
            }

            if (!is_array($file)) {
                file_put_contents($this->root . "/data/users/" . $row["username"] . "/sessions.json", '{"array":true}');
                continue;
            }

            foreach ($file as $device_id => $json) {
				if ($device_id == "array") {
					continue;
				}
				
                if ($json["user_id"] === $_COOKIE["user_id"] && $json["session_id"] === $_COOKIE["session_id"]) {
                    $this->user = $row["username"];
					$this->admin = $row["admin"];
                    return true;
                }
            }
        }
        return false;
    }

    public function createNewSession() {
        $session_id = hash("sha256", rand(0, 99999999999));

        $file = file_get_contents($this->root . "/data/users/" . $this->user . "/sessions.json");
        $file = json_decode($file, true);

        $json = [];
        $json["user_id"] = $_COOKIE["user_id"];
        $json["session_id"] = $session_id;
        $json["time"] = time() + 1800;

        $file[$_COOKIE["device_id"]] = $json;

        file_put_contents($this->root . "/data/users/" . $this->user . "/sessions.json", json_encode($file));

        if (setcookie("user_id", $_COOKIE["user_id"], time() + 1800, "/") &&
            setcookie("session_id", $session_id, time() + 1800, "/") &&
            setcookie("device_id", $_COOKIE["device_id"], time() + 1800, "/"))
		{
			return true;
        } else {
			return false;
        }
    }

    public function getUserName() {
        return $this->user;
    }
	
	public function getAdmin() {
		if ($this->admin == 1) {
			return true;
		}else{
			return false;
		}
	}

    public function gunfI($id) {
        require $this->root . "/assets/db.php";

        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row && isset($row["username"])) {
            return $row["username"];
        } else {
            return null;
        }
    }
}

class design {
    public function showMenue() {
        $menue = '<md-tabs>
        <md-primary-tab id="home" active>Home</md-primary-tab>
        <md-primary-tab id="files">Files</md-primary-tab>
        <md-primary-tab id="reminders">ToDo</md-primary-tab>
        <md-primary-tab id="settings">Settings</md-primary-tab>';

        $admin = $session->getAdmin();

        if ($admin) {
            $menue .= '<md-primary-tab id="store">App Store</md-primray-tab>';
        }

        $menue .= '</md-tabs>';
        echo $menue;
    }

    public function addMD() {
        echo '
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
        <script type="importmap">
            {
                "imports": {
                    "@material/web/": "https://esm.run/@material/web/"
                }
            }
        </script>
        <script type="module">
            import "@material/web/all.js";
            import {styles as typescaleStyles} from "@material/web/typography/md-typescale-styles.js";

            document.adoptedStyleSheets.push(typescaleStyles.styleSheet);
        </script>
        <style>
            @import url("https://fonts.googleapis.com/css2?family=Open%20Sans:wght@400;500;700&display=swap");

            :root{
                --md-sys-color-primary:rgb(46, 131, 236);
                --md-ref-typeface-brand: "roboto", sans-serif;
                --md-ref-typeface-plain: system-ui;
            }
        </style>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
        <style>
            md-icon {
                font-family: "Material Symbols Outlined";
                font-size: 24px;
                font-variation-settings: "FILL" 0, "wght" 400, "GRAD" 0, "opsz" 24;
            }
        </style>
        <script src="/assets/menue.js"></script>
        <script>
            fetch("/apps/home")
            fetch("/apps/files")
            fetch("/apps/reminders")
            fetch("/apps/settings")
        </script>
        <script src="https://cdn.jsdelivr.net/npm/eruda"></script>
        <script>eruda.init();</script>
        ';
    }
}

function startDB() {
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $dbname = "470Cloud";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

$file = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/package.json");
$file = json_decode($file, true);
$root = $file["root"];
?>