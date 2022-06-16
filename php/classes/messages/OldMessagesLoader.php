<?php

declare(strict_types=1);

namespace PHP\Classes\Messages;

use PHP\Classes\Global\Validator;

class OldMessagesLoader extends InitialMessagesLoader {

  public function additionalSetUp() {
    if(filter_var($_GET['oldest'], FILTER_VALIDATE_INT) === false)
      Validator::failureExit('Niepoprawny numer ID wiadomości.');

    $this -> oldest_message_id = $_GET['oldest'];
    $this -> limit = 150;

    $this -> return_data = new class {
      public $messages_data = [];
      public $oldest_message_id;
    };
  }

  public function echoOrReturn() {
    echo json_encode($this -> return_data);
  }

  public function includeInitialSpecificData() { return; }

}








