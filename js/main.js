/* generic function for sending xmlhttp requests */

function sendRequest(callback, file, params = '', method = 'GET') {
  xmlhttp = new XMLHttpRequest();

  xmlhttp.onload = callback;

  if(method === 'GET') {
    xmlhttp.open(method, file + '?' + params);
    xmlhttp.send();
  }
  else {
    xmlhttp.open(method, file);
    xmlhttp.send(params);
  }
}


/* ask for 50 latest messages from database */
const output_wrapper = document.getElementById('outputWrapper');
let oldest_message_id = 0;
let latest_message_id = 0;
let client_server_time_difference = 0;
sendRequest(getMessagesWhenLoaded, 'php/load_old_messages.php');

function getMessagesWhenLoaded() {
  const arr = this.responseText.split('%');
  
  if(arr[0] === 'error') {
    alert(arr[1]);
    return;
  }
  
  let len = arr.length;
  let server_time = arr[0];
  let client_time = Math.trunc(Date.now() / 1000);
  client_server_time_difference = client_time - server_time;

  oldest_message_id = arr[1];
  latest_message_id = arr[2];

  console.log(arr);

  if(len === 153)
    output_wrapper.addEventListener('scroll', scrolledToTop);
  
  for(let i = 3; i < len; i += 3) {
    const div = document.createElement('DIV');

    div.innerHTML =
    `<h3>${arr[i]}</h3>
    <h4>${parseMessageDate(arr[i + 2])}</h4>
    <p>${arr[i + 1]}</p>`;

    output_wrapper.insertBefore(div, output_wrapper.firstChild);
  }

  output_wrapper.scrollTo(0, 999999);
}

function parseMessageDate(initial_date) {
  let year = initial_date.slice(0, 4);
  let month = initial_date.slice(5, 7);
  let day = initial_date.slice(8, 10);
  let hour = initial_date.slice(11, 13);
  let minute = initial_date.slice(14, 16);
  let second = initial_date.slice(17, 19);
  let full_date = new Date (
    Date.parse(`${month} ${day} ${year} ${hour}:${minute}:${second}`) -
    client_server_time_difference);

  return full_date.toLocaleString();
}


/* send message */
const input_field = document.getElementById('textInput');
const send_button = document.getElementById('sendButton');
let msg = '';

send_button.addEventListener('click', sendMessage);
window.addEventListener('keyup', keyUpReact);

function keyUpReact(event) {
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

function sendMessage() {
  msg = input_field.value;
  input_field.value = '';

  if(msg === '' || (msg.indexOf(' ') !== -1 && msg.match(/ /g).length === msg.length)) {
    return;
  }
  
  sendRequest(sendMessageUpdate, 'php/send_message.php', `user=user12345&message=${msg}`);
}

function sendMessageUpdate() {
  if(this.responseText === 'false')
    return;

  const div = document.createElement('DIV');
  div.innerHTML = `<h3>user12345</h3><p>${msg.replace(/</g, '&lt;')}</p>`;
  output_wrapper.appendChild(div);
  output_wrapper.scrollTo(0, 999999);
}


/* ask for older messages, if user has scrolled to top */
let old_messages_array = [];
let no_more_old_messages = false;

function scrolledToTop() {
  // onscroll event listener is added in getMessagesWhenLoaded, if there were 20 messages
  if(output_wrapper.scrollTop !== 0)
    return;

  if(!no_more_old_messages && old_messages_array.length === 0)
    sendRequest(readIncomingOldMessages, 'php/load_old_messages.php', `oldest=${oldest_message_id}`);
  else
    loadOldMessage();
}

function readIncomingOldMessages() {
  old_messages_array = this.responseText.split('%');

  if(old_messages_array[0] === 'error') {
    output_wrapper.innerHTML = `<p>${old_messages_array[1]}</p>`;
    return;
  }

  let len = old_messages_array.length;
  oldest_message_id = old_messages_array[0];

  //453, 63 for testing
  if(len < 63)
    no_more_old_messages = true;

  if(old_messages_array.length >= 2) {
    old_messages_array.shift();
    old_messages_array.shift();
  }

  loadOldMessage();
}

function loadOldMessage() {
  let init_height = output_wrapper.scrollHeight;

  if(old_messages_array.length >= 2) {
    const div = document.createElement('DIV');
    const p = old_messages_array.shift();
    const h3 = old_messages_array.shift();
    div.innerHTML = `<h3>${h3}</h3><p>${p}</p>`;
    output_wrapper.insertBefore(div, output_wrapper.firstChild);
  }

  if(no_more_old_messages && old_messages_array.length === 0)
      output_wrapper.removeEventListener('scroll', scrolledToTop);

  output_wrapper.scrollTo(0, output_wrapper.scrollHeight - init_height);
}

//console.log(navigator.language);
