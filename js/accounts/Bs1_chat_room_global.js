"use strict";

/* get user info */
const user = JSON.parse(document.getElementById('user_data').innerHTML);
user.username_encoded = encodeURIComponent(user.username);

/* to be run every x seconds, checks every message and updates profile pictures
to match gender (gender and other data are requested only once when outputting message from new user)
not the best solution but user data doesn't need to be sent with every message
*/
let oldest_checked_message_div = null;
let latest_checked_message_div = null;
const msg_divs_to_be_changed = new Map();

function updateProfilePictureGender() {
  const message_divs = output_container.children;
  let loop_ended_at_div = message_divs[message_divs.length - 1];

  msg_divs_to_be_changed.forEach
  (
    function (value, key) {
      if(all_users_data.get(key).hasOwnProperty('gender')) {
        value.children[0].children[0].src = `../resources/${all_users_data.get(key).gender}.jpg`;
        msg_divs_to_be_changed.delete(key);
      }
    }
  );

  for(let i = 0; i < message_divs.length; i++) {
    if(message_divs[i] === oldest_checked_message_div) {
      loop_ended_at_div = message_divs[i];
      break;
    }
    updateProfilePictureGenderRepeating(message_divs, i);
  }
  
  for(let i = message_divs.length - 1; i >= 0; i--) {
    if(
        message_divs[i] === loop_ended_at_div
        || message_divs[i] === latest_checked_message_div
      )
      break;
    updateProfilePictureGenderRepeating(message_divs, i);
  }

  oldest_checked_message_div = message_divs[0];
  latest_checked_message_div = message_divs[message_divs.length - 1];
}

function updateProfilePictureGenderRepeating(message_divs, i) {
  let name = message_divs[i].children[1].children[0].innerHTML;
  if(all_users_data.get(name).hasOwnProperty('gender'))
    message_divs[i].children[0].children[0].src = `../resources/${all_users_data.get(name).gender}.jpg`;
  else if(!msg_divs_to_be_changed.has(name))
    msg_divs_to_be_changed.set(name, message_divs[i]);
}