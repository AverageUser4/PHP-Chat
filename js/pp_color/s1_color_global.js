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
  const new_color = r_slider.value + ',' + g_slider.value +
  ',' + b_slider.value + ',' + a_slider.value;
  sendRequest(updateProfilePicture, 'php/accounts/profile_customize.php',
  `color=${new_color}`);
}

function updateProfilePicture() {
  if(this.responseText.startsWith('error%')) {
    alert(this.responseText.slice(6));
    return;
  }

  //sprawdzić czy pole użytkownika istnieje, jeśli tak zmienić tylko wartości

  const style = document.createElement('STYLE');
  style.innerHTML =
  `
  .${g1} {

  }
  `;

}