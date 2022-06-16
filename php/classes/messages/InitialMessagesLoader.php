<?php

declare(strict_types=1);

namespace PHP\Classes\Messages;

use PHP\Classes\Global\Validator;
use PHP\Classes\Global\PDOConnection;
use \PDO;
use \DateTime;

class InitialMessagesLoader {

  protected $oldest_message_id;
  protected $limit;
  protected $PDO_connection;
  public $query_result;
  public $result_len;
  public $return_data;

  public function setUp() {
    set_include_path($_SERVER['DOCUMENT_ROOT'] . '/chat');
    require_once 'vendor/autoload.php';
    $this -> additionalSetUp();
    return true;
  }

  public function additionalSetUp() {
    $this -> oldest_message_id = PHP_INT_MAX;
    $this -> limit = 50;

    $this -> return_data = new class {
      public $messages_data = [];
      public $oldest_message_id;
      public $latest_message_id;
      public $server_time;
    };
  }

  public function initAndRunPDO() {
    $this -> PDO_connection = new PDOConnection();

    $query = 
    "SELECT m.message_id, m.content, m.date, u.username
    FROM messages AS m, users AS u 
    WHERE m.user_id = u.id AND message_id < {$this -> oldest_message_id}
    ORDER BY message_id DESC LIMIT {$this -> limit}";

    if(!$PDO_stm = $this -> PDO_connection -> PDO -> query($query))
      Validator::failureExit('Zapytanie do bazy danych nie powiodło się.');

    $this -> query_result = $PDO_stm -> fetchAll(PDO::FETCH_ASSOC);
    $this -> result_len = count($this -> query_result);
    if($this -> result_len === 0) 
      Validator::failureExit("Nie ma już więcej wiadomości.");
  }

  public function formatAndSendMessages() {
    $this -> includeInitialSpecificData();
    
    $this -> return_data -> oldest_message_id = $this -> query_result[count($this -> query_result) - 1]['message_id'];
    
    for($i = 0; $i < $this -> result_len; $i++) {
      $buf_obj = new class {
        public $message_id;
        public $username;
        public $content;
        public $date;
      };
      $buf_obj -> message_id = $this -> query_result[$i]['message_id'];
      $buf_obj -> username = $this -> query_result[$i]['username'];
      $buf_obj -> content = $this -> query_result[$i]['content'];
      $buf_obj -> date =  $this -> query_result[$i]['date'];

      $this -> return_data -> messages_data[] = $buf_obj;
    }
  }

  public function echoOrReturn() {
    // messages are returned in order from the newest to the oldest
    return json_encode($this -> return_data);
  }

  protected function includeInitialSpecificData() {
    // send server time to adjust shown messages date
    $dt = new DateTime();
    $this -> return_data -> server_time = $dt -> format('U');
    $this -> return_data -> latest_message_id = $this -> query_result[0]['message_id'];
  }

}