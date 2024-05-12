<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require 'db_connection.php';

if (count($_SESSION['cart']) > 0) {
    $userId = $_SESSION['user_id'] ?? 0; // Предполагается, что пользователь уже залогинен и его id сохранён в сессии

    // Считаем общую стоимость заказа
    $total = 0;
    foreach ($_SESSION['cart'] as $id => $quantity) {
        $stmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($product = $result->fetch_assoc()) {
            $total += $product['price'] * $quantity;
        }
        $stmt->close();
    }

    // Вставка данных о заказе в базу данных
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total, status, created_at) VALUES (?, ?, 'Обрабатывается', NOW())");
    $stmt->bind_param("id", $userId, $total);
    $stmt->execute();
    $orderId = $stmt->insert_id;
    $stmt->close();

    // Вставка деталей заказа
    foreach ($_SESSION['cart'] as $id => $quantity) {
        $stmt = $conn->prepare("INSERT INTO order_details (order_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $orderId, $id, $quantity);
        $stmt->execute();
        $stmt->close();
    }

    // Очистка корзины
    $_SESSION['cart'] = [];

    // Перенаправление на страницу моих заказов или информацию о заказе
    header("Location: orders.php");
    exit;
} else {
    header("Location: cart.php?error=empty_cart");
    exit;
}
?>
