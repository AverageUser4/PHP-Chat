<?php

declare(strict_types=1);

use PHP\Classes\Accounts\Loginer;
use PHP\Classes\Global\Validator;

set_include_path($_SERVER['DOCUMENT_ROOT'] . '/chat');
require_once 'vendor/autoload.php';

if(!isset($_COOKIE['access_token'])) {
  $location = 'Location: http://' . $_SERVER['SERVER_NAME'] .
  '/chat/html_or_php/are_you_new.php';
  header($location);
  exit();
}
else if($_COOKIE['access_token'] === 'undef') {
  $location = 'Location: http://' . $_SERVER['SERVER_NAME'] .
  '/chat/html_or_php/login.php';
  header($location);
  exit();
}
else if(Validator::validAccessToken($_COOKIE['access_token'])) {
  $loginer = new Loginer;
  if($loginer -> loginWithAccessToken() === true) {
    $location = 'Location: http://' . $_SERVER['SERVER_NAME'] .
    '/chat/html_or_php/chat_room.php';
    header($location);
    exit();
  }
}

$location = 'Location: http://' . $_SERVER['SERVER_NAME'] .
'/chat/html_or_php/are_you_new.php';
setcookie('access_token', '', time() - 3600, '/');
header($location);
