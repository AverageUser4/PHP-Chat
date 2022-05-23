"use strict";

const email_label_span = document.getElementById('emailLabel').firstElementChild;
const email = document.getElementById('email');
const pass2 = document.getElementById('pass2');
const gender = document.getElementById('gender')
const incorrect_emails = [];

function overwriteDocument() {
  const username = login.value.replace('<', '&lt;');
  form.innerHTML = 
  `
  <p>
    Gratulacje, ${username}! <br>
    Pomyślnie udało ci się utworzyć konto w naszym serwisie. <br>
    <a href="login.php">Kilknij tutaj, żeby się zalogować.</a> <br>
  </p>
  `;
}