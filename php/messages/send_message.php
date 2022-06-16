<?php

declare(strict_types=1);

set_include_path($_SERVER['DOCUMENT_ROOT'] . '/chat');
require 'vendor/autoload.php';

use PHP\Classes\Messages\MessageSender;

// for testing
// session_start();
// $_SESSION['id'] = 2;
// session_commit();
// $_GET['message'] = 'abcd';

$message_sender = new MessageSender();
$message_sender -> send();

echo '1';
