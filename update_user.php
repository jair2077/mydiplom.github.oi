<?php
session_start();
include 'db_connection.php';

if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1 && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
    $usernameToUpdate = $_POST['username'];
    $newEmail = $_POST['email'];
    $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $updateStmt = $conn->prepare("UPDATE users SET email = ?, password = ? WHERE username = ?");
    $updateStmt->bind_param("sss", $newEmail, $newPassword, $usernameToUpdate);
    $updateStmt->execute();

    if ($updateStmt->affected_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Не удалось обновить данные пользователя.']);
    }
    $updateStmt->close();
}
?>
