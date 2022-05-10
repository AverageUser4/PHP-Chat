"use strict";

/* ask for 50 latest messages from database when document is loaded */

sendRequest(getMessagesWhenLoaded, 'php/load_old_messages.php');

function getMessagesWhenLoaded() {
  // insert messages we asked for into the output field

  const arr = this.responseText.split('%');
  
  if(arr[0] === 'error') {
    alert(arr[1]);
    return;
  }
  
  let len = arr.length;
  let server_time = arr[0];
  let client_time = Math.trunc(Date.now() / 1000);
  client_server_time_difference = client_time - server_time;

  latest_message_id = arr[1];
  oldest_message_id = arr[2];

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


/* ask for older messages, if user has scrolled to top */

let old_messages_array = [];
let no_more_old_messages = false;

function scrolledToTop() {
  // onscroll event listener is added in getMessagesWhenLoaded, if there were enough messages
  // sends request for more messages or inserts message from array into the output field

  if(output_wrapper.scrollTop !== 0)
    return;

  if(!no_more_old_messages && old_messages_array.length === 0)
    sendRequest(readIncomingOldMessages, 'php/load_old_messages.php', `oldest=${oldest_message_id}`);
  else
    loadOldMessage();
}

function readIncomingOldMessages() {
  // store requested messages in array

  old_messages_array = this.responseText.split('%');

  if(old_messages_array[0] === 'error') {
    alert(old_messages_array[1]);
    return;
  }

  let len = old_messages_array.length;
  oldest_message_id = old_messages_array[0];

  //451, 61 for testing
  if(len < 61)
    no_more_old_messages = true;

  old_messages_array.shift();

  loadOldMessage();
}

function loadOldMessage() {
  // when users scrolls to top, one message from array is inserted at once

  let init_height = output_wrapper.scrollHeight;

  if(old_messages_array.length >= 3) {
    const div = document.createElement('DIV');
    const h3 = old_messages_array.shift();
    const p = old_messages_array.shift();
    const h4 = parseMessageDate(old_messages_array.shift());
    div.innerHTML = `<h3>${h3}</h3><h4>${h4}</h4><p>${p}</p>`;
    output_wrapper.insertBefore(div, output_wrapper.firstChild);
  }

  if(no_more_old_messages && old_messages_array.length === 0)
      output_wrapper.removeEventListener('scroll', scrolledToTop);

  output_wrapper.scrollTo(0, output_wrapper.scrollHeight - init_height);
}

/*
- when document is loaded, server sends it's current dateTime,
latest message's ID and oldest message's ID at the beginnig of array with
requested messages, when we request older messages, we only get additionaly
oldest message's ID, because that's only thing that changes
*/