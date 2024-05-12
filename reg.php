<?php
include 'db_connection.php';  // Предполагается, что это ваш скрипт подключения к БД

header('Content-Type: application/json');  // Важно указать правильный MIME-тип

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Пользователь с таким именем или email уже существует.']);
        return;
    }

    $stmt = $conn->prepare("INSERT INTO users (name, username, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $username, $email, $hashedPassword);
    if ($stmt->execute()) {
        $response = ['success' => true, 'message' => 'Регистрация успешна.'];
        error_log('Ответ сервера: ' . json_encode($response)); // Логирование ответа сервера
        echo json_encode($response);
        
    } else {
        echo json_encode(['success' => false, 'message' => 'Ошибка при добавлении пользователя: ' . $conn->error]);
    }
    $stmt->close();
    $conn->close();
}
?>
