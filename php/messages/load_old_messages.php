<?php

function failure($error) { echo 'error%' . $error; exit(); }


$oldest_id = PHP_INT_MAX;
$limit = 50;
$first_page_load = true;

if(isset($_GET['oldest'])) {
  if(!filter_input(INPUT_GET, 'oldest', FILTER_VALIDATE_INT))
    failure('Niepoprawny numer ID wiadomości.');

  $oldest_id = $_GET['oldest'];
  //150, 20 for testing
  $limit = 20;
  $first_page_load = false;
}


$PDO = require_once '../global/pdo_connect.php';
if(!$PDO instanceof PDO)
  failure($PDO);

if(!$PDO_Statement = $PDO -> query("SELECT * FROM messages WHERE id < $oldest_id ORDER BY id DESC LIMIT $limit"))
  failure('Zapytanie do bazy danych nie powiodło się.');

$result = $PDO_Statement -> fetchAll(PDO::FETCH_NUM);
if(count($result) == 0) 
  failure("Nie ma już więcej wiadomości.");

$messages_string = '';

// send server time to adjust shown messages date, latest message's
// ID doesn't need to be updated in this script, so it's also sent only once
if($first_page_load) {
  $dt = new DateTime();
  $messages_string .= $dt -> format('U');
  $messages_string .= '%';
  // id of latest message
  $messages_string .= $result[0][0] . '%';
}

// id of oldest message
$messages_string .= $result[count($result) - 1][0] . '%';

$len = count($result);

// username, message and date
for($i = 0; $i < $len; $i++) {
  $messages_string .= $result[$i][1] .= '%';
  $messages_string .= $result[$i][2] .= '%';
  $messages_string .= $result[$i][3];
  $i != $len - 1 ? $messages_string .= '%' : 0;
}

echo $messages_string;