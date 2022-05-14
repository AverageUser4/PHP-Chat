const arr = document.getElementById('guest_data').innerHTML.split('%');
const guest_id = arr[0];
const guest_name = `Gość ${guest_id}`;
const guest_name_encoded = encodeURIComponent(guest_name);
const guest_token = arr[1];
const expires = new Date(Date.now() + 1000*60*60*24*365).toUTCString();

document.cookie = `guest_token=${guest_token}; expires=${expires}`;
