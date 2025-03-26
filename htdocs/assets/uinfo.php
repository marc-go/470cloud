<?php
global $user;

require "db.php";
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

$uinfo["user"] = $row["username"];
$uinfo["mail"] = $row["mail"];
$uinfo["admin"] = $row["admin"] === 1 ? true : false;

$stmt->close();
$conn->close();
?>