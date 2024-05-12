<?php
session_start();
include 'db_connection.php'; // Убедитесь, что файл подключения к базе данных подключен корректно

// Проверка авторизации пользователя
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

$message = ''; // Переменная для сообщения
$redirect = ''; // Скрипт для перенаправления

// Обработка отправки формы
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password != $confirm_password) {
        $message = "Новый пароль и подтверждение не совпадают.";
    } else {
        // Получение текущего пароля из базы данных
        $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
        $stmt->bind_param("s", $_SESSION['username']);
        $stmt->execute();
        $stmt->bind_result($hashed_password);
        $stmt->fetch();
        $stmt->close();

        // Проверка текущего пароля
        if (password_verify($current_password, $hashed_password)) {
            // Обновление пароля
            $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
            $stmt->bind_param("ss", $new_hashed_password, $_SESSION['username']);
            if ($stmt->execute()) {
                $message = "Пароль успешно изменен.";
                $redirect = "<script>setTimeout(function() { window.location.href = 'personal_area.php'; }, 100);</script>";
            } else {
                $message = "Ошибка при обновлении пароля: " . $conn->error;
            }
            $stmt->close();
        } else {
            $message = "Текущий пароль неверен.";
        }
    }
    $conn->close();
}

include 'header.php';
?>

<link rel="stylesheet" href="styles.css">

<div class="content-container">
    <h1>Смена пароля</h1>
    <form action="change_password.php" method="post">
        <label for="current_password">Текущий пароль:</label>
        <input type="password" id="current_password" name="current_password" required>

        <label for="new_password">Новый пароль:</label>
        <input type="password" id="new_password" name="new_password" required>

        <label for="confirm_password">Подтвердите новый пароль:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>

        <button type="submit">Сменить пароль</button>
    </form>
    <?php if ($message): ?>
        <script>alert('<?php echo $message; ?>');</script>
    <?php endif; ?>
    <?php echo $redirect; ?>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
