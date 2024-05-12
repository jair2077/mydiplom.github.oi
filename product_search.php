<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'db_connection.php';

// Получаем поисковый запрос из URL
$searchQuery = $_GET['search'] ?? '';

// Проверяем, чтобы поисковый запрос не был пустым и имел длину не менее 3 символов
if (!empty($searchQuery) && mb_strlen($searchQuery) >= 3) {
    // Здесь может быть ваш код для поиска товаров в базе данных
    // Например:
    // $searchResults = searchProducts($searchQuery);

    // Затем отобразите результаты поиска на этой же странице
} elseif (!empty($searchQuery) && mb_strlen($searchQuery) < 3) {
    // Если поисковый запрос слишком короткий, перенаправляем пользователя на главную страницу
    header("Location: index.php");
    exit;
}

// Записываем в сессию состояние пользователя (авторизован или нет)
if (isset($_SESSION['username'])) {
    echo "<script>sessionStorage.setItem('logged_in', 'true');</script>";
} else {
    echo "<script>sessionStorage.removeItem('logged_in');</script>";
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title><?php echo $pageTitle; ?></title>
</head>
<body>
<!-- Остальной код страницы product_search.php -->
