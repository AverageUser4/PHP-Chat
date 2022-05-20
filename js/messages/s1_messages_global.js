"use strict";

/* global variables */

const output_container = document.getElementById('outputContainer');
//which messages to take from database
let oldest_message_id;
let latest_message_id;
//adjust message's date to client's timezone
let client_server_time_difference = 0;
const all_users_color_data = new Map();


/* adjust message's date to client's timezone */
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

/* create element containing single message */
function createMessageElement(name, date, message, append) {
  if(all_users_color_data.has(name)) {
    console.log(all_users_color_data);
    actuallyCreateMessageElement(name, date, message, append);
    return;
  }
  sendRequest(
    function () {
      if(this.responseText.startsWith('error%')) {
        console.log(this.responseText.slice(6));
        all_users_color_data.set(name, '0,0,0,0');
      }
      all_users_color_data.set(name, this.responseText);
      actuallyCreateMessageElement(name, date, message, append);
    },
    'php/accounts/get_user_color.php', `username=${encodeURIComponent(name)}`
  );
}

function actuallyCreateMessageElement(name, date, message, append) {
  const class_name = 'usc' + all_users_color_data
  .get(name).replace('.', '').replace(',', '');

  const div = document.createElement('DIV');

  div.innerHTML =
  `
  <div class="imgContainer ${class_name}">
    <img draggable="false" src="resources/pp_male.jpg">
    <div class="imgColor"></div>
  </div>
  <div>
    <h3>${name}</h3>
    <h4>${date}</h4>
    <p>${message}</p>
  </div>
  `;

  if(append)
    output_container.appendChild(div);
  else
    output_container.insertBefore(div, output_container.firstChild);
}