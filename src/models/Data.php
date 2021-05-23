<?php
include_once(dirname(__FILE__) . '/../dbConnect.php');

class Data
{
    public $id;
    public $date;
    public $weight;
    public $temperature;
    public $humidity;
    public $hive;

    /**
     * Data constructor.
     * @param int $id
     * @param DateTime $date
     * @param float $weight
     * @param float $temperature
     * @param float $humidity
     * @param Hive $hive
     */
    public function __construct(int $id, DateTime $date, float $weight, float $temperature, float $humidity, Hive $hive)
    {
        $this->id = $id;
        $this->date = $date;
        $this->weight = $weight;
        $this->temperature = $temperature;
        $this->humidity = $humidity;
        $this->hive = $hive;
    }


    public static function getData($id): Data
    {
        global $db;
        $query = "SELECT data.id AS data_id, date, weight, temperature, humidity, hive.id, name, latitude, longitude FROM data LEFT JOIN hivedata ON dataId=data.id LEFT JOIN hive ON hiveId=hive.id WHERE data.id=$id LIMIT 1";

        $row = mysqli_fetch_row(mysqli_query($db, $query));
        return new Data($row['data_id'],  DateTime::createFromFormat('Y-m-d H:i:s', $row['date']), $row['weight'], $row['temperature'], $row['humidity'], new Hive($row['id'], $row['name'], $row['latitude'], $row['longitude']));
    }

    public static function getAllData(int $start = 0, $limit = 0, $searchTerm = null): array
    {
        global $db;
        $query = "SELECT data.id AS data_id, date, weight, temperature, humidity, hive.id, name, latitude, longitude FROM data LEFT JOIN hivedata ON dataId=data.id LEFT JOIN hive ON hiveId=hive.id";
        $searchTerm = trim($searchTerm);
        if ($searchTerm != '') {
            $query .= " WHERE name LIKE '%$searchTerm%'";
        }
        $query .= " ORDER BY name LIMIT $limit OFFSET $start";
        $response = array();
        $result = mysqli_query($db, $query);
        while ($row = mysqli_fetch_array($result)) {
            $response[] = new Data($row['id'], DateTime::createFromFormat('Y-m-d H:i:s', $row['date']), $row['weight'], $row['temperature'], $row['humidity'], new Hive($row['id'], $row['name'], $row['latitude'], $row['longitude']));
        }
        return $response;
    }

    public static function getDataCount(): int
    {
        global $db;
        $query = "SELECT count(id) AS count FROM data";

        $row = mysqli_fetch_row(mysqli_query($db, $query));
        return $row[0];
    }

    public static function addData(DateTime $date, float $weight, float $temperature, float $humidity, Hive $hive): Data
    {
        global $db;
        $mysqlDate = date('Y-m-d H:i:s', $date);
        $query = "INSERT INTO data (date, weight, temperature, humidity) VALUES ('$mysqlDate', $weight, $temperature, $humidity)";
        mysqli_query($db, $query);
        $dataId = mysqli_insert_id($db);
        $query = "INSERT INTO hivedata (hiveId, dataId) VALUES ($hive->id, $dataId)";
        mysqli_query($db, $query);

        return new Data($dataId, $date, $weight, $temperature, $humidity, $hive);
    }

    public static function editData($id, $date, $weight, $temperature, $humidity): bool
    {
        global $db;
        $mysqlDate = date('Y-m-d H:i:s', $date);
        $query = "UPDATE data SET date='$mysqlDate', weight=$weight, temperature=$temperature, humidity=$humidity WHERE id=$id";
        return mysqli_query($db, $query);
    }

    public static function deleteData($id): bool
    {
        global $db;
        $query = "TRANSACTION; DELETE FROM hiveData WHERE dataId=$id; DELETE FROM data WHERE id=$id; COMMIT;";
        return mysqli_query($db, $query);
    }

    public function getHive(): Hive
    {
        global $db;
        $query = "SELECT id, name, latitude, longitude FROM hivedata LEFT JOIN hive ON hiveId=id WHERE dataId=$this->id LIMIT 1";

        $row = mysqli_fetch_row(mysqli_query($db, $query));
        return new Hive($row['id'], $row['name'], $row['latitude'], $row['longitude']);

    }

}