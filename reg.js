document.addEventListener('DOMContentLoaded', function() {
  const form = document.querySelector('#regForm');
  const modal = document.getElementById('myModal');
  const closeBtn = document.querySelector('.close-btn');
  const messageDiv = document.getElementById('modal-message');

  form.addEventListener('submit', function(event) {
      event.preventDefault(); // Остановить отправку формы
      const formData = new FormData(this);

      fetch('reg.php', {
          method: 'POST',
          body: formData,
      })
      .then(response => response.json())
      .then(data => {
          if (data.success) {
              messageDiv.textContent = data.message; // Установить сообщение
              modal.style.display = 'flex'; // Показать модальное окно
              
          } else {
              alert(data.message); // Покажем сообщение об ошибке
          }
      })
      .catch(error => console.error('Ошибка:', error));
  });

  closeBtn.onclick = function() {
      modal.style.display = 'none'; // Скрыть модальное окно
  };

  window.onclick = function(event) {
      if (event.target === modal) {
          modal.style.display = 'none'; // Скрыть при клике вне модального окна
      }
  };
});
