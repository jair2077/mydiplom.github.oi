<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Карточка товара</title>
    <link rel="stylesheet" href="styles.css"> <!-- Убедитесь, что стили подключены правильно -->
</head>
<body>
<?php
include 'db_connection.php';  // Подключение к базе данных
?>
<div class="products-container">
    <?php
    $query = "SELECT id, name, price, description, image, category FROM products";  // Запрос на выборку данных
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="product-card">';
            echo '<img src="' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['name']) . '" style="width:100%">';
            echo '<h1>' . htmlspecialchars($row['name']) . '</h1>';
            echo '<p class="price">' . number_format($row['price'], 2, '.', '') . ' руб.</p>';
            echo '<p>' . htmlspecialchars($row['description']) . '</p>';
            echo '<p><button>Добавить в корзину</button></p>';
            echo '</div>';
        }
    } else {
        echo '<p>Товары не найдены.</p>';
    }
    ?>
</div>
<?php include('footer.php'); // Подключаем подвал сайта ?>
</body>
</html>
