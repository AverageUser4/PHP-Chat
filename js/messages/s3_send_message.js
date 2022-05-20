"use strict";

/* send message */

const input_field = document.getElementById('textInput');
const send_button = document.getElementById('sendButton');
const honey_pot = document.getElementById('honeypot');
const regexp = /\p{C}|\p{Z}|\u034f|\u115f|\u1160|\u17b4|\u17b5|\u180e|\u2800|\u3164|\uffa0/gu;
const regexp_space = / /gu;
const sent_messages_array = [];
let index_of_message_to_show;
let msg = '';
let last_message_time = Date.now();
let messages_per_minute = 0;
let limit_ends_at = Date.now() + 60000;
setInterval(() => { messages_per_minute = 0;
limit_ends_at = Date.now() + 60000; }, 60000);

send_button.addEventListener('click', sendMessage);
input_field.addEventListener('input', validateInput);
window.addEventListener('keyup', keyUpReact);
window.addEventListener('keydown', preventArrowDefault);

function preventArrowDefault(event) {
  if(event.key === 'ArrowUp' || event.key === 'ArrowDown')
    event.preventDefault();
}

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
  else if(event.key === 'Escape') {
    updateMessArr();
    input_field.value = '';
    input_field.blur();
  }
  else if(event.key === 'ArrowUp' || event.key === 'ArrowDown') {
    if(sent_messages_array.length === 0)
      return;
    input_field.focus();

    if(input_field.value !== '')
      if(updateMessArr())
      index_of_message_to_show--;

    event.key === 'ArrowUp' ? index_of_message_to_show--
    :  index_of_message_to_show++;

    if(index_of_message_to_show < 0)
      index_of_message_to_show = sent_messages_array.length - 1;
    if(index_of_message_to_show > sent_messages_array.length - 1)
      index_of_message_to_show = 0;

    input_field.value = sent_messages_array[index_of_message_to_show];
    return;
  }

  index_of_message_to_show = sent_messages_array.length;
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

function updateMessArr(enter_invoked = false) {
  const swap_index = sent_messages_array.indexOf(input_field.value);
  if(swap_index !== -1) {
    if(enter_invoked) {
      const val = sent_messages_array.splice(swap_index, 1);
      sent_messages_array.push(val[0]);
    }
    return false;
  }
  sent_messages_array.push(input_field.value);
  if(sent_messages_array.length > 100)
    sent_messages_array.shift();
  index_of_message_to_show = sent_messages_array.length;
  return true;
}

function sendMessage() {
  // send message unless it's empty string or consists only of spaces
  // also some spam protection
  if(honey_pot.value !== '')
    return;

  if(input_field.value === '')
    return;

  if(Date.now() - last_message_time < 300)
    return;
  last_message_time = Date.now();

  if(++messages_per_minute >= 45) {
    let secs_left = Math.trunc((limit_ends_at - Date.now()) / 1000);
    alert(`Limit 45 wiadomości na minutę przekroczony. Pozostało ${secs_left} sekund.`);
    return;
  }

  updateMessArr(true);
  msg = input_field.value;
  input_field.value = '';

  let char_test = msg.match(regexp);
  if(char_test !== null) {
    let space_test = msg.match(regexp_space);
    if(space_test === null || char_test.length > space_test.length) {
      if(confirm('Twoja wiadomość zawiera niedozwolone znaki. Czy chcesz je zamienić na spacje?'))
        msg = msg.replace(regexp, ' ');
      else
        return;
    }
  }

  if(msg.indexOf(' ') !== -1 && msg.match(/ /g).length === msg.length) {
    alert('Wiadomość nie może składać się z samych spacji.');
    return;
  }
  
  sendRequest(sendMessageUpdate, 'php/messages/send_message.php',
  `user=${guest_name_encoded}&guest_id=${guest_id}&guest_token=${guest_token}&message=${encodeURIComponent(msg)}`);
}

function sendMessageUpdate() {
  // append sent message to the output field

  if(this.responseText.startsWith('error%')) {
    alert(this.responseText.slice(6));
    return;
  }

  createMessageElement(guest_name, new Date().toLocaleString(),
  msg.replace(/&/g, '&amp;').replace(/</g, '&lt;'), true);
  output_container.scrollTo(0, 999999);
}