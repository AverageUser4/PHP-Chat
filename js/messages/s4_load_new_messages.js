"use strict";

/* wait for new messages and append them to output wrapper */

const myInterval = setInterval(isVarDefined, 1000);
let event_source;
let failure_count = 0;

function isVarDefined() {
  // wait for latest_message_id to get defined
  // connect or try to reconnect after error
  if(latest_message_id === undefined)
    return;

  clearInterval(myInterval);

  event_source = new EventSource
  (`php/messages/load_new_messages.php?latest=${latest_message_id}&user=user12345`);
  event_source.addEventListener('new_msg', appendNewMessages);
  event_source.addEventListener('msg_error', handleError);
  event_source.addEventListener('timeout', (event) => { latest_message_id = event.data; event_source.close(); isVarDefined(); console.log(event.data); });
  //event_source.addEventListener('error', () => console.log('Nie udało się nawiązać połączenia z serwerem.'));
  event_source.onmessage = () => console.log('penis');
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

function handleError(event) {
  failure_count++;
  console.log(event.data);
  event_source.close();

  if(failure_count >= 10) {
    alert(event.data);
    return;
  }

  setTimeout(isVarDefined, 10000);
}