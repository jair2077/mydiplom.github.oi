<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "user_management";

// Создаем подключение
$conn = new mysqli($servername, $username, $password, $database);

// Проверяем подключение
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>