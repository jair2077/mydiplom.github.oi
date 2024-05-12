<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: index.php');
    exit;
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $filters = $_POST['filters'];

    // Обновление данных в базе
    $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, category = ?, description = ?, filters = ? WHERE id = ?");
    $stmt->bind_param("sdsssi", $name, $price, $category, $description, $filters, $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Товар успешно обновлен.";
    } else {
        echo "Ошибка при обновлении товара или данные не изменены.";
    }
    $stmt->close();
}

$conn->close();
header("Location: content_management.php"); // Возвращаемся обратно к управлению контентом
?>
