<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Мои заказы</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include('header.php'); // Включаем header ?>

<?php
function translateStatus($status) {
    $translations = [
        'Pending' => 'Ожидает',
        'Completed' => 'Завершён',
        'Cancelled' => 'Отменён'
    ];

    return $translations[$status] ?? 'Неизвестный статус';
}
?>

<div class="container">
    <h1>Мои заказы</h1>
    <?php
    require 'db_connection.php';

    // Проверяем, была ли уже начата сессия
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // Предполагаем, что user_id хранится в сессии после входа в систему
    $userId = $_SESSION['user_id'] ?? 0; // Замените 0 на редирект или обработку для неавторизованных пользователей

    $query = "SELECT id, total, status, created_at FROM orders WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>ID заказа</th><th>Дата</th><th>Сумма</th><th>Статус</th></tr>";
        while ($row = $result->fetch_assoc()) {
            $translatedStatus = translateStatus($row['status']);
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
            echo "<td>" . htmlspecialchars($row['total']) . " руб.</td>";
            echo "<td>" . htmlspecialchars($translatedStatus) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>У вас пока нет заказов.</p>";
    }
    ?>
</div>

<?php include('footer.php'); // Включаем footer ?>
</body>
</html>
