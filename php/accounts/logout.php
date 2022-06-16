<?php

declare(strict_types=1);

use PHP\Classes\Accounts\Logouter;

set_include_path($_SERVER['DOCUMENT_ROOT'] . '/chat');
require_once 'vendor/autoload.php';

$logouter = new Logouter();

$logouter -> logout();
$logouter -> redirectToWeThankYou();