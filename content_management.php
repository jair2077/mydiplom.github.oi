<?php
session_start();
ob_start(); // Start output buffering
include 'db_connection.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: index.php');
    exit;
}

$pageTitle = 'Управление контентом';
include 'header.php';

// Fetch categories function
function fetchCategories($conn) {
    $categories = [];
    $query = "SELECT name FROM categories";
    $result = $conn->query($query);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row['name'];
        }
    }
    return $categories;
}
$categories = fetchCategories($conn);

// Handle new category addition
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $category_name = $_POST['category_name'];
    $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
    if ($stmt) {
        $stmt->bind_param("s", $category_name);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            $message = "<p>Категория успешно добавлена.</p>";
        } else {
            $message = "<p>Ошибка при добавлении категории.</p>";
        }
        $stmt->close();
        // Send JSON response to trigger AJAX update on product_addition.php
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    } else {
        $message = "<p>Ошибка при подготовке запроса.</p>";
    }
}

// Fetch categories from content_management.php
function fetchCategoriesFromContentManagement($conn) {
    $categories = [];
    $query = "SELECT name FROM categories";
    $result = $conn->query($query);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row['name'];
        }
    }
    return $categories;
}

// Fetch filters function
function fetchFilters($conn) {
    $filters = [];
    $query = "SELECT DISTINCT filters FROM products WHERE filters IS NOT NULL AND filters != ''";
    $result = $conn->query($query);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $currentFilters = explode(',', $row['filters']);
            foreach ($currentFilters as $filter) {
                $filter = trim($filter);
                if (!empty($filter) && !in_array($filter, $filters)) {
                    $filters[] = $filter;
                }
            }
        }
    }
    return $filters;
}

// Fetch added products and return as JSON
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    // AJAX request
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
?>

<link rel="stylesheet" href="styles.css">

<div class="content-management">
    <h1>Управление контентом</h1>
    <div class="form-container">
        <h2>Добавить новый товар</h2>
        <form action="add_product.php" method="post" enctype="multipart/form-data">
            <label for="product-name">Название товара:</label>
            <input type="text" id="product-name" name="product_name" required>

            <label for="product-category">Категория товара:</label>
            <select id="product-category" name="category">
                <option value="">Выберите категорию</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category); ?>"><?= htmlspecialchars($category); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="product-price">Цена товара:</label>
            <input type="number" id="product-price" name="price" step="0.01" required>

            <label for="product-description">Описание товара:</label>
            <textarea id="product-description" name="description"></textarea>

            <label for="product-image">Изображение товара:</label>
            <input type="file" id="product-image" name="image" accept="image/*">

            <label for="product-filters">Фильтры товара:</label>
            <input type="text" id="product-filters" name="filters" placeholder="Введите фильтры через запятую">

            <button type="submit" class="admin-button">Добавить товар</button>
        </form>
    </div>

    <div class="form-container">
        <h2>Добавить новую категорию</h2>
        <form action="content_management.php" method="post">
            <input type="text" name="category_name" placeholder="Название категории" required>
            <button type="submit" name="add_category" class="admin-button">Добавить категорию</button>
        </form>
    </div>
    <div class="filters-container">
        <h2>Фильтры</h2>
        <ul>
            <?php
            // Вывод фильтров
            $filters = fetchFilters($conn);
            foreach ($filters as $filter) {
                echo "<li><input type='checkbox' name='filters[]' value='" . htmlspecialchars($filter) . "'>" . htmlspecialchars($filter) . "</li>";
            }
            ?>
        </ul>
    </div>               
    <div class="products-table">
        <h2>Список товаров</h2>
        <table>
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Цена</th>
                    <th>Описание</th>
                    <th>Категория</th>
                    <th>Фильтры</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($data)) {
                    foreach ($data as $row) {
                        echo "<tr>";
                        echo "<form action='update_product.php' method='post'>";
                        echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
                        echo "<td><input type='text' name='name' value='" . htmlspecialchars($row['name']) . "'></td>";
                        echo "<td><input type='number' name='price' step='0.01' value='" . $row['price'] . "'></td>";
                        echo "<td><input type='text' name='description' value='" . htmlspecialchars($row['description']) . "'></td>";
                        echo "<td><input type='text' name='category' value='" . htmlspecialchars($row['category']) . "'></td>";
                        echo "<td><input type='text' name='filters' value='" . htmlspecialchars($row['filters']) . "'></td>";
                        echo "<td>";
                        echo "<button type='submit' name='update' class='admin-button'>Сохранить</button>";
                        echo "<button type='submit' formaction='delete_product.php' formmethod='post' class='delete-button'>Удалить</button>";
                        echo "</td>";
                        echo "</form>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Нет товаров</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</div>

<?php include 'footer.php'; ?>
