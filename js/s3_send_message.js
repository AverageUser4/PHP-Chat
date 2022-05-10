"use strict";

/* send message */

const input_field = document.getElementById('textInput');
const send_button = document.getElementById('sendButton');
let msg = '';

send_button.addEventListener('click', sendMessage);
input_field.addEventListener('input', validateInput);
window.addEventListener('keyup', keyUpReact);

function keyUpReact(event) {
  // focus input field when one of the common keys is pressed

  if(event.altKey || event.ctrlKey)
    return;

  if(event.key === 'Enter')
    sendMessage();
  else if
    (
      ((event.which >= 65 && event.which <= 90)
      || (event.which >= 48 && event.which <= 57))
      && document.activeElement.id !== 'textInput'
    )
    {
      input_field.value += event.key;
      input_field.focus();
    }
  else if(event.key === 'Escape')
    input_field.blur();
}

function validateInput() {
  // make sure input field value doesn't exceed given amount of BYTES

  let val = input_field.value;
  let char_len = val.length;
  let byte_len = 0;

  for(let i = 0; i < char_len; i++) {
    const code = val.charCodeAt(i);
    let add_what;
    if(val.charAt(i) === '&'
      || val.charAt(i) === '"'
      || val.charAt(i) === "'"
      || val.charAt(i) === '<'
      || val.charAt(i) === '>'
      || val.charAt(i) === '%'
      ) add_what = 5;
    else if(code <= 0xff)add_what = 1;
    else if(code <= 0xffff)add_what = 2;
    else if(code <= 0xffffff)add_what = 3;
    else add_what = 4;

    if(byte_len + add_what > 256) {
      input_field.value = val.substring(0, i);
      break;
    }

    byte_len += add_what;
  }
}

function sendMessage() {
  // send message unless it's empty string or consists only of spaces

  msg = input_field.value;
  input_field.value = '';

  if(msg === '' || (msg.indexOf(' ') !== -1 && msg.match(/ /g).length === msg.length))
    return;
  
  sendRequest(sendMessageUpdate, 'php/send_message.php', `user=user12345&message=${msg}`);
}

function sendMessageUpdate() {
  // append sent message to the output field

  if(this.responseText.startsWith('error%')) {
    alert(this.responseText.slice(6));
    return;
  }

  const div = document.createElement('DIV');

  div.innerHTML =
  `<h3>user12345</h3>
  <h4>${new Date().toLocaleString()}</h4>
  <p>${msg.replace(/</g, '&lt;')}</p>`;

  output_wrapper.appendChild(div);
  output_wrapper.scrollTo(0, 999999);
}

