<?php
$connect = new PDO("pgsql:host=localhost;port=5432;dbname=postgres;user=postgres;password=superpassword");
$query = "
    select name, code, socr from kladr where code like '__00000000000'
    order by name asc";

$result = $connect->query($query);
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>ФИАС</title>
</head>
<body>

<div class="container">
    <div class="row">
        <h4 class="mb-3 text-center mt-5">Поиск адреса</h4>
        <form class="row gap-5">

            <div class="d-flex justify-content-around">
                <div class="col-md-4">
                    <label for="region" class="form-label">Регион</label>
                    <select class="form-select" id="region" onchange="handleSelectRegion()">
                        <option value="">Выберите регион...</option>
                        <?php
                        foreach ($result as $row) {
                            echo '<option value="' . $row["code"] . '">' . $row["name"] . " " . $row["socr"] . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="city" class="form-label">Город</label>
                    <select class="form-select" id="city">
                        <option value="">Выберите город...</option>
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-around">

                <div class="col-md-4">
                    <label for="street" class="form-label">Улица</label>
                    <input type="text" class="form-control" id="street" placeholder="Введите улицу">
                </div>

                <div class="col-md-4">
                    <label for="house" class="form-label">Дом</label>
                    <input type="text" class="form-control" id="house" placeholder="Введите номер дома">
                </div>
            </div>
            <div class="d-flex gap-5 mt-5">
                <button
                        class="w-100 btn btn-primary btn-lg"
                        type="button"
                        onclick="handleSearch()"
                >Искать
                </button>
                <button
                        class="w-100 btn btn-warning btn-lg"
                        type="button"
                        onclick="saveRow()"
                >Сохранить строкой
                </button>
                <button
                        class="w-100 btn btn-success btn-lg"
                        type="button"
                        onclick="saveFields()"
                >Сохранить полями
                </button>
            </div>
        </form>
    </div>


    <hr class="my-4">

    <table class="table mt-5 table-striped">
        <thead>
        <tr>
            <th scope="col">№</th>
            <th scope="col">Регион</th>
            <th scope="col">Город</th>
            <th scope="col">Улица</th>
            <th scope="col">Дом</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <h5 >Найдено записей: <span id="resultCount">0</span></h5>
    <br>
    <h6 id="save"></h6>
</div>

<script src="js/index.js"></script>
</body>
</html>