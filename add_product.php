<?php
session_start(); // Вызываем session_start() в самом начале скрипта
include 'db_connection.php'; // Подключаем файл для работы с базой данных

// Проверяем, авторизован ли пользователь как администратор
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: index.php'); // Перенаправляем неавторизованных пользователей на главную страницу
    exit;
}

// Проверяем, был ли отправлен POST-запрос на добавление товара
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Проверяем, чтобы все необходимые поля были заполнены и файл изображения был загружен успешно
    if (!empty($_POST['product_name']) && !empty($_POST['price']) && !empty($_POST['category']) && isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Получаем данные из формы
        $productName = $_POST['product_name'];
        $price = (float) $_POST['price'];
        $description = $_POST['description'];
        $category = $_POST['category'];
        $filters = $_POST['filters'];

        // Подготавливаем путь для сохранения изображения
        $targetDirectory = "uploads/";
        $targetFile = $targetDirectory . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Проверяем, является ли загруженный файл изображением
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            // Проверяем, существует ли файл с таким именем
            if (file_exists($targetFile)) {
                echo "Файл с таким именем уже существует.";
            } elseif ($_FILES["image"]["size"] > 5000000) { // Проверяем размер файла
                echo "Файл слишком большой.";
            } elseif (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) { // Проверяем формат файла
                echo "Допускаются только файлы с расширениями JPG, JPEG, PNG и GIF.";
            } else {
                // Перемещаем загруженный файл изображения в указанную директорию
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                    // Формируем запрос для добавления товара в базу данных
                    $sql = "INSERT INTO products (name, price, description, category, image, filters) VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sdssss", $productName, $price, $description, $category, $targetFile, $filters);
                    $stmt->execute();
                    
                    // Проверяем, был ли добавлен товар успешно
                    if ($stmt->affected_rows > 0) {
                        echo "Новый товар успешно добавлен.";
                    } else {
                        echo "Ошибка при добавлении товара.";
                    }
                    $stmt->close();
                } else {
                    echo "Произошла ошибка при загрузке файла.";
                }
            }
        } else {
            echo "Файл не является изображением.";
        }
    } else {
        echo "Необходимо заполнить все поля.";
    }
} else {
    echo "Неверный запрос.";
}

// Закрываем соединение с базой данных
$conn->close();
?>
