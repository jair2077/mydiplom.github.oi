<?php
session_start();
include 'db_connection.php';

// Проверяем, есть ли у пользователя права администратора
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: index.php');
    exit;
}

$pageTitle = 'Управление заказами';
include 'header.php';

// Логика для получения списка заказов
// Здесь нужно добавить код для получения данных из базы данных
?>

<div class="admin-orders">
    <h1>Управление заказами</h1>
    <table>
        <thead>
            <tr>
                <th>Номер заказа</th>
                <th>Имя клиента</th>
                <th>Сумма заказа</th>
                <th>Дата заказа</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <!-- Здесь будет код PHP для вывода каждого заказа -->
            <!-- Пример вывода одного заказа (замените данными из БД) -->
            <?php
            // Пример массива данных заказов
            $orders = [
                ['order_id' => 1, 'customer_name' => 'Иван Иванов', 'amount' => '1500 руб.', 'order_date' => '2021-09-01', 'status' => 'Обработан']
            ];
            foreach ($orders as $order) {
                echo "<tr>
                        <td>{$order['order_id']}</td>
                        <td>{$order['customer_name']}</td>
                        <td>{$order['amount']}</td>
                        <td>{$order['order_date']}</td>
                        <td>{$order['status']}</td>
                        <td>
                            <button>Изменить статус</button>
                        </td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
