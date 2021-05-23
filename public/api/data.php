<?php
include_once('../../src/dbConnect.php');
include_once('../../src/models/Hive.php');
include_once('../../src/models/Data.php');

$request_method = $_SERVER["REQUEST_METHOD"];
switch ($request_method) {
    case 'GET':
        if (!empty($_GET["id"])) {
            $id = intval($_GET["id"]);
            getData($id);
        } else {
            getData();
        }
        break;
    case 'POST':
        addData();
        break;
    case 'PUT':
        editData();
        break;
    case 'DELETE':
        deleteData();
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}

function getData(int $id = 0)
{
    $start = intval($_GET["start"]);
    $limit = intval($_GET["length"]);
    $searchTerm = trim($_GET["search"] ? $_GET["search"]["value"] : null);
    $count = 0;
    // TODO: Implement Server side sorting
    if ($id == 0) {
        //$response = Data::getAllData($start, $length, $searchTerm);
        global $db;

        // Constructing the query
        $query = "SELECT data.id AS data_id, date, weight, temperature, humidity, hive.id, hive.name, hive.latitude, hive.longitude FROM data LEFT JOIN hivedata ON dataId=data.id LEFT JOIN hive ON hiveId=hive.id";
        $countQuery = "SELECT count(*) FROM data LEFT JOIN hivedata ON dataId=data.id LEFT JOIN hive ON hiveId=hive.id";
        if ($searchTerm != '') {
            $query .= " WHERE name LIKE ?";
            $countQuery .= " WHERE name LIKE ?";
        }
        foreach ($_GET["order"] ?? [] as $order) {
            $columnId = $order["column"];
            $columnName = $_GET["columns"][$columnId]["data"];

            // mysqli does not offer column name sanitization, we must do it ourselves
            $validColumnName = in_array($columnName, ["data_id", "date", "weight", "temperature", "humidity", "hive.id", "hive.name", "hive.latitude", "hive.longitude"]);
            $dir = $order["dir"] == 'desc' ? 'desc' : 'asc';
            if ($validColumnName) {
                $query .= " ORDER BY $columnName $dir";
                // No need to order the count query
            }
        }
        $query .= " LIMIT $limit OFFSET $start";
        $stmt = $db->prepare($query);
        $countStmt = $db->prepare($countQuery);
        if ($searchTerm != '') {
            $searchTerm = '%' . $searchTerm . '%';
            $stmt->bind_param('s', $searchTerm);
            $countStmt->bind_param('s', $searchTerm);
        }
        // Query constructed

        // Fetching data
        $countStmt->execute();
        $count = $countStmt->get_result()->fetch_row()[0];
        $stmt->execute();
        $result = $stmt->get_result();

        // Processing results
        $response = [];
        while ($row = $result->fetch_array()) {
            $response[] = new Data($row['id'], DateTime::createFromFormat('Y-m-d H:i:s', $row['date']), $row['weight'], $row['temperature'], $row['humidity'], new Hive($row['id'], $row['name'], $row['latitude'], $row['longitude']));
        }
    } else {
        $response = Data::getData($id);
    }
    //header('Content-Type: application/json');
    echo json_encode([
        "recordsTotal" => Data::getDataCount(),
        "recordsFiltered" => $count,
        "draw" => intval($_GET["draw"]),
        "data" => $response
    ]);
}

function addData()
{
    $hiveId = $_POST["hiveId"];
    $date = $_POST["date"];
    $weight = $_POST["weight"];
    $temperature = $_POST["temperature"];
    $humidity = $_POST["humidity"];

    echo json_encode(Data::addData($date, (float)$weight, (float)$temperature, $humidity, Hive::getHive($hiveId)));
}

function editData()
{
    $_PUT = json_decode(file_get_contents('php://input'), true);
    $id = $_PUT["id"];
    $name = $_PUT["name"];
    $latitude = $_PUT["latitude"];
    $longitude = $_PUT["longitude"];

    echo Hive::editHive($id, $name, $latitude, $longitude) ? 'true' : 'false';
}

function deleteData()
{
    $_DELETE = json_decode(file_get_contents('php://input'), true);
    $id = $_DELETE["id"];

    echo Hive::deleteHive($id) ? 'true' : 'false';
}

