<?php
$connect = new PDO("pgsql:host=database;port=5432;dbname=postgres;user=postgres;password=superpassword;");
$mariadb = new PDO('mysql:host=mariadb;dbname=kladr', 'root', 'rootpassword');
$action = $_POST['action'];
header('Content-Type: text/html; charset=utf-8');

if ($action === "search") {
    $regionCode = $_POST['region'];
    $cityCode = $_POST['code'];
    $street = $_POST['street'];
    $house = $_POST['house'];

    $streets = getStreetCodes($cityCode, $street, $connect);
    $streetResult = $streets[0]['name'] . " " . $streets[0]['socr'];

    $city = getCityByCode($cityCode, $connect);
    $region = getRegionByCode($regionCode, $connect);
    $data = array();
    $result = findHouseOnStreet($streets, $house, $connect);
    if ($result === true) {
        $data[] = (object)[
            'region' => $region,
            'city' => $city,
            'street' => $streetResult,
            'house' => $house
        ];
    }
    print json_encode($data, JSON_UNESCAPED_UNICODE);

} else if ($action === "regionSelected") {
    $regionCode = $_POST['code'];
    $data = prepareCityList($regionCode, $connect);
    print json_encode($data);
} else if ($action === "saveRow") {
    $rowsStr = $_POST['rows'];

    $rows = json_decode($rowsStr);

    try {
        foreach ($rows as $row) {
            $stmt = $mariadb->prepare("INSERT INTO rowaddress (address) VALUES(?)");
            $stmt->execute([$row]);
        }

        print json_encode(["status" => "success"]);
    } catch (Exception $e) {
        print json_encode(["status" => "failed"]);
    }
} else if ($action === "saveFields") {
    $rowsStr = $_POST['rows'];

    $rows = json_decode($rowsStr);

    try {
        foreach ($rows as $row) {
            $stmt = $mariadb->prepare("INSERT INTO kladr (region, city, street, house) VALUES(?, ?, ?, ?)");
            $stmt->execute([$row->{'region'}, $row->{'city'}, $row->{'street'}, $row->{'house'}]);
        }
        print json_encode(["status" => "success"]);
    } catch (Exception $e) {
        print json_encode(["status" => "failed"]);
    }
}

function getCityByCode($c, $conn): string
{
    $query = "select name, socr from kladr where code like :code";

    $prepared = $conn->prepare($query);
    $prepared->execute(['code' => $c]);
    $result = $prepared->fetchAll();
    return $result[0]['name'] . " " . $result[0]['socr'];
}

function getRegionByCode($c, $conn): string
{
    $query = "select name, socr from kladr where code like :code";

    $prepared = $conn->prepare($query);
    $prepared->execute(['code' => $c]);
    $result = $prepared->fetchAll();
    return $result[0]['name'] . " " . $result[0]['socr'];
}

function prepareCityList($r, $conn): array
{
    $code = substr($r, 0, 2) . "%";

    $query = "select name, code, socr from kladr where code like :code and socr='г' order by name asc";

    $prepared = $conn->prepare($query);
    $prepared->execute(['code' => $code]);
    $result = $prepared->fetchAll();

    $data = array();

    foreach ($result as $row) {
        $data[] = (object)[
            'code' => $row['code'],
            'name' => $row['name'],
            'socr' => $row['socr'],
        ];
    }
    return $data;
}

function getStreetCodes($cityCode, $streetName, $conn): array
{
    $code = substr($cityCode, 0, 8) . "%";

    $query = "select name, code, socr from street where code like :code and name ilike :street";

    $prepared = $conn->prepare($query);
    $prepared->execute(['code' => $code, 'street' => $streetName]);
    return $prepared->fetchAll();
}

function findHouseOnStreet($streetArray, $house, $conn): bool
{
    foreach ($streetArray as $street) {
        $streetCode = $street['code'];
        $code = substr($streetCode, 0, 15) . "%";
        $query = "select name from doma where code like :code";
        $prepared = $conn->prepare($query);
        $prepared->execute(['code' => $code]);
        $houseArray = $prepared->fetchAll();
        foreach ($houseArray as $houseStr) {
            if (str_contains($houseStr['name'], $house)) {
                return true;
            }
        }
    }

    return false;
}
