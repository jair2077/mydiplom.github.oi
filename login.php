<?php
session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Измените запрос, чтобы выбрать также и id пользователя
    $stmt = $conn->prepare("SELECT id, password, adm FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($user_id, $hashed_password, $is_admin);
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            // Сохраняем в сессию необходимые данные, включая id пользователя
            $_SESSION['user_id'] = $user_id; // Важно: сохраняем id пользователя
            $_SESSION['username'] = $username;
            $_SESSION['is_admin'] = $is_admin; // Записываем статус администратора
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Неверный пароль']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Пользователь не найден']);
    }
    $stmt->close();
    $conn->close();
}
?>
