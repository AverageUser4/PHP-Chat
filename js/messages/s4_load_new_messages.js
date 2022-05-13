"use strict";

/* wait for new messages and append them to output wrapper */

const myInterval = setInterval(isVarDefined, 1000);
let event_source;
let failure_count = 0;
let connection_open = false;
let failed_connections = 0;

function isVarDefined() {
  // wait for latest_message_id to get defined
  // connect or try to reconnect after error
  if(latest_message_id === undefined)
    return;

  clearInterval(myInterval);

  event_source = new EventSource
  (`php/messages/load_new_messages.php?latest=${latest_message_id}&user=user12345`);
  event_source.addEventListener('new_msg', appendNewMessages);
  event_source.addEventListener('custom_error', handleCustomError);
  //event_source.addEventListener('timeout', (event) => { latest_message_id = event.data; event_source.close(); isVarDefined(); });
  //error jest wysyłany kiedy nie uda się połączyć z serwerem lub skrypt zostanie przerwany
  //bez użycia event_source.close()
  event_source.addEventListener('open', () => connection_open = true);
  event_source.addEventListener('error', unableToConnect);
  /*
  - nie udało się nawiązać połączenia (open nie został odpalony)
    * próbujemy się co jakiś czas znowu połączyć
  - połączenie nawiązane, problem z danymi, bazą itd
    * jeżeli dane są niepoprawne nie ma sensu próbować się dalej
    łączyć z takimi samymi danymi
  - czas trwania skryptu przekroczył godzinę    
  */
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
    

  failure_count++;
  console.log(event);
  event_source.close();

  if(failure_count >= 10) {
    alert(event.data);
    return;
  }

  setTimeout(isVarDefined, 10000);
}

function unableToConnect() {
  if(connection_open) {
    connection_open = false;
    return;
  }

  if(++failed_connections >= 3) {
    event_source.close();
    failed_connections = 0;
    setTimeout(isVarDefined, 600);
    alert("Nie udało się nawiązać połączenia z serwerem.");
  }
}

/*
- timeout, unknown, unexpected, db_connect_fail: 
  * spróbuj łączyć się natychmiast
  * po kilku nieudanych próbach odczekaj 10 minut
  
- wrong_data: nie podejmuj dalszych prób połączenia
*/
