
<?php 
$pageTitle = "Детский бутик";
include('header.php'); 
?>
<!-- Каталог товаров -->
<section class="catalog">
    <h2>Каталог товаров</h2>
    <div class="products">
        <div class="product">
            <img src="img/product1.jpg" alt="Продукт 1">
            <h3>Футболка для мальчика</h3>
            <p>Цена: 500 руб.</p>
            <button class="add-to-cart">Добавить в корзину</button>
        </div>
        <div class="product">
            <img src="img/product2.jpg" alt="Продукт 2">
            <h3>Платье для девочки</h3>
            <p>Цена: 800 руб.</p>
            <button class="add-to-cart">Добавить в корзину</button>
        </div>
        <!-- и другие товары -->
    </div>
    <div class="pagination">
        <a href="#" class="prev">Предыдущая</a>
        <a href="#" class="next">Следующая</a>
        <p>Страница 1 из 5</p>
    </div>
</section>
<script src="script.js"></script>
<!-- Футер сайта, если есть -->
<?php include('footer.php'); ?>

