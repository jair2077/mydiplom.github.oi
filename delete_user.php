<?php
session_start();
include 'db_connection.php';

if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1 && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'])) {
    $usernameToDelete = $_POST['username'];
    $deleteStmt = $conn->prepare("DELETE FROM users WHERE username = ?");
    $deleteStmt->bind_param("s", $usernameToDelete);
    $deleteStmt->execute();

    if ($deleteStmt->affected_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Не удалось удалить пользователя.']);
    }
    $deleteStmt->close();
}
?>
