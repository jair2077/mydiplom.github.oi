<?php
session_start();
include 'db_connection.php';

// Проверяем, является ли пользователь администратором
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: index.php'); // Перенаправление неадминистраторов на главную страницу
    exit;
}

$pageTitle = 'Административная панель';
include 'header.php';
?>

<link rel="stylesheet" href="styles.css">

<div class="admin-panel">
    <h1>Административная панель</h1>
    <div class="admin-sections">
        <section class="admin-section">
            <h2>Управление пользователями</h2>
            <p>Здесь можно добавлять, удалять и изменять информацию пользователей.</p>
            <a href="manage_users.php" class="admin-link">Управление пользователями</a>
        </section>

        <section class="admin-section">
            <h2>Статистика заказов</h2>
            <p>Просмотр статистики по заказам, анализ продаж.</p>
            <a href="order_stats.php" class="admin-link">Статистика заказов</a>
        </section>

        <section class="admin-section">
            <h2>Модерация контента</h2>
            <p>Управление каталогом товаров, добавление новых товаров и категорий.</p>
            <a href="content_management.php" class="admin-link">Модерация контента</a>
        </section>
    </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
