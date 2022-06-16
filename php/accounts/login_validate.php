<?php

declare(strict_types=1);

use PHP\Classes\Accounts\LoginValidator;

set_include_path($_SERVER['DOCUMENT_ROOT'] . '/chat');
require_once 'vendor/autoload.php';

$login_validator = new LoginValidator();
if($login_validator -> validate())
  echo '1';

// login validator exits with error message otherwise