<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Статистика заказов</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include('header.php'); // Включаем header для админ панели ?>
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
    <h1>Статистика заказов</h1>
    <?php
    require 'db_connection.php';

    // Подсчёт общего количества заказов
    $result = $conn->query("SELECT COUNT(*) AS total_orders FROM orders");
    $total_orders = $result ? $result->fetch_assoc()['total_orders'] : 0;

    // Подсчёт общей суммы по всем заказам
    $result = $conn->query("SELECT SUM(total) AS total_revenue FROM orders");
    $total_revenue = $result ? $result->fetch_assoc()['total_revenue'] : 0;

    // Подсчёт среднего чека
    $average_order_value = $total_orders > 0 ? $total_revenue / $total_orders : 0;

    echo "<p>Общее количество заказов: $total_orders</p>";
    echo "<p>Общая сумма заказов: " . number_format($total_revenue, 2) . " руб.</p>";
    echo "<p>Средний чек: " . number_format($average_order_value, 2) . " руб.</p>";

    // Отображение заказов по статусам и пользователям
    echo "<h2>Заказы по статусам и пользователям:</h2>";
    $query = "SELECT orders.id, orders.total, orders.status, IFNULL(users.username, 'Unknown') AS username 
          FROM orders 
          LEFT JOIN users ON orders.user_id = users.id 
          ORDER BY IFNULL(users.username, 'Unknown'), orders.status";

    $result = $conn->query($query);
    echo "<table>";
    echo "<tr><th>Пользователь</th><th>Заказ ID</th><th>Сумма</th><th>Статус</th><th>Изменить статус</th></tr>";
    while ($row = $result->fetch_assoc()) {
        $translatedStatus = translateStatus($row['status']);
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . number_format($row['total'], 2) . " руб.</td>";
        echo "<td>" . htmlspecialchars($translatedStatus) . "</td>"; // Используем переведённый статус
        echo "<td>
            <button class='status-button' data-id='" . $row['id'] . "' data-status='Pending' " . ($row['status'] === 'Pending' ? 'disabled' : '') . ">Ожидает</button>
            <button class='status-button' data-id='" . $row['id'] . "' data-status='Completed' " . ($row['status'] === 'Completed' ? 'disabled' : '') . ">Завершён</button>
            <button class='status-button' data-id='" . $row['id'] . "' data-status='Cancelled' " . ($row['status'] === 'Cancelled' ? 'disabled' : '') . ">Отменён</button>
        </td>";
        echo "</tr>";
    }
    echo "</table>";
    ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.status-button');
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.getAttribute('data-id');
            const newStatus = this.getAttribute('data-status');

            const formData = new FormData();
            formData.append('order_id', orderId);
            formData.append('status', newStatus);

            fetch('update_status.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload(); // Перезагрузка страницы после успешного обновления
                } else {
                    alert('Ошибка обновления статуса: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Ошибка:', error);
            });
        });
    });
});
</script>

<?php include('footer.php'); // Включаем footer ?>
</body>
</html>
