<?php

// HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// подключаем файл дл€ работы с Ѕƒ
include_once "../config/database.php";
include_once "../objects/product.php";

// получаем соединение с базой данных
$database = new Database();
$db = $database->getConnection();

// создание объекта
$user = new User($db);

// получаем id пользовател€ дл€ редактировани€
$data = json_decode(file_get_contents("php://input"));

// установим id свойства товара дл€ редактировани€
$user->id = $data->id;

// установим значени€ свойств товара
$user->surname = $data->surname;
$user->name = $data->name;
$user->patronymic = $data->patronymic;
$user->city = $data->city;

// обновление данных пользовател€
if ($user->update()) {
    // установим код ответа - 200 ok
    http_response_code(200);

    echo json_encode(
        array(
            "message" => "“овар был обновлЄн"
        ), JSON_UNESCAPED_UNICODE);
} // если не удаетс€ обновить данные
else {
    // код ответа - 503 —ервис не доступен
    http_response_code(503);

    echo json_encode(
        array(
            "message" => "Ќевозможно обновить товар"
        ), JSON_UNESCAPED_UNICODE);
}