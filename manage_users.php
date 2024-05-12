<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: index.php');
    exit;
}

$pageTitle = 'Управление пользователями';
include 'header.php';

// Получение списка пользователей
$query = "SELECT username, email FROM users";
$result = $conn->query($query);
?>

<div class="admin-users">
    <h1>Управление пользователями</h1>
    <table>
        <thead>
            <tr>
                <th>Имя пользователя</th>
                <th>Email</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td>
                        <button class="admin-button delete" data-username="<?php echo htmlspecialchars($row['username']); ?>">Удалить</button>
                        <button class="admin-button edit" data-username="<?php echo htmlspecialchars($row['username']); ?>" data-email="<?php echo htmlspecialchars($row['email']); ?>">Редактировать</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('Вы уверены, что хотите удалить пользователя?')) {
                const username = this.dataset.username;
                fetch('delete_user.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: 'username=' + encodeURIComponent(username)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Ошибка при удалении пользователя: ' + data.message);
                    }
                });
            }
        });
    });

    document.querySelectorAll('.edit').forEach(button => {
        button.addEventListener('click', function() {
            const username = this.dataset.username;
            const email = this.dataset.email;
            const newEmail = prompt("Введите новый email", email);
            const newPassword = prompt("Введите новый пароль (оставьте пустым, если не хотите менять)");

            if (newEmail !== null && newPassword !== null) {
                fetch('update_user.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: `username=${encodeURIComponent(username)}&email=${encodeURIComponent(newEmail)}&password=${encodeURIComponent(newPassword)}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Ошибка обновления пользователя: ' + data.message);
                    }
                });
            }
        });
    });
});
</script>

<?php include 'footer.php'; ?>
</body>
</html>
