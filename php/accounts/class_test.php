<?php

declare(strict_types=1);

use PHP\Accounts\GuestCreator;
use PHP\Accounts\UserCreator;
use PHP\Accounts\ActiveStateChanger;

set_include_path($_SERVER['DOCUMENT_ROOT'] . '\chat');
require_once 'vendor/autoload.php';

$asc = new ActiveStateChanger();
$asc -> switchUserActive(3, 'inactive');