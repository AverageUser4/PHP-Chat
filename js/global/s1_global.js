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


