<?php
include_once('../../src/dbConnect.php');
include_once('../../src/models/Hive.php');

$request_method = $_SERVER["REQUEST_METHOD"];
switch ($request_method) {
    case 'GET':
        if (!empty($_GET["id"])) {
            $id = intval($_GET["id"]);
            getHive($id);
        } else {
            getHive();
        }
        break;
    case 'POST':
        addHive();
        break;
    case 'PUT':
        editHive();
        break;
    case 'DELETE':
        deleteHive();
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}

function getHive(int $id = 0)
{
    if ($id == 0) {
        $response = Hive::getAllHive();
    } else {
        $response = Hive::getHive($id);
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}

function addHive()
{
    $name = $_POST["name"];
    $latitude = $_POST["latitude"];
    $longitude = $_POST["longitude"];

    echo json_encode(Hive::addHive($name, (float)$latitude, (float)$longitude));
}

function editHive()
{
    $_PUT = json_decode(file_get_contents('php://input'), true);
    $id = $_PUT["id"];
    $name = $_PUT["name"];
    $latitude = $_PUT["latitude"];
    $longitude = $_PUT["longitude"];

    echo Hive::editHive($id, $name, $latitude, $longitude) ? 'true' : 'false';
}

function deleteHive()
{
    $_DELETE = json_decode(file_get_contents('php://input'), true);
    $id = $_DELETE["id"];

    echo Hive::deleteHive($id) ? 'true' : 'false';
}

