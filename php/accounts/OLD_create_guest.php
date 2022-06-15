<?php

session_start();
if(isset($_SESSION['id'])) {
  header('Location: ../../html_or_php/chat_room.php');
  exit();
}
session_commit();

set_include_path($_SERVER['DOCUMENT_ROOT'] . '/chat/php');

if(!isset($_SERVER['REMOTE_ADDR']) 
  || !filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP)
  ) $ip = 'null';
else $ip = $_SERVER['REMOTE_ADDR'];

require_once 'accounts/reusable.php';
$success = insert_new_user($ip);

if(!$success[0]) {
  session_start();
  $_SESSION['sww_err'] = $success[1];
  session_commit();
  header("Location: ../../html_or_php/something_went_wrong.php");
  exit();
}

header('Location: verify_user.php');