"use strict";

const color_picker = document.getElementById('colorPickerContainer');
const color_close_button = document.getElementById('colorCloseButton');
const color_open_button = document.getElementById('colorOpenButton');
const random_color_button = document.getElementById('randomColorButton');
color_picker.addEventListener('click', (e) => e.stopPropagation());
color_close_button.addEventListener('click', closeColorPicker);
color_open_button.addEventListener('click', openColorPicker);
random_color_button.addEventListener('click', updateColorField);

const color = document.getElementById('color');
color.addEventListener('click', requestColorUpdate);
const apply = document.getElementById('applyButton');
apply.addEventListener('click', requestColorUpdate);
let current_color = 'cool value';

const all_users_data = new Map();
const style_container = document.getElementById('usersPicturesColors');

const r_slider = document.getElementById('r');
const g_slider = document.getElementById('g');
const b_slider = document.getElementById('b');
const a_slider = document.getElementById('a');
r_slider.addEventListener('input', updateColorField);
g_slider.addEventListener('input', updateColorField);
b_slider.addEventListener('input', updateColorField);
a_slider.addEventListener('input', updateColorField);

const r_val = document.getElementById('rVal');
const g_val = document.getElementById('gVal');
const b_val = document.getElementById('bVal');
const a_val = document.getElementById('aVal');

let new_color;


/* color picker */
updateColorField({currentTarget: random_color_button});

function openColorPicker(e) {
  e.stopPropagation();
  color_picker.style.display = 'block';
  window.addEventListener('click', closeColorPicker);
}

function closeColorPicker() {
  color_picker.style.display = 'none';
  window.removeEventListener('click', closeColorPicker);
}

function updateColorField(event) {
  if(event.currentTarget === random_color_button) {
    r_slider.value = Math.floor(Math.random() * 256);
    g_slider.value = Math.floor(Math.random() * 256);
    b_slider.value = Math.floor(Math.random() * 256);
    a_slider.value = Math.floor(Math.random() * 9 + 2) / 10;
  }
  const r = r_slider.value;
  const g = g_slider.value;
  const b = b_slider.value;
  const a = a_slider.value;
  color.style.backgroundColor = `rgba(${r},${g},${b},${a})`;
  r_val.innerHTML = `R: ${r}`;
  g_val.innerHTML = `G: ${g}`;
  b_val.innerHTML = `B: ${b}`;
  a_val.innerHTML = `A: ${a}`;
}

function requestColorUpdate() {
  if(a_slider.value == 0)
    new_color = '0,0,0,0';
  else {
    new_color = r_slider.value + ',' + g_slider.value +
    ',' + b_slider.value + ',' + a_slider.value;
  }

  if(new_color === current_color)
    return;
  current_color = new_color;

  sendRequest(updateProfilePictureColor, '../php/accounts/profile_customize.php',
  `color=${new_color}`);
}

function updateProfilePictureColor() {
  if(this.responseText.startsWith('error%')) {
    alert(this.responseText.slice(6));
    return;
  }
  if(!getInfoAndCreateColorClass(user.username))
    updateInfoAndClassColor(user.username, `{"color":"${current_color}"}`);

  closeColorPicker();
}


/* class for every user that we read message from */
function getInfoAndCreateColorClass(name) {
  if(all_users_data.has(name))
    return false;
  
  let class_name = 'u';

  for(let i = 0; i < name.length; i++)
    class_name += name.charCodeAt(i);

  style_container.innerHTML += 
  `.${class_name}{background:rgba(0,0,0,0);}`;

  all_users_data.set(name, { class_name: class_name });
  sendRequest
  (
    function () { updateInfoAndClassColor(name, this.responseText) },
    '../php/accounts/get_user_info.php',
    `username=${encodeURIComponent(name)}`
  );

  return class_name;
}

function updateInfoAndClassColor(name, response) {
  let color;
  if(response.startsWith('error%')) {
    console.log(response.slice(6));
    color = '0,0,0,0';
  }
  else {
    const json_obj = JSON.parse(response);
    color = json_obj.color;
    if(json_obj.hasOwnProperty('gender'))
      all_users_data.get(name).gender = json_obj.gender;
    if(json_obj.hasOwnProperty('account_type'))
      all_users_data.get(name).account_type = json_obj.account_type;
    all_users_data.get(name).color = json_obj.color;
  }

  const class_name = all_users_data.get(name).class_name;
  const s = style_container.innerHTML;
  const class_pos = s.indexOf(class_name);
  const semicolon_pos = s.indexOf('}', class_pos);

  style_container.innerHTML = 
  s.replace
  (
    s.slice(
      class_pos,
      semicolon_pos
    ),
    `${class_name}{background:rgba(${color})`
  )
}