<?php
$serverName = "localhost";
$username = "root";
$password = "";
$dbName = "tallyos";

$db = new mysqli($serverName, $username, $password, $dbName);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}