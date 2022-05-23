<?php

set_include_path($_SERVER['DOCUMENT_ROOT'] . '/chat/php');
require_once 'global/validate.php';


if(!isset($_SERVER['REMOTE_ADDR']) 
  || !filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP)
  ) $ip = 'null';
else $ip = $_SERVER['REMOTE_ADDR'];

require_once 'accounts/reusable.php';
$success = insert_new_user($ip);

if(!$success[0]) {
  header('Location: ../../html_or_php/something_went_wrong.php');
  exit();
}

session_start();
$_SESSION['id'] = $success[1];
header('Location: ../../html_or_php/chat_room.php');


