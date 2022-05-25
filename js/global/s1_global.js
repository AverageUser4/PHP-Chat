"use strict";

/* generic function for sending xmlhttp requests */

function sendRequest(callback, file, params = '', method = 'GET') {
  const xmlhttp = new XMLHttpRequest();

  xmlhttp.onload = callback;

  if(method === 'GET') {
    xmlhttp.open(method, file + '?' + params);
    xmlhttp.send();
  }
  else {
    xmlhttp.open(method, file);
    xmlhttp.send(params);
  }
}

/* make buttons lose focus when clicked */
const inp_buttons = document.querySelectorAll("input[type=button]");
const inp_submits = document.querySelectorAll("input[type=submit]");
const buttons = document.getElementsByTagName("BUTTON");

for(let x of inp_buttons)
  x.addEventListener('click', blurButton);
for(let x of inp_submits)
  x.addEventListener('click', blurButton);
for(let x of buttons)
  x.addEventListener('click', blurButton);

function blurButton(event) { event.currentTarget.blur(); }