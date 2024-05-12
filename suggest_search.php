<?php
include 'db_connection.php';

// Получаем поисковый запрос из GET параметра
$searchQuery = $_GET['search'] ?? '';

// Если запрос не пустой, выполняем поиск
if (!empty($searchQuery)) {
    // Подготовка SQL запроса
    $stmt = $conn->prepare("SELECT id, name, image FROM products WHERE name LIKE CONCAT('%', ?, '%')");
    $stmt->bind_param("s", $searchQuery);
    $stmt->execute();
    $result = $stmt->get_result();

    // Собираем результаты в массив
    $suggestions = [];
    while ($row = $result->fetch_assoc()) {
        $suggestions[] = $row;
    }

    // Возвращаем результаты в формате JSON
    echo json_encode($suggestions);
} else {
    // Если запрос пустой, возвращаем пустой массив
    echo json_encode([]);
}
?>
