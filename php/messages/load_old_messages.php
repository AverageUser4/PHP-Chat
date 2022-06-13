<?php

declare(strict_types=1);

use PHP\Messages\InitialMessagesLoader;
use PHP\Messages\OldMessagesLoader;

if(!isset($_GET['oldest'])) {
  $initial_messages_loader = new InitialMessagesLoader();
}
else {
  $old_messages_loader = new OldMessagesLoader();
}