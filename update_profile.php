<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $name = $_POST['name'];
    $username = $_SESSION['username'];

    $stmt = $conn->prepare("UPDATE users SET email = ?, name = ? WHERE username = ?");
    $stmt->bind_param("sss", $email, $name, $username);
    if ($stmt->execute()) {
        echo "Данные успешно обновлены.";
    } else {
        echo "Ошибка обновления данных: " . $conn->error;
    }
    $stmt->close();
    $conn->close();
}
?>
