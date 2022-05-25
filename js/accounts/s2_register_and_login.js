"use strict";

const form = document.getElementById('registerForm');
form.addEventListener('submit', validateForm);
const login = document.getElementById('login');
const pass = document.getElementById('pass');
const login_label_span = document.getElementById('loginLabel').firstElementChild;
const pass_label_span = document.getElementById('passLabel').firstElementChild;
const incorrect_logins = [];
const incorrect_passwords = [];
let register_extended = false;
if(typeof email !== 'undefined')
  register_extended = true;

function validateForm(event) {
  event.preventDefault();

  if(register_extended)
    email_label_span.innerHTML = '';
  login_label_span.innerHTML = '';
  pass_label_span.innerHTML = '';
  let test;


  /*
   these messages may not be correct, ideally
   incorrect thing should be added to array with
   error message
  */
  if(register_extended)
    if(incorrect_emails.includes(email.value)) {
      showError('email', 'E-mail zajęty.');
      return;
    }
  if(incorrect_logins.includes(login.value)) {
    showError('login', register_extended ?
    'Login zajęty.' : 'Niepoprawne dane logowania.');
    return;
  }
  if(incorrect_passwords.includes(pass.values)) {
    showError('pass', register_extended ?
    'Niepoprawne hasło.' : 'Niepoprawne dane logowania.');
  }

  if(register_extended) {
    test = validEmail(email.value);
    if(!test[0]) { showError('email', test[1]); return; }
    test = validPassword(pass.value, pass2.value);
  }
  else
    test = validPassword(pass.value, pass.value);
  if(!test[0]) { showError('pass', test[1]); return; }

  test = validLogin(login.value);
  if(!test[0]) { 
    test = validEmail(login.value);
    if(register_extended || !test[0]) { showError('login', test[1]); return; }
  } 


  let path;
  const form_data = new FormData();
  form_data.append('username', login.value);
  form_data.append('password', pass.value);
  if(register_extended) {
    form_data.append('email', email.value);
    form_data.append('gender', gender.value);
    path = '../php/accounts/register_validate.php';
  }
  else
    path = '../php/accounts/login_validate.php';

  sendRequest(registerRequestResult, path, form_data, 'POST');
}

function registerRequestResult() {
  let response = this.responseText;
  if(response.startsWith('error%')) {
    response = response.slice(6);
    if(register_extended) {
      if(response.startsWith('e')) {
        incorrect_emails.push(email.value);
        showError('email', response.slice(1));  
      }
      else if(response.startsWith('u')) {
        incorrect_logins.push(login.value);
        showError('login', response.slice(1));  
      }
    }
    else if(
        response.startsWith('e')
        || response.startsWith('u')
        || response.includes('Niepoprawne dane logowania')
      ) {
      incorrect_logins.push(login.value);
      showError('login', response.slice(1));
    }
    if(response.startsWith('p')) {
      incorrect_passwords.push(pass.value);
      showError('pass', response.slice(1));
    }
    if(
        !response.startsWith('e')
        && !response.startsWith('u')
        && !response.startsWith('p')
        && !response.includes('Niepoprawne dane logowania')
      )
      alert(response);
    return;
  }
  if(register_extended)
    overwriteDocument();
  else
    location.assign('chat_room.php');
}

function showError(where, errmsg) {
  if(register_extended)
    if(where === 'email') {
      email_label_span.innerHTML = errmsg;
      return;
    }

  if(where === 'login')
    login_label_span.innerHTML = errmsg;
  else if(where === 'pass')
    pass_label_span.innerHTML = errmsg;
}