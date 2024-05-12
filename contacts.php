
<?php 
$pageTitle = "Детский бутик";
include('header.php'); 
?>
    <!-- Контакты -->
    <section class="contacts">
        <h2>Контакты</h2>
        <p>Телефон: +7 (495) 123-45-67</p>
        <p>Email: [info@detbutik.ru](mailto:info@detbutik.ru)</p>
        <p>Адрес: г. Москва, ул. Ленина, 123</p>
        <p>Время работы: Пн-Пт с 10:00 до 18:00</p>
    </section>

    <!-- Форма обратной связи -->
    <section class="feedback">
        <h2>Обратная связь</h2>
        <form>
            <label for="name">Имя:</label>
            <input type="text" id="name" name="name"><br><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email"><br><br>
            <label for="message">Сообщение:</label>
            <textarea id="message" name="message"></textarea><br><br>
            <input type="submit" value="Отправить">
        </form>
    </section>

     <!-- Карта -->
     <section class="map">
        <h2>Карта</h2>
        <iframe src="https://www.google.com/maps/embed?..." width="100%" height="400"></iframe>
    </section>
    
    <script src="script.js"></script>
     <!-- Футер сайта, если есть -->
     <?php include('footer.php'); ?>