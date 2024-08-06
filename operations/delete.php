<?php

// HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// подключим файл для соединения с базой
include_once "../config/database.php";
include_once "../users/user.php";

// получаем соединение с БД
$database = new Database();
$db = $database->getConnection();

// подготовка объекта
$user = new User($db);

// получаем id пользователя
$data = json_decode(file_get_contents("php://input"));

// установим id пользователя для удаления
$user->id = $data->id;

// удаление пользователя
if ($user->delete()) {
    // код ответа - 200 ok
    http_response_code(200);

    echo json_encode(array("message" => "Пользователь удалён"), JSON_UNESCAPED_UNICODE);
}
// если не удалось удалить
else {
    // код ответа - 503 Сервис не доступен
    http_response_code(503);

    echo json_encode(array("message" => "Не удалось удалить"));
}