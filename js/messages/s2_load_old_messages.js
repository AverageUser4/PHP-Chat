"use strict";

let old_messages_array = [];
let no_more_old_messages = false;

/* read 50 latest messages from template tag when document is loaded */


function getMessagesWhenLoaded() {
  // insert messages we asked for into the output field

  const old_mes_data = document.getElementById('old_mes_data');
  if(old_mes_data.innerHTML.startsWith('error%')) {
    alert(old_mes_data.innerHTML.slice(6));
    return;
  }
  const json_data = JSON.parse(old_mes_data.innerHTML);
  // json_data(obj) -> messages_data, oldest_message_id, latest_message_id, server_time
  // json_data(obj) -> messages_data(arr) -> arr_elements(obj) -> message_id, username, content, data
  
  const len = json_data.messages_data.length;
  const server_time = json_data.server_time;
  const client_time = Math.trunc(Date.now() / 1000);
  client_server_time_difference = client_time - server_time;

  latest_message_id = json_data.latest_message_id;
  oldest_message_id = json_data.oldest_message_id;

  console.log(len);
  if(len === 50)
    output_container.addEventListener('scroll', scrolledToTop);
  
  for(let i = 0; i < len; i++)
    createMessageElement(
      json_data.messages_data[i].username,
      parseMessageDate(json_data.messages_data[i].date),
      json_data.messages_data[i].content,
      false
    );

  output_container.scrollTo(0, 999999);
}

/* ask for older messages, if user has scrolled to top */

function scrolledToTop() {
  // onscroll event listener is added in getMessagesWhenLoaded, if there were enough messages
  // sends request for more messages or inserts message from array into the output field

  if(output_container.scrollTop !== 0)
    return;

  if(!no_more_old_messages && old_messages_array.length === 0)
    sendRequest(readIncomingOldMessages, '../php/messages/load_old_messages.php', `oldest=${oldest_message_id}`);
  else
    updateOldMessage();
}

function readIncomingOldMessages() {
  // store requested messages in array

  if(this.responseText.startsWith('error%')) {
    alert(this.responseText.slice(6));
    return;
  }

  const json_data = JSON.parse(this.responseText);
  old_messages_array = json_data.messages_data;

  const len = old_messages_array.length;
  oldest_message_id = json_data.oldest_message_id;

  if(len < 150)
    no_more_old_messages = true;

  updateOldMessage();
}

function updateOldMessage() {
  // when users scrolls to top, one message from array is inserted at once

  const init_height = output_container.scrollHeight;

  if(old_messages_array.length >= 1) {
    const h3 = old_messages_array[0].username;
    const p = old_messages_array[0].content;
    const h4 = parseMessageDate(old_messages_array[0].date);
    old_messages_array.shift();
    createMessageElement(h3, h4, p, false)
  }

  if(no_more_old_messages && old_messages_array.length === 0)
      output_container.removeEventListener('scroll', scrolledToTop);

  output_container.scrollTo(0, output_container.scrollHeight - init_height);
}

/*
- when document is loaded, server sends it's current dateTime,
latest message's ID and oldest message's ID at the beginnig of array with
requested messages, when we request older messages, we only get additionaly
oldest message's ID, because that's only thing that changes
*/