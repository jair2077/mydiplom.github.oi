<?php
session_start(); // Вызываем session_start() в самом начале скрипта

include('header.php'); // Подключаем хедер
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Корзина</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container">
    <h1>Ваша корзина</h1>
    <?php
    require 'db_connection.php'; // Подключаемся к базе данных

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_GET['action']) && isset($_GET['id'])) {
        $productId = intval($_GET['id']);
        switch ($_GET['action']) {
            case 'add':
                if (!array_key_exists($productId, $_SESSION['cart'])) {
                    $_SESSION['cart'][$productId] = 1;
                } else {
                    $_SESSION['cart'][$productId]++;
                }
                break;
            case 'remove':
                if (array_key_exists($productId, $_SESSION['cart'])) {
                    if ($_SESSION['cart'][$productId] > 1) {
                        $_SESSION['cart'][$productId]--;
                    } else {
                        unset($_SESSION['cart'][$productId]);
                    }
                }
                break;
            case 'delete':
                if (array_key_exists($productId, $_SESSION['cart'])) {
                    unset($_SESSION['cart'][$productId]);
                }
                break;
        }
    }

    $totalPrice = 0;
    if (!empty($_SESSION['cart'])) {
        echo "<table>";
        echo "<tr><th>Продукт</th><th>Количество</th><th>Цена</th><th>Изменить количество</th><th>Удалить</th></tr>";
        foreach ($_SESSION['cart'] as $id => $quantity) {
            $stmt = $conn->prepare("SELECT name, image, price FROM products WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $itemPrice = $row['price'] * $quantity;
                $totalPrice += $itemPrice;
                echo "<tr>";
                echo "<td><img src='" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "' style='width:50px; height:auto;'> " . htmlspecialchars($row['name']) . "</td>";
                echo "<td>$quantity</td>";
                echo "<td>" . number_format($itemPrice, 2) . " руб.</td>";
                echo "<td><a href='cart.php?action=add&id=$id'>+</a> <a href='cart.php?action=remove&id=$id'>-</a></td>";
                echo "<td><a href='cart.php?action=delete&id=$id'>Удалить</a></td>";
                echo "</tr>";
            }
            $stmt->close();
        }
        echo "<tr><td colspan='5' style='text-align:right;'>Общая стоимость: " . number_format($totalPrice, 2) . " руб.</td></tr>";
        echo "</table>";
        echo "<a href='checkout.php' class='button'>Оформить заказ</a>";
    } else {
        echo "<p>Ваша корзина пуста.</p>";
    }
    ?>
    <a href="index.php">Продолжить покупки</a>
</div>

<?php include('footer.php'); // Подключаем футер ?>
</body>
</html>
