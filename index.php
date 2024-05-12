<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная страница</title>
</head>
<body>
<?php include('header.php'); // Включаем header ?>

<div class="catalog-container">
    <div class="catalog-item">
        <a href="product_addition.php?category=Девочкам">
            <img src="/img/girl.jpg" alt="Девочкам">
            <p>Девочкам</p>
        </a>
    </div>
    <div class="catalog-item">
        <a href="product_addition.php?category=На выпускной">
            <img src="/img/graduation.jpg" alt="На выпускной">
            <p>На выпускной</p>
        </a>
    </div>
    <div class="catalog-item">
        <a href="product_addition.php?category=Мальчикам">
            <img src="/img/man.jpg" alt="Мальчикам">
            <p>Мальчикам</p>
        </a>
    </div>
    <div class="catalog-item">
        <a href="product_addition.php?category=Новорождённым">
            <img src="/img/newborn.png" alt="Новорождённым">
            <p>Новорождённым</p>
        </a>
    </div>
    <div class="catalog-item">
        <a href="product_addition.php?category=Малышам">
            <img src="/img/mininmen.jpg" alt="Малышам">
            <p>Малышам</p>
        </a>
    </div>
    <div class="catalog-item">
        <a href="product_addition.php?category=Обувь">
            <img src="/img/shoes.jpg" alt="Обувь">
            <p>Обувь</p>
        </a>
    </div>
</div>


<div class="new-arrivals">
<h2 class="section-title" id="new-arrivals">Новинки</h2>
    <div class="new-arrivals-container">
        <?php
        include 'db_connection.php';
        $query = "SELECT * FROM products WHERE category = 'Новинки'";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="new-arrival-item">';
                echo '<a href="product_detail.php?id=' . $row['id'] . '">';
                echo '<img src="' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                echo '<div class="item-info">';
                echo '<p>' . date("Y") . ' КОЛЛЕКЦИЯ</p>';
                echo '<p>' . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . '</p>';
                echo '<p>' . htmlspecialchars($row['price']) . ' руб.</p>';
                echo '</div></a>';
                echo '<a href="cart.php?action=add&id=' . $row['id'] . '" class="button">Добавить в корзину</a>';
                echo '</div>';
            }
        } else {
            echo '<p>Нет новинок.</p>';
        }
        ?>
    </div>
</div>



<section class="cd-slider">
  <ul>
    <li data-color="">
      <div class="content" style="background-image:url(/img/kids5.jpg)">
        <blockquote>
          <p>Летняя коллекция</p>
          <span>Успей купить пока не разобрали</span>
        </blockquote>
      </div>
    </li>
    <li data-color="">
      <div class="content" style="background-image:url(/img/kids2.jpg)">
        <blockquote>
          <p>Покупай только лучшие</p>
          <span>Все для ваших детей</span>
        </blockquote>
      </div>
    </li>
    <li data-color="">
      <div class="content" style="background-image:url(/img/kids4.jpg)">
        <blockquote>
          <p>Подбери образ</p>
          <span>Для своего ребёнка</span>
        </blockquote>
      </div>
    </li>
  </ul>
  <nav>
    <div><a class="prev" href="#"></a></div>
    <div><a class="next" href="#"></a></div>
  </nav>
</section>
<div class="about-section">
    <h2 class="about-title">ИНТЕРНЕТ-МАГАЗИН ДЕТСКОЙ ОДЕЖДЫ «MiniMode»</h2>
    <p class="about-text">Сеть детских бутиков «MiniMode» – это настоящее королевство моды, где царят любовь к детям и стремление наполнить их жизнь красотой и радостью. Мы по праву гордимся высочайшим уровнем обслуживания, являющимся эталонным даже для зарубежных компаний. Мы искренне ценим каждого клиента и на протяжении более чем 25 лет делаем все, чтобы Вы могли ответить нам взаимностью. «MiniMode» предлагает своим клиентам:</p>
    <button class="toggle-button">Подробнее</button>
    <div class="additional-info">
        <p>Только оригинальные товары. Мы уверены, что хороший вкус необходимо развивать с детства. Стараясь преподнести все самое лучшее из мира детской моды, в каталоге нашего интернет-магазина мы собрали самые качественные и оригинальные товары, более 200 премиальных детских брендов;</p>
        <p>Удобную доставку. Мы отправим Ваш заказ в любой уголок России. Детские вещи нужны срочно? Если Вы живете в Москве, мы можем привезти их в течение 3-х часов, с возможностью выбора из нескольких вариантов и примерки;</p>
        <p>Большой выбор надежных способов оплаты.</p>
        <h2 class="about-title">БРЕНДОВАЯ ДЕТСКАЯ ОДЕЖДА</h2>
        <p>Способность выражать внутренний мир через внешний облик – это талант, который стоит развивать с ранних лет. Брендовая детская одежда помогает развивать у ребенка вкус и чувство стиля, которые станет визитной карточкой в его дальнейшей жизни.</p>
        <h2 class="about-title">ОДЕЖДА МЕНЯЕТСЯ, А ВКУС ОСТАЕТСЯ</h2>
        <p>Магазин брендовой детской одежды «MiniMode» приглашает родителей и детей за покупками. В нашем каталоге вы найдете вещи для мальчиков и девочек всех возрастов. Походы по магазинам занимают много времени? Вы можете быстро сделать заказ на нашем сайте. Среди наших товаров – одежда брендов Aletta, Dolce & Gabbana, Elisabetta Franchi, Il Gufo, Bikkembergs, Emporio Armani, Moschino, Maison Margiela и другие.</p>
        <p>Детская брендовая одежда обладает преимуществами:</p>
        <p>качественные и натуральные материалы: хлопок, шелк, шерсть, вискоза, кашемир;</p>
        <p>безупречный пошив и идеальный крой;</p>
        <p>многообразие стилей: от классики до casual и спортивного стиля;</p>
        <p>эксклюзивные фасоны и дизайнерские решения.</p>
        <p>Мы предлагаем купить В «MiniMode» детскую брендовую одежду в комфортных для вас условиях – не выходя из дома. У нас не только оригинальные вещи, но и отличный сервис, который сделает шопинг еще и приятным.</p>
        <p>В каталоге вы найдете стильную повседневную, пляжную, спортивную, элегантную школьную и яркую праздничную одежду для малышей и подростков.</p>        
        <h2 class="about-title">БРЕНДОВАЯ ДЕТСКАЯ ОДЕЖДА С ДОСТАВКОЙ ПО ВСЕЙ РОССИИ</h2>
        <p>Наш интернет-магазин более 25 лет на рынке детской брендовой одежды. За это время мы организовали систему доставки товаров по всей России. Заказы доставляем несколькими способами: курьером по Москве (в том числе экспресс-доставка), Московской области, транспортными компаниями в другие города России. При доставке по Москве и Московской области можно померить вещи перед их покупкой.</p>
        <p>Оплата производится удобным для вас способом: наличными или банковской картой при доставке, картой на сайте (защищено протоколом SSL), на расчетный счет.</p>
        <p>Мы также создали программу лояльности для постоянных покупателей, которая позволит вам экономить до 20% от стоимости заказов.</p>
        <p>Выбирайте лучшее! Чем красивее одежда, тем ярче жизнь!</p>
        
        <!-- Добавьте всю остальную информацию из вашего изображения здесь -->
    </div>
</div>


<script src="script.js" defer></script>
<?php include('footer.php'); // Включаем footer ?>

</body>
</html>
