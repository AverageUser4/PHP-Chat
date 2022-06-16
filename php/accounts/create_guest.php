<?php

declare(strict_types=1);

use PHP\Classes\Accounts\GuestCreator;

session_start();
if(isset($_SESSION['id'])) {
  $location = 'Location: http://' . $_SERVER['SERVER_NAME'] .
  '/chat/html_or_php/chat_room.php';
  header($location);
  exit();
}
session_commit();

set_include_path($_SERVER['DOCUMENT_ROOT'] . '/chat');
require_once 'vendor/autoload.php';

$guest_creator = new GuestCreator();
$success = $guest_creator -> insertNewGuest();

if(!$success[0]) {
  $location = 'Location: http://' . $_SERVER['SERVER_NAME'] .
  '/chat/html_or_php/something_went_wrong.php?msg=' . urlencode($success[1]);
  header($location);
  exit();
}

require_once 'check_user.php';