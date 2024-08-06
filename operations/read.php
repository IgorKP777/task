<?php

// ����������� HTTP-���������
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// ����������� � ���� ������
include_once "../config/database.php";
include_once "../users/user.php";

// �������� ���������� � ����� ������
$database = new Database();
$db = $database->getConnection();

// �������������� ������
$user = new User($db);

// ����������� ������������
$stmt = $user->read();
$num = $stmt->rowCount();

// ��������, ������� �� ������ 0 �������
if ($num > 0) {
    // ������ �������������
    $users_arr = array();
    $users_arr["records"] = array();

    // �������� ���������� ����� �������
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // ��������� ������
        extract($row);
        $user_item = array(
            "id" => $id,
            "surname" => $surname,
            "name" => $name,
            "patronymic" => $patronymic
        );
        array_push($users_arr["records"], $user_item);
    }

    // ������������� ��� ������ - 200 OK
    http_response_code(200);

    // ������� ������ � ������ � ������� JSON
    echo json_encode($users_arr);
} else {
    // ��������� ��� ������ - 404 �� �������
    http_response_code(404);

    // �������� ������������, ��� ������ �� �������
    echo json_encode(
        array(
            "message" => "������������ �� ������"
        ), JSON_UNESCAPED_UNICODE);
}
