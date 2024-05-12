<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Детали товара</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
<?php
include('db_connection.php'); // Подключение к базе данных

// Получение идентификатора товара из URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Запрос к базе данных для получения информации о товаре
$query = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    ?>
    <div class="container">
        <h1 class="product-title"><?= htmlspecialchars($row['name']) ?></h1>
        <img class="product-image" src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
        <p class="product-price"><?= number_format($row['price'], 2) ?> руб.</p>
        <div class="product-description"><?= nl2br(htmlspecialchars($row['description'])) ?></div>
        <a href="cart.php?action=add&id=<?= $row['id'] ?>" class="button">Добавить в корзину</a>
        <a class="back-link" href="index.php">Вернуться на главную</a>
    </div>
    <?php
} else {
    echo "<p class='container'>Товар не найден.</p>";
}
$stmt->close();
$conn->close();
?>
<?php include('footer.php'); ?>

<script>
function loadAddedProducts() {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Обновляем содержимое <div class="added-products">
            document.querySelector('.added-products').innerHTML = this.responseText;
        }
    };
    xhr.open("GET", "content_management.php", true);
    xhr.send();
}

// Вызываем функцию при загрузке страницы
document.addEventListener("DOMContentLoaded", function() {
    loadAddedProducts();
});
</script>

</body>
</html>
