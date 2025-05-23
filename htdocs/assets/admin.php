<?php
/*
* This File is created by Marc Goering (https://marc-goering.de)
* This is the Login Checker for 470Cloud.
*/
class loginManager {
    private $user;
	private $admin;

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
            $file = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/data/users/" . $row["username"] . "/sessions.json");
            if (filesize($_SERVER["DOCUMENT_ROOT"] . "/data/users/" . $row["username"] . "/sessions.json") === 0) {
                continue;
            } else {
                $file = json_decode($file, true);
            }

            if (!is_array($file)) {
                file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/data/users/" . $row["username"] . "/sessions.json", '{"array":true}');
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

        $file = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/data/users/" . $this->user . "/sessions.json");
        $file = json_decode($file, true);

        $json = [];
        $json["user_id"] = $_COOKIE["user_id"];
        $json["session_id"] = $session_id;
        $json["time"] = time() + 1800;

        $file[$_COOKIE["device_id"]] = $json;

        file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/data/users/" . $this->user . "/sessions.json", json_encode($file));

        if (setcookie("user_id", $_COOKIE["user_id"], time() + 1800, "/") &&
            setcookie("session_id", $session_id, time() + 1800, "/") &&
            setcookie("device_id", $_COOKIE["device_id"], time() + 1800, "/")) #
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
        require $_SERVER["DOCUMENT_ROOT"] . "/assets/db.php";

        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        print_r($result);
        return $row["username"];
    }
}
?>