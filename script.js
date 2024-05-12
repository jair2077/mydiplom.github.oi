(function() {
    // Инициализация слайдера и прочего
    var autoUpdate = false,
        timeTrans = 4000;
    var cdSlider = document.querySelector('.cd-slider'),
        item = cdSlider.querySelectorAll("li"),
        nav = cdSlider.querySelector("nav");

    item[0].className = "current_slide";

    for (var i = 0, len = item.length; i < len; i++) {
        var color = item[i].getAttribute("data-color");
        item[i].style.backgroundColor = color;
    }

    if (item.length <= 1) {
        nav.style.display = "none";
    }

    function prevSlide() {
        var currentSlide = cdSlider.querySelector("li.current_slide"),
            prevElement = currentSlide.previousElementSibling,
            prevSlide = prevElement ? prevElement : item[item.length - 1],
            prevColor = prevSlide.getAttribute("data-color"),
            el = document.createElement('span');

        currentSlide.className = "";
        prevSlide.className = "current_slide";
        nav.children[0].appendChild(el);

        var size = Math.max(cdSlider.clientWidth, cdSlider.clientHeight) * 2,
            ripple = nav.children[0].querySelector("span");

        ripple.style.height = size + 'px';
        ripple.style.width = size + 'px';
        ripple.style.backgroundColor = prevColor;

        ripple.addEventListener("webkitTransitionEnd", removeRipple);
        ripple.addEventListener("transitionend", removeRipple);
    }

    function nextSlide() {
        var currentSlide = cdSlider.querySelector("li.current_slide"),
            nextElement = currentSlide.nextElementSibling,
            nextSlide = nextElement ? nextElement : item[0],
            nextColor = nextSlide.getAttribute("data-color"),
            el = document.createElement('span');

        currentSlide.className = "";
        nextSlide.className = "current_slide";
        nav.children[1].appendChild(el);

        var size = Math.max(cdSlider.clientWidth, cdSlider.clientHeight) * 2,
            ripple = nav.children[1].querySelector("span");

        ripple.style.height = size + 'px';
        ripple.style.width = size + 'px';
        ripple.style.backgroundColor = nextColor;

        ripple.addEventListener("webkitTransitionEnd", removeRipple);
        ripple.addEventListener("transitionend", removeRipple);
    }

    function removeRipple(event) {
        if (event.target.parentNode) {
            event.target.parentNode.removeChild(event.target);
        }
    }

    function updateNavColor() {
        var currentSlide = cdSlider.querySelector("li.current_slide"),
            nextColor = currentSlide.nextElementSibling ? currentSlide.nextElementSibling.getAttribute("data-color") : item[0].getAttribute("data-color"),
            prevColor = currentSlide.previousElementSibling ? currentSlide.previousElementSibling.getAttribute("data-color") : item[item.length - 1].getAttribute("data-color");

        if (item.length > 2) {
            nav.querySelector(".prev").style.backgroundColor = prevColor;
            nav.querySelector(".next").style.backgroundColor = nextColor;
        }
    }

    // Управление модальным окном и формами
    var modal = document.getElementById('loginModal');
    var btnOpen = document.querySelector('.header-controls img');
    var btnClose = document.querySelector('.close');

    btnOpen.addEventListener('click', function() {
        if (!sessionStorage.getItem('logged_in')) {
            modal.style.display = 'block';
        }
    });

    btnClose.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    window.onclick = function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    };

    var loginForm = document.getElementById('loginForm');
    loginForm.onsubmit = function(event) {
        event.preventDefault();
        var formData = new FormData(loginForm);
        fetch('login.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                sessionStorage.setItem('logged_in', 'true');
                window.location.href = 'personal_area.php'; // Redirect to user profile page
            } else {
                document.getElementById('loginError').textContent = data.message;
            }
        })
        .catch(error => console.error('Ошибка:', error));
    };

    var regForm = document.getElementById('regForm');
    regForm.onsubmit = function(event) {
        event.preventDefault();
        var formData = new FormData(regForm);
        fetch('register.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message); // Show server message
            if (data.success) {
                showLogin(); // Switch to login form
            }
        })
        .catch(error => console.error('Ошибка:', error));
    };

    function showRegistration() {
        document.getElementById('loginForm').style.display = 'none';
        document.getElementById('regForm').style.display = 'block';
    }

    function showLogin() {
        document.getElementById('loginForm').style.display = 'block';
        document.getElementById('regForm').style.display = 'none';
    }

    document.querySelector('.btn.submits.sign-up[onclick="showRegistration()"]').addEventListener('click', showRegistration);
    document.querySelector('.btn.submits.sign-up[onclick="showLogin()"]').addEventListener('click', showLogin);

    nav.querySelector(".next").addEventListener('click', function(event) {
        event.preventDefault();
        nextSlide();
        updateNavColor();
    });

    nav.querySelector(".prev").addEventListener("click", function(event) {
        event.preventDefault();
        prevSlide();
        updateNavColor();
    });
})();
document.querySelector('.toggle-button').addEventListener('click', function() {
    var info = document.querySelector('.additional-info');
    if (info.style.display === 'none' || info.style.display === '') {
        info.style.display = 'block';
        this.textContent = 'Свернуть';
    } else {
        info.style.display = 'none';
        this.textContent = 'Подробнее';
    }
});


