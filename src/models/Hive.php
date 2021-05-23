<?php
include_once(dirname(__FILE__) . '/../dbConnect.php');

class Hive
{
    public $id;
    public $name;
    public $latitude;
    public $longitude;


    /**
     * Hive constructor.
     * @param int $id
     * @param string $name
     * @param float $latitude
     * @param float $longitude
     */
    public function __construct(int $id, string $name, float $latitude, float $longitude)
    {
        $this->id = $id;
        $this->name = $name;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public static function getHive($id): Hive
    {
        global $db;
        $query = "SELECT id, name, latitude, longitude FROM hive WHERE id=$id LIMIT 1";

        $row = mysqli_fetch_row(mysqli_query($db, $query));
        return new Hive($row['id'], $row['name'], $row['latitude'], $row['longitude']);
    }

    public static function getAllHive(): array
    {
        global $db;
        $query = "SELECT id, name, latitude, longitude FROM hive ORDER BY name";

        $response = array();
        $result = mysqli_query($db, $query);
        while ($row = mysqli_fetch_array($result)) {
            $response[] = new Hive($row['id'], $row['name'], $row['latitude'], $row['longitude']);
        }
        return $response;
    }

    public static function getHiveCount(): int
    {
        global $db;
        $query = "SELECT count(id) AS count FROM hive";

        $row = mysqli_fetch_row(mysqli_query($db, $query));
        return $row[0];
    }

    public static function addHive(string $name, float $latitude, float $longitude): Hive
    {
        global $db;
        $query = "INSERT INTO hive (name, latitude, longitude) VALUES ('$name', $latitude, $longitude)";
        mysqli_query($db, $query);

        return new Hive(mysqli_insert_id($db), $name, $latitude, $longitude);
    }

    public static function editHive($id, $name, $latitude, $longitude): bool
    {
        global $db;
        $query = "UPDATE hive SET name='$name', latitude=$latitude, longitude=$longitude WHERE id=$id";
        return mysqli_query($db, $query);
    }

    public static function deleteHive($id): bool
    {
        global $db;
        $query = "DELETE FROM hive WHERE id=$id";
        return mysqli_query($db, $query);
    }

    public function getData(): array {
        global $db;
        $query = "SELECT id, date, weight, temperature, humidity FROM data LEFT JOIN hiveData ON dataId=id WHERE hiveId=$this->id";
        $result = mysqli_query($db, $query);
        $response = [];
        while ($row = mysqli_fetch_array($result)) {
            $response[] = new Data($row['id'], $row['date'], $row['weight'], $row['temperature'], $row['humidity']);
        }
        return $response;
    }

}