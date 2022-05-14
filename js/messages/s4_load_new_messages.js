"use strict";

/* wait for new messages and append them to output wrapper */

const myInterval = setInterval(isVarDefined, 1000);
let event_source;
let failure_count = 0;
let connection_open = false;

function isVarDefined() {
  // wait for latest_message_id to get defined
  // connect or try to reconnect after error
  if(latest_message_id === undefined)
    return;

  clearInterval(myInterval);

  event_source = new EventSource
  (`php/messages/load_new_messages.php?latest=${latest_message_id}&user=${guest_name_encoded}`);
  event_source.addEventListener('new_msg', appendNewMessages);
  event_source.addEventListener('custom_error', handleCustomError);
  event_source.addEventListener('open', () => connection_open = true);
  event_source.addEventListener('error', unableToConnect);
}

function appendNewMessages(event) {
  const arr = event.data.split('%');

  if(arr.length < 3) {
    alert('Coś poszło nie tak.')
    return;
  }

  for(let i = 0; i < arr.length; i += 3) {
    const div = document.createElement('DIV');

    div.innerHTML =
    `<h3>${arr[i]}</h3>
    <h4>${parseMessageDate(arr[i + 2])}</h4>
    <p>${arr[i + 1]}</p>`;
    
    output_wrapper.appendChild(div);
  }
}

function handleCustomError(event) {
  if(event.data != '')
    latest_message_id = event.data;

  event_source.close();

  let wait_for = 3000;
    
  if(event.lastEventId === 'wrong_data') {
    console.log("Podane dane są nieprawidłowe.");
    return;
  }
  
  if(event.lastEventId === 'timeout') {
    failure_count = 0;
    wait_for = 0;
  }

  if(++failure_count >= 3) {
    failure_count = 0;
    console.log(event.lastEventId);
    setTimeout(isVarDefined, 300000);
    return;
  }

  setTimeout(isVarDefined, wait_for);
}

function unableToConnect() {
  if(connection_open) {
    connection_open = false;
    return;
  }

  setTimeout(isVarDefined, 300000);
  console.log("Nie udało się nawiązać połączenia z serwerem.");
}
