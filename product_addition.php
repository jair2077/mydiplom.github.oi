<?php
session_start();
include 'db_connection.php';

$pageTitle = 'Товары';
include 'header.php';

// Функция для получения выбранных фильтров
function getSelectedFilters() {
    return $_GET['filters'] ?? [];
}

// Функция для проверки, есть ли у товара выбранный фильтр
function hasSelectedFilter($productFilters, $selectedFilters) {
    foreach ($selectedFilters as $filter) {
        if (in_array($filter, $productFilters)) {
            return true;
        }
    }
    return false;
}

// Измененная функция для получения уникальных фильтров для выбранной категории
function fetchUniqueFiltersByCategory($conn, $category) {
    $filters = [];
    $query = "SELECT DISTINCT filters FROM products WHERE category = ? AND filters IS NOT NULL AND filters != ''";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $result = $stmt->get_result();
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

// Получение выбранной категории из URL
$category = $_GET['category'] ?? '';

// Fetch products by category
function fetchProductsByCategory($conn, $category) {
    $query = "SELECT * FROM products WHERE category = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $result = $stmt->get_result();
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    return $products;
}

// Fetch products by category and filters
function fetchProductsByCategoryAndFilters($conn, $category, $selectedFilters) {
    $query = "SELECT * FROM products WHERE category = ?";
    // Добавление условий фильтров
    if (!empty($selectedFilters)) {
        $filterConditions = [];
        foreach ($selectedFilters as $filter) {
            $filterConditions[] = "FIND_IN_SET('$filter', filters)";
        }
        $query .= " AND (" . implode(" OR ", $filterConditions) . ")";
    }
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $result = $stmt->get_result();
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    return $products;
}

// Отображение товаров
echo "<div class='product-wrapper'>";
echo "<div class='product-list'>";
echo "<h2>$category</h2>";

// Получение выбранных фильтров
$selectedFilters = getSelectedFilters();

// Получение товаров с учетом выбранной категории и фильтров
$products = fetchProductsByCategoryAndFilters($conn, $category, $selectedFilters);

if (!empty($products)) {
    foreach ($products as $product) {
        echo '<div class="new-arrival-item">';
        echo '<a href="product_detail.php?id=' . $product['id'] . '">';
        echo '<img src="' . htmlspecialchars($product['image']) . '" alt="' . htmlspecialchars($product['name']) . '">';
        echo '<div class="item-info">';
        echo '<p>' . date("Y") . ' КОЛЛЕКЦИЯ</p>';
        echo '<p>' . htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') . '</p>';
        echo '<p>' . htmlspecialchars($product['price']) . ' руб.</p>';
        echo '</div></a>';
        echo '<a href="cart.php?action=add&id=' . $product['id'] . '" class="button">Добавить в корзину</a>';
        echo '</div>';
    }
} else {
    echo "<p>Нет товаров, удовлетворяющих выбранным категории и фильтрам.</p>";
}

echo "</div>";

// Измененный вызов функции для получения уникальных фильтров
$filters = fetchUniqueFiltersByCategory($conn, $category);

// Отображение фильтров
echo "<div class='filters-container'>";
echo "<h2>Фильтры</h2>";
echo "<form action='product_addition.php' method='get' class='filters-form'>";

echo "<input type='hidden' name='category' value='$category'>";
echo "<ul class='filter-list'>";
foreach ($filters as $filter) {
    $checked = in_array($filter, $selectedFilters) ? 'checked' : '';
    echo "<li><input type='checkbox' name='filters[]' value='" . htmlspecialchars($filter) . "' $checked>" . htmlspecialchars($filter) . "</li>";
}
echo "</ul>";
echo "<button type='submit' class='apply-filters-button'>Применить фильтры</button>";
echo "</form>";
echo "</div>";

echo "</div>";

include 'footer.php';
?>
