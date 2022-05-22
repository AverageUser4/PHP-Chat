"use strict";

const form = document.getElementById('registerForm');
form.addEventListener('submit', validateForm);
const email = document.getElementById('email');
const login = document.getElementById('login');
const pass = document.getElementById('pass');
const pass2 = document.getElementById('pass2');
const gender = document.getElementById('gender')

function validateForm(event) {
  event.preventDefault();

  if(!validEmail(email.value))
    alert('Niepoprawny adres e-mail!');
  else if(!validLogin(login.value))
    alert('Niepoprawny login!');
  else if(!validPassword(pass.value))
    alert('Hasło może zawierać od 5 do 72 znaków');
  else if(pass.value !== pass2.value)
    alert('Hasła nie są identyczne!');
  else {
    const form_data = new FormData();
    form_data.append('email', email.value);
    form_data.append('username', login.value);
    form_data.append('password', pass.value);
    form_data.append('gender', gender.value);
    sendRequest(registerRequestResult,
    '../../php/accounts/register_validate.php', form_data, 'POST');
  }
}

function registerRequestResult() {
  const response = this.responseText;
  // if(response.startsWith('error%')) {
  //   alert(response.slice(6));
  //   return;
  // }
  alert(response);
  //przenieś na inną stronę
}