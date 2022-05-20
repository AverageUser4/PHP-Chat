<?php 
header("Content-Type: text/event-stream");
header('Cache-Control: no-cache');

set_include_path($_SERVER['DOCUMENT_ROOT'] . '/chat/php');
set_time_limit(3600);
require_once 'global/validate.php';

function sse_failure($e) {
  global $custom_error;
  $custom_error = $e;
  exit();
}

function sse_shutdownHandler() {
  if(connection_aborted())
    return;

  global $custom_error;
  global $latest_id;
  echo "event: custom_error\n", "data: $latest_id\n";

  if($e = error_get_last()) {
    if(str_contains($e['message'], 'Maximum execution time'))
      echo "id: timeout\n\n";
    else
      echo "id: unknown\n\n";
    return;
  }

  if(isset($custom_error))
    echo "id: $custom_error\n\n";
  else
    echo "id: unexpected\n\n";
}
register_shutdown_function('sse_shutdownHandler');


//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////
//////////////////////////////////////////////

if(!get_exists(['latest', 'user']) || !valid_int($_GET['latest']))
  sse_failure('wrong_data');

$latest_id = $_GET['latest'];
$user = $_GET['user'];
$custom_error = null;


require_once "global/pdo_connect.php";
if(!$PDO instanceof PDO)
  sse_failure('db_connect_fail');

$PDO_Statement = $PDO -> prepare("SELECT * FROM messages WHERE
  id > :id AND NOT nickname = :user");
$PDO_Statement -> bindParam(':user', $user, PDO::PARAM_STR);

while (1) {
  /* check database every 3 seconds, if there are messages
  that were sent by someone else than the user, send them to user */
  if(connection_aborted()) break;

  $PDO_Statement -> bindParam(':id', $latest_id, PDO::PARAM_INT);
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