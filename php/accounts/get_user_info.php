<?php

declare(strict_types=1);

use PHP\Classes\Global\Validator;
use PHP\Classes\Accounts\UserInfoRetriever;

set_include_path($_SERVER['DOCUMENT_ROOT'] . '/chat');
require_once 'vendor/autoload.php';

if(!isset($_GET['username']))
  Validator::failureExit('Nie podano nazwy użytkownika');

$username = $_GET['username'];

if(!Validator::validUsername($username) && !Validator::validGuestname($username))
  Validator::failureExit('Niepoprawna nazwa użytkownika.');

$user_info_retriever = new UserInfoRetriever();
echo $user_info_retriever -> getJSONInfo($username);