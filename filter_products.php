<?php
session_start();
include 'db_connection.php';

// Получаем выбранные фильтры из тела запроса
$data = json_decode(file_get_contents("php://input"), true);

// Подготавливаем запрос для выборки товаров по выбранным фильтрам
$query = "SELECT * FROM products WHERE ";
$placeholders = [];
$params = [];

foreach ($data as $index => $filter) {
    $placeholders[] = "filters LIKE ?";
    $params[] = "%$filter%";
}

$query .= implode(" AND ", $placeholders);

// Выполняем запрос к базе данных
$stmt = $conn->prepare($query);
$stmt->bind_param(str_repeat('s', count($data)), ...$params);
$stmt->execute();
$result = $stmt->get_result();
$products = [];

// Получаем результаты запроса и добавляем их в массив товаров
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

// Отправляем список товаров в формате JSON
header('Content-Type: application/json');
echo json_encode($products);
