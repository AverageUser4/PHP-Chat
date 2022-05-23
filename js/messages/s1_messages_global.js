"use strict";

/* global variables */

const output_container = document.getElementById('outputContainer');
//which messages to take from database
let oldest_message_id;
let latest_message_id;
//adjust message's date to client's timezone
let client_server_time_difference = 0;


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

  let class_name = createColorClass(name)
  if(!class_name)
    class_name = all_users_color_data.get(name);

  const div = document.createElement('DIV');

  div.innerHTML =
  `
  <div class="imgContainer">
    <img draggable="false" src="../resources/pp_male.jpg">
    <div class="imgColor ${class_name}"></div>
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