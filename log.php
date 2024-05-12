<?php
session_start();
// Предположим, у вас есть массив пользователей для примера.
$users = [
    ['username' => 'user1', 'password' => 'pass1'], // Логин и пароль пользователя.
    ['username' => 'user2', 'password' => 'pass2']
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    foreach ($users as $user) {
        if ($user['username'] === $username && $user['password'] === $password) {
            $_SESSION['user'] = $username; // Сохраняем имя пользователя в сессии.
            header('Location: personal_area.php'); // Перенаправляем на личный кабинет.
            exit();
        }
    }

    $error = 'Неверное имя пользователя или пароль';
    // Оставляем пользователя на странице входа с сообщением об ошибке.
}

// Если пользователь уже авторизован, перенаправляем его сразу в личный кабинет.
if (isset($_SESSION['user'])) {
    header('Location: personal_area.php');
    exit();
}
?>
