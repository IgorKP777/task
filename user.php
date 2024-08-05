<?php

class User
{
    private $conn;

    // ��������
    public $id;
    public $surname;
    public $name;
    public $patronymic;
    public $city;

    // ����������� ��� ���������� � ����� ������
    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * ����� ��� ������ ���� ������� �� ������� user_info
     */
    function read()
    {
        // �������� ��� ������
        $query = "
            SELECT ui.id, ui.surname, ui.name, ui.patronymic, uc.name
            FROM user_info ui
            LEFT JOIN user_city uc ON p.category_id = uc.id
            ORDER BY ui.surname, ui.name, ui.patronymic";
        // ���������� �������
        $stmt = $this->conn->prepare($query);
        // ��������� ������
        $stmt->execute();
        return $stmt;
    }

    /**
     * ����� ��� ��������� ����������� ������������ �� ID
     */
    function readOne()
    {
        // ������ ��� ������ ������ ������������
        $query = "
            SELECT ui.id, ui.surname, ui.name, ui.patronymic, uc.name city_name
            FROM user_info ui
            LEFT JOIN user_city uc ON p.category_id = uc.id
            WHERE ui.id = ':id'";

        // ���������� �������
        $stmt = $this->conn->prepare($query);

        // ����������� id ������, ������� ����� �������
        $stmt->bindParam(":id", $this->id);

        // ��������� ������
        $stmt->execute();

        // �������� ����������� ������
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // ��������� �������� ������� �������
        $this->id = $row["id"];
        $this->surname = $row["surname"];
        $this->name = $row["name"];
        $this->patronymic = $row["patronymic"];
        $this->city = $row["city_name"];
    }

    /**
     * ����� ��� �������� ������������
     */
    function create()
    {
        // ������ ��� ������� ������
        $query = "
            INSERT INTO user_info
            SET surname=':surname', name=':name', patronymic=':patronymic', city=':city'";

        // ���������� �������
        $stmt = $this->conn->prepare($query);

        $this->surname = htmlspecialchars(strip_tags($this->surname));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->patronymic = htmlspecialchars(strip_tags($this->patronymic));
        $this->city = htmlspecialchars(strip_tags($this->city));

        // �������� ��������
        $stmt->bindParam(":surname", $this->surname);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":patronymic", $this->patronymic);
        $stmt->bindParam(":city", $this->city);

        // ��������� ������
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * �������� ������������
     */
    function delete()
    {
        // ������ ��� �������� ������ (������)
        $query = "
            DELETE FROM user_info 
            WHERE id = ':id'";

        // ���������� �������
        $stmt = $this->conn->prepare($query);

        // �������
        $this->id = htmlspecialchars(strip_tags($this->id));

        // ����������� id ������ ��� ��������
        $stmt->bindParam(":id", $this->id);

        // ��������� ������
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * ���������� ���������� � ������������
     */
    function update()
    {
        // ������ ��� ���������� ������
        $query = "
            UPDATE user_info
            SET surname = :surname,
                name = :name,
                patronymic = :patronymic,
                city = :city
            WHERE id = :id";

        // ���������� �������
        $stmt = $this->conn->prepare($query);

        // �������
        $this->surname = htmlspecialchars(strip_tags($this->surname));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->patronymic = htmlspecialchars(strip_tags($this->patronymic));
        $this->city = htmlspecialchars(strip_tags($this->city));

        // ����������� ��������
        $stmt->bindParam(":surname", $this->surname);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":patronymic", $this->patronymic);
        $stmt->bindParam(":city", $this->city);

        // ��������� ������
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * ����� ���������� ���������� �������������
     */
    public function count()
    {
        $query = "
            SELECT COUNT(*) as count_user 
            FROM user_info";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return intval($row["count_user"]);
    }
}