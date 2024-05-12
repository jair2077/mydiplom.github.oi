<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $product_id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        echo "<script>alert('Товар успешно удален');</script>";
    } else {
        echo "<script>alert('Ошибка при удалении товара');</script>";
    }
    $stmt->close();
    header("Location: content_management.php"); // Переадресация для обновления страницы
    exit;
}
?>
