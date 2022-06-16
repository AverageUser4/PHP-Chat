<?php

declare(strict_types=1);

set_include_path($_SERVER['DOCUMENT_ROOT'] . '/chat');
require_once 'vendor/autoload.php';

use PHP\Classes\Messages\InitialMessagesLoader;
use PHP\Classes\Messages\OldMessagesLoader;

$messages_loader = isset($_GET['oldest']) ? 
new OldMessagesLoader() : new InitialMessagesLoader();

$messages_loader -> setUp();
$messages_loader -> initAndRunPDO();
$messages_loader -> formatAndSendMessages();
$messages_string = $messages_loader -> echoOrReturn();