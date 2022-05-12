<?php
header("Content-Type: text/event-stream");
header('Cache-Control: no-cache');

set_time_limit(3600);

function failure($error) { 
  echo "event: msg_error\n", "data: $error\n\n";
  exit();
}


if(!isset($_GET['latest']) || !filter_var($_GET['latest'], FILTER_VALIDATE_INT))
  failure('Niepoprawny numer ID najnowszej wiadomości.');

if(!isset($_GET['user']))
  failure('Nie podano nazwy użytkownika.');

$latest_id = $_GET['latest'];
$user = $_GET['user'];


function timeoutHandler() {
  if(connection_aborted())
    return;

  global $latest_id;

  echo "event: timeout\n", "data: $latest_id\n\n";
}
register_shutdown_function('timeoutHandler');


$PDO = require_once "../global/pdo_connect.php";
if(!$PDO instanceof PDO)
  failure($PDO);


while (1) {
  /* check database every 3 seconds, if there are messages
  that were sent by someone else than the user, send them to user */
  if(connection_aborted()) break;

  $PDO_Statement = $PDO -> prepare("SELECT * FROM messages WHERE
    id > :id AND NOT nickname = :user");

  $PDO_Statement -> bindParam(':id', $latest_id, PDO::PARAM_INT);
  $PDO_Statement -> bindParam(':user', $user, PDO::PARAM_STR);
  $PDO_Statement -> execute();

  $result = $PDO_Statement -> fetchAll(PDO::FETCH_NUM);
  $len = count($result);

  if($len) {
    $latest_id = $result[$len - 1][0];
    $message = '';

    for($i = 0; $i < $len; $i++) {
      $message .= $result[$i][1] . '%';
      $message .= $result[$i][2] . '%';
      $message .= $result[$i][3];
      if($i != $len - 1)
        $message .= '%';
    }
    echo "event: new_msg\n", "data: $message\n\n";
  }
  else
    echo "event: ping\n", "data: 1\n\n";

  // flush is needed because we are in a loop
  while (ob_get_level() > 0)
    ob_end_flush();
  flush();
  
  sleep(3);
}