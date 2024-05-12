<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

$username = $_SESSION['username'];
$is_admin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1; // Проверка на админа

$stmt = $conn->prepare("SELECT name, email FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($name, $email);
$stmt->fetch();
$stmt->close();

$pageTitle = 'Личный кабинет';
include 'header.php';
?>
<link rel="stylesheet" href="styles.css">

<div class="user-profile">
    <div class="sidebar">
        <a href="#" class="active">Персональные данные</a>
        <a href="orders.php">Мои заказы</a>
        <?php if ($is_admin): ?>
            <a href="admin_panel.php">Административная панель</a>
        <?php endif; ?>
        <a href="logout.php">Выйти</a>
    </div>
    <div class="profile-content">
        <h1>Профиль пользователя</h1>
        <form action="update_profile.php" method="post">
            <label for="email">Электронная почта:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            
            <label for="name">Имя:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>

            <a href="change_password.php" class="button-link save-button">Сменить пароль</a>
            <button type="submit" class="save-button">Сохранить</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
