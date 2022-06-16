<?php

declare(strict_types=1);

use PHP\Classes\Accounts\RegisterValidator;

set_include_path($_SERVER['DOCUMENT_ROOT'] . '/chat');
require_once 'vendor/autoload.php';

$register_validator = new RegisterValidator();
if($register_validator -> createNewAccount())
  echo '1';
