<?php

declare(strict_types=1);

namespace PHP\Messages;

use PHP\Global\Validator;
use PHP\Global\PDOConnection;
use \PDO;

class MessageSender {

  private $user_id;
  private $message;
  private $PDO_connection;

  public function initialSetUp() {
    set_include_path($_SERVER['DOCUMENT_ROOT'] . '/chat');
    require_once 'vendor/autoload.php';

    session_start();
    if(!isset($_SESSION['id']))
      Validator::failureExit('Użytkownik nie jest zalogowany.');
    $this -> user_id = $_SESSION['id'];
    session_commit();

    if(!isset($_GET['message']))
      Validator::failureExit('No message provided.');

    $this -> message = Validator::customEntities($_GET['message']);
    if(!Validator::validMessage($this -> message))
      Validator::failureExit('There is a problem with provided message.');

    return true;
  }

  public function initAndRunPDO() {
    $this -> PDO_connection = new PDOConnection();
    $query_string = 
    "INSERT INTO messages 
    VALUES (null, " . $this -> user_id . ", :message, NOW())";

    $PDO_stm = $this -> PDO_connection -> PDO -> prepare($query_string);
    $PDO_stm -> bindParam(':message', $this -> message, PDO::PARAM_STR);
    if(!$PDO_stm -> execute())
      Validator::failureExit('Database query has not succeeded.');
  }

}