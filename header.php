<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'db_connection.php';

// Проверка, если поиск был выполнен
if (isset($_GET['search'])) {
    // Получаем поисковый запрос из URL
    $searchQuery = $_GET['search'];

    // Проверяем, чтобы поисковый запрос не был пустым и имел длину не менее 3 символов
    if (!empty($searchQuery) && mb_strlen($searchQuery) >= 3) {
        // Обработка поискового запроса и перенаправление пользователя на страницу категории "Найденные товары"
        header("Location: product_addition.php?category=Найденные товары&search=$searchQuery");
        exit;
    }
}

// Записываем в сессию состояние пользователя (авторизован или нет)
if (isset($_SESSION['username'])) {
    echo "<script>sessionStorage.setItem('logged_in', 'true');</script>";
} else {
    echo "<script>sessionStorage.removeItem('logged_in');</script>";
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title><?php echo $pageTitle; ?></title>
</head>
<body>
<header>
    <div class="header-top">
        <div class="header-controls">
            <!-- Кнопка для скрытия и показа хедера -->
            <button id="toggleHeaderButton" class="toggle-header-button">Скрыть хедер</button>
            <div class="input-wrapper">
                <button type="button" class="icon">
                    <svg width="25px" height="25px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11.5 21C16.7467 21 21 16.7467 21 11.5C21 6.25329 16.7467 2 11.5 2C6.25329 2 2 6.25329 2 11.5C2 16.7467 6.25329 21 11.5 21Z" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M22 22L20 20" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </button>
                <input type="text" id="searchInput" name="text" class="input" placeholder="Поиск.."/>
                <div id="searchResults" class="search-results"></div>
            </div>
            <?php if (isset($_SESSION['username'])): ?>
                <a href="personal_area.php"><img src="/img/user.png" alt="Профиль"></a>
            <?php else: ?>
                <a href="#" onclick="showModal()"><img src="/img/user.png" alt="Профиль"></a>
            <?php endif; ?>
            <div id="loginModal" class="modal" style="<?php echo empty($error) ? 'display: none;' : 'display: block;'; ?>">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <form id="loginForm" action="login.php" method="post">
                        <div class="con">
                            <div class="field-set">
                                <input class="form-input" type="text" placeholder="Имя пользователя" name="username" required>
                                <input class="form-input" type="password" placeholder="Пароль" name="password" required>
                                <div id="loginError" class="error-message"></div>
                                <button type="submit" class="log-in">Войти</button>
                            </div>
                            <div class="other">
                                <button type="button" class="btn submits sign-up" onclick="showRegistration()">Регистрация</button>
                            </div>
                        </div>
                    </form>
                    <!-- Форма регистрации -->
                    <form id="regForm" action="register.php" method="post" style="display: none;">
                        <div class="con">
                            <div class="field-set">
                                <input class="form-input" type="text" placeholder="Имя пользователя" name="username" required>
                                <input class="form-input" type="text" placeholder="Имя" name="name" required>
                                <input class="form-input" type="email" placeholder="Email" name="email" required>
                                <input class="form-input" type="password" placeholder="Пароль" name="password" required>
                                <input class="form-input" type="password" placeholder="Повторите пароль" name="repeat-password" required>
                                <button type="submit" class="log-in">Регистрация</button>
                            </div>
                            <div class="other">
                                <button type="button" class="btn submits sign-up" onclick="showLogin()">Авторизоваться</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <a href="cart.php"><img src="/img/shopping-cart.png" alt="Корзина"></a>
        </div>
        <div class="header-bottom">
            <nav>
                <ul>
                    <li><a href="index.php">Главная</a></li>
                    <li><a href="index.php#new-arrivals">Новинки</a></li>
                    <li><a href="product_addition.php?category=На выпускной">На выпускной</a></li>
                    <li><a href="product_addition.php?category=Новорождённым">Новорождённым</a></li>
                    <li><a href="product_addition.php?category=Малышам">Малышам</a></li>
                    <li><a href="product_addition.php?category=Мальчикам">Мальчикам</a></li>
                    <li><a href="product_addition.php?category=Девочкам">Девочкам</a></li>
                    <li><a href="product_addition.php?category=Обувь">Обувь</a></li>
                </ul>
            </nav>
        </div>
        <div class="phone-numbers">
            <a href="tel:+78007707070">+7 (800) 770-70-70</a>
            <a href="tel:+79032607030">+7 (903) 260-70-30</a>
        </div>
    </div>
</header>
<script src="script.js"></script>
<script>
    // Обработка поиска товаров
    document.getElementById('searchInput').addEventListener('input', function() {
        var searchQuery = this.value.trim();
        var searchResultsContainer = document.getElementById('searchResults');
        if (searchQuery.length >= 3) {
            fetch('suggest_search.php?search=' + encodeURIComponent(searchQuery))
                .then(response => response.json())
                .then(data => {
                    searchResultsContainer.innerHTML = '';
                    // Показываем окно результатов поиска
                    searchResultsContainer.style.display = 'block';
                    // Добавляем результаты поиска
                    data.forEach(product => {
                        var resultItem = document.createElement('div');
                        resultItem.classList.add('result-item');
                        resultItem.innerHTML = `
                            <a href="product_detail.php?id=${product.id}" style="display: flex; align-items: center;">
                                <img src="${product.image}" alt="${product.name}">
                                <p>${product.name}</p>
                            </a>`;
                        searchResultsContainer.appendChild(resultItem);
                    });
                });
        } else {
            // Если запрос короче 3 символов, скрываем окно результатов поиска
            searchResultsContainer.style.display = 'none';
        }
    });
    document.addEventListener('click', function(event) {
        var searchResultsContainer = document.getElementById('searchResults');
        // Проверяем, был ли клик внутри окна результатов поиска или в поле поиска
        if (!searchResultsContainer.contains(event.target) && event.target !== document.getElementById('searchInput')) {
            // Если клик был вне окна результатов поиска или поля поиска, закрываем окно
            searchResultsContainer.style.display = 'none';
        }
    });
    function showModal() {
        document.getElementById('loginModal').style.display = 'block';
    }

    document.querySelector('.close').onclick = function() {
        document.getElementById('loginModal').style.display = 'none';
        document.getElementById('loginError').textContent = '';
    };

    // Скрипт для скрытия и показа хедера
    const toggleHeaderButton = document.getElementById('toggleHeaderButton');
    const header = document.querySelector('header');

    toggleHeaderButton.addEventListener('click', function() {
        header.classList.toggle('header-hidden');
        if (header.classList.contains('header-hidden')) {
            toggleHeaderButton.textContent = 'Показать хедер';
        } else {
            toggleHeaderButton.textContent = 'Скрыть хедер';
        }
    });

    // Добавляем обработчик события для скрытия хедера при клике вне него на мобильных устройствах
    document.addEventListener('click', function(event) {
        if (window.innerWidth <= 425 && !header.contains(event.target) && event.target !== toggleHeaderButton) {
            header.classList.add('header-hidden');
            toggleHeaderButton.textContent = 'Показать хедер';
        }
    });
    document.addEventListener('click', function(event) {
    // Проверяем, был ли клик вне хедера и кнопки "Скрыть хедер"
    if (!header.contains(event.target) && event.target !== toggleHeaderButton) {
        // Если да, и хедер скрыт, то показываем его
        if (header.classList.contains('header-hidden')) {
            header.classList.remove('header-hidden');
            toggleHeaderButton.textContent = 'Скрыть хедер';
        }
    }
});


    // Функция для показа или скрытия кнопки в зависимости от ширины экрана
    function toggleHeaderButtonVisibility() {
        var toggleHeaderButton = document.getElementById('toggleHeaderButton');
        if (window.innerWidth <= 425) {
            toggleHeaderButton.style.display = 'block';
        } else {
            toggleHeaderButton.style.display = 'none';
        }
    }

    // Вызываем функцию для первоначальной настройки видимости кнопки при загрузке страницы
    toggleHeaderButtonVisibility();

    // Обработчик события изменения размера окна браузера для динамического обновления видимости кнопки
    window.addEventListener('resize', toggleHeaderButtonVisibility);
</script>

</body>
</html>
