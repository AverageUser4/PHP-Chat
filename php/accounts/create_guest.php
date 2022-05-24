<?php

set_include_path($_SERVER['DOCUMENT_ROOT'] . '/chat/php');

if(!isset($_SERVER['REMOTE_ADDR']) 
  || !filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP)
  ) $ip = 'null';
else $ip = $_SERVER['REMOTE_ADDR'];

require_once 'accounts/reusable.php';
$success = insert_new_user($ip);

session_start();

if(!$success[0]) {
  $_SESSION['sww_err'] = $success[1];
  header("Location: ../../html_or_php/something_went_wrong.php");
  exit();
}

header('Location: verify_user.php');