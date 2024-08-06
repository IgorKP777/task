<?php

class User
{
    private $conn;

    // свойства
    public $id;
    public $surname;
    public $name;
    public $patronymic;
    public $city;

   // конструктор для соединения с базой данных
    public function __construct($db)
    {
        $this->conn = $db;
    }

    /** чтение всех записей из таблицы user_info
    * @return bool возвращает true если запрос выполнен, иначе false
     */
    function read()
    {
        // выбираем все записи
        $query = "
            SELECT ui.id, ui.surname, ui.name, ui.patronymic, uc.name
            FROM user_info ui
            LEFT JOIN user_city uc ON p.category_id = uc.id
            ORDER BY ui.surname, ui.name, ui.patronymic";
       // подготовка запроса
        $stmt = $this->conn->prepare($query);
        // выполняем запрос
        $stmt->execute();
        return $stmt;
    }

    /** получения конкретного пользователя по ID
    * @return bool возвращает true если запрос выполнен, иначе false
     */
    function readOne()
    {
        // запрос для чтения одного пользователя
        $query = "
            SELECT ui.id, ui.surname, ui.name, ui.patronymic, uc.name city_name
            FROM user_info ui
            LEFT JOIN user_city uc ON p.category_id = uc.id
            WHERE ui.id = ':id'";

        // подготовка запроса
        $stmt = $this->conn->prepare($query);

        // привязываем id товара, который будет получен
        $stmt->bindParam(":id", $this->id);

        // выполняем запрос
        $stmt->execute();

        // получаем извлеченную строку
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // установим значения свойств объекта
        $this->id = $row["id"];
        $this->surname = $row["surname"];
        $this->name = $row["name"];
        $this->patronymic = $row["patronymic"];
        $this->city = $row["city_name"];
    }

    /** создание пользователя
    * @return bool возвращает true если запрос выполнен, иначе false
     */
    function create()
    {
        // запрос для вставки записи
        $query = "
            INSERT INTO user_info
            SET surname=':surname', name=':name', patronymic=':patronymic', city=':city'";

       // подготовка запроса
        $stmt = $this->conn->prepare($query);

        $this->surname = htmlspecialchars(strip_tags($this->surname));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->patronymic = htmlspecialchars(strip_tags($this->patronymic));
        $this->city = htmlspecialchars(strip_tags($this->city));

       // привязка значений
        $stmt->bindParam(":surname", $this->surname);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":patronymic", $this->patronymic);
        $stmt->bindParam(":city", $this->city);

        // выполняем запрос
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /** удаление пользователя
    * @return bool возвращает true если запрос выполнен, иначе false
     */
    function delete()
    {
        // запрос для удаления записи
        $query = "
            DELETE FROM user_info 
            WHERE id = ':id'";

        // подготовка запроса
        $stmt = $this->conn->prepare($query);

        // очитка
        $this->id = htmlspecialchars(strip_tags($this->id));

        // привязываем id записи для удаления
        $stmt->bindParam(":id", $this->id);

        // выполняем запрос
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /** обновления информации о пользователе
    * @return bool возвращает true если запрос выполнен, иначе false
     */
    function update()
    {
        // запрос для обновления записи
        $query = "
            UPDATE user_info
            SET surname = :surname,
                name = :name,
                patronymic = :patronymic,
                city = :city
            WHERE id = :id";

        // подготовка запроса
        $stmt = $this->conn->prepare($query);

        // очистка
        $this->surname = htmlspecialchars(strip_tags($this->surname));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->patronymic = htmlspecialchars(strip_tags($this->patronymic));
        $this->city = htmlspecialchars(strip_tags($this->city));

        // привязываем значения
        $stmt->bindParam(":surname", $this->surname);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":patronymic", $this->patronymic);
        $stmt->bindParam(":city", $this->city);

        // выполняем запрос
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /** количество пользователей
    * @return int возвращает число - количество пользователей в таблице
     */
    public function count()
    {
        $query = "
            SELECT COUNT(*) as count_user 
            FROM user_info";
        // подготовка запроса
        $stmt = $this->conn->prepare($query);
        // выполняем запрос
        $stmt->execute();

        // получаем извлеченную строку
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return intval($row["count_user"]);
    }
}
