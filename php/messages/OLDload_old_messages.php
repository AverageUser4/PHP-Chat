<?php

set_include_path($_SERVER['DOCUMENT_ROOT'] . '/chat/php');
require_once 'global/validate.php';

$oldest_id = PHP_INT_MAX;
$limit = 50;
$first_page_load = true;

if(isset($_GET['oldest'])) {
  if(!filter_var($_GET['oldest'], FILTER_VALIDATE_INT))
    failure('Niepoprawny numer ID wiadomości.');

  $oldest_id = $_GET['oldest'];
  $limit = 150;
  $first_page_load = false;
}

require_once 'global/pdo_connect.php';
if(!$PDO instanceof PDO)
  failure($PDO);

$query_string = 
"SELECT m.message_id, m.content, m.date, u.username
FROM messages AS m, users AS u 
WHERE m.user_id = u.id AND message_id < $oldest_id
ORDER BY message_id DESC LIMIT $limit";

if(!$PDO_Statement = $PDO -> query($query_string))
  failure('Zapytanie do bazy danych nie powiodło się.');

$result = $PDO_Statement -> fetchAll(PDO::FETCH_ASSOC);
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
  $messages_string .= $result[0]['message_id'] . '%';
}

// id of oldest message
$messages_string .= $result[count($result) - 1]['message_id'] . '%';

$len = count($result);

// username, message and date
for($i = 0; $i < $len; $i++) {
  $messages_string .= $result[$i]['username'] .= '%';
  $messages_string .= $result[$i]['content'] .= '%';
  $messages_string .= $result[$i]['date'];
  $i != $len - 1 ? $messages_string .= '%' : 0;
}

if($first_page_load)
  return;

echo $messages_string;