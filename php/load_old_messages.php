<?php

$oldest_id = PHP_INT_MAX;
$limit = 50;
$first_page_load = true;
if(isset($_GET['oldest'])
    && filter_input(INPUT_GET, 'oldest', FILTER_VALIDATE_INT)
  ) {
  $oldest_id = $_GET['oldest'];
  //150, 20 for testing
  $limit = 20;
  $first_page_load = false;
}

$PDO = require_once 'pdo_connect.php';

if(!$PDO instanceof PDO) {
  echo "error%Nie udało się zainicjalizować połączenia z bazą danych.";
  exit();
}

try {
  if(!$PDO_Statement = $PDO -> query("SELECT * FROM messages WHERE id < $oldest_id ORDER BY id DESC LIMIT $limit"))
    throw new Exception('error%Zapytanie do bazy danych nie powiodło się.');

  $result = $PDO_Statement -> fetchAll(PDO::FETCH_NUM);

  if(count($result) == 0) {
    echo "error%Nie ma już więcej wiadomości.";
    exit();
  }

  $messages_string = '';

  // send server time to adjust shown messages date
  if($first_page_load) {
    $dt = new DateTime();
    $messages_string .= $dt -> format('U');
    $messages_string .= '%';
  }

  // id of oldest message
  $messages_string .= $result[count($result) - 1][0] . '%';

  // id of latest message
  $messages_string .= $result[0][0] . '%';

  $len = count($result);

  for($i = 0; $i < $len; $i++) {
    $messages_string .= $result[$i][1] .= '%';
    $messages_string .= $result[$i][2] .= '%';
    $messages_string .= $result[$i][3];
    $i != $len - 1 ? $messages_string .= '%' : 0;
  }

  echo $messages_string;

} catch(Exception $e) { echo $e; }
