document.addEventListener('DOMContentLoaded', function() {
  var form = document.querySelector('form[action="login.php"]');
  if (form) {
      form.addEventListener('submit', function(event) {
          event.preventDefault();
          var formData = new FormData(this);
          var xhr = new XMLHttpRequest();
          xhr.open('POST', 'login.php', true);
          xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
          xhr.onreadystatechange = function() {
              if (xhr.readyState === 4 && xhr.status === 200) {
                  var response = JSON.parse(xhr.responseText);
                  var messageDiv = document.querySelector('#message');
                  messageDiv.textContent = response.message;
                  messageDiv.style.color = response.success ? 'green' : 'red';
                  if (response.success) {
                      window.location.href = 'personal_area.php';
                  }
              }
          };
          xhr.send(formData);
      });
  }
});
