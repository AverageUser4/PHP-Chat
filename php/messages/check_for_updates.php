<?php

declare(strict_types=1);

set_include_path($_SERVER['DOCUMENT_ROOT'] . '/chat');
require 'vendor/autoload.php';

use PHP\Classes\Messages\UpdateChecker;

// for testing
// session_start();
// $_SESSION['id'] = 2;
// session_commit();
// $_GET['latest'] = 0;

$update_checker = new UpdateChecker();
$update_checker -> setUpAndStartLoop();