<?php

declare(strict_types=1);

namespace PHP\Messages;

use \PDO;

class UpdateChecker {

  private $user_id;
  private $latest_message_id;

  private $PDO_connection;
  private $new_messages_query;
  private $active_users_query;
  private $PDO_stm_new_messages;
  private $PDO_stm_active_users;
  private $check_active_counter;
  

  public function loop() {
    while(1) {;}
  }

  public function initialSetUp() {
    header("Content-Type: text/event-stream");
    header('Cache-Control: no-cache');

    register_shutdown_function([__CLASS__, 'shutdownHandler'], $this);

    session_start();
    if(!isset($_SESSION['id'])) {
      header('Location: ../../index.php');
      exit();
    }
    $this -> user_id = $_SESSION['id'];
    session_commit();

    if(!isset($_GET['latest']) || filter_var($_GET['latest'], FILTER_VALIDATE_INT) === false)
      exit('wrong_data');
    $this -> latest_message_id = $_GET['latest'];

    set_time_limit(3600);
    set_include_path($_SERVER['DOCUMENT_ROOT'] . '/chat');
    require_once 'vendor/autoload.php';

    return true;
  }

  public function setUpDBConnection($new_pdo_connection_obj) {
    $this -> PDO_connection = $new_pdo_connection_obj;

    $this -> new_messages_query = 
    "SELECT m.message_id, m.content, m.date, u.username
    FROM messages AS m, users AS u 
    WHERE m.user_id = u.id 
    AND m.message_id > :latest_id
    AND NOT m.user_id = :user_id";

    $this -> active_users_query =
    "SELECT username FROM users WHERE active = 1 ORDER BY username";

    $this -> PDO_stm_new_messages = $this -> PDO_connection -> PDO -> prepare($this -> new_messages_query);
    $this -> PDO_stm_new_messages -> bindParam(':user_id', $this -> user_id, PDO::PARAM_STR);

    $this -> PDO_stm_active_users = $this -> PDO_connection -> PDO -> prepare($this -> active_users_query);
    $this -> check_active_counter = 0;
    
    $this -> PDO_connection -> PDO -> query("UPDATE users SET active = 1 WHERE id = " . $this -> user_id);
  }

  public function runLoop() {   
    while (1) {
      /* check database every 3 seconds, if there are messages
      that were sent by someone else than the user, send them to user */
      if(connection_aborted()) break;

      $this -> checkActive();
      $this -> checkNewMessages();

      // flush is needed because we are in a loop
      while (ob_get_level() > 0)
        ob_end_flush();
      flush();

      sleep(3);
    }
  }

  public function checkActive() {
    if($this -> check_active_counter === 0) {
      $this -> check_active_counter = 4;

      $this -> PDO_stm_active_users -> execute();
      $result = $this -> PDO_stm_active_users -> fetchAll(PDO::FETCH_NUM);
      
      if($result) {
        $data = json_encode($result);
        echo "event: active_update\n", "data: $data\n\n";
      }
    }
    $this -> check_active_counter--;
  }

  public function checkNewMessages() {
    $this -> PDO_stm_new_messages -> 
    bindParam(':latest_id', $this -> latest_message_id, PDO::PARAM_INT);
    $this -> PDO_stm_new_messages -> execute();
  
    $result = $this -> PDO_stm_new_messages -> fetchAll(PDO::FETCH_ASSOC);
    $len = count($result);

    if($len) {
      $this -> latest_message_id = $result[$len - 1]['message_id'];
      $message = '';

      for($i = 0; $i < $len; $i++) {
        $message .= $result[$i]['username'] . '%';
        $message .= $result[$i]['content'] . '%';
        $message .= $result[$i]['date'];
        if($i != $len - 1)
          $message .= '%';
      }
      echo "event: new_msg\n", "data: $message\n\n";
    }
    else
      echo "event: ping\n", "data: 1\n\n";
  }

  public static function shutdownHandler($uc_obj) {
    if(isset($uc_obj -> PDO_connection))
      $uc_obj -> PDO_connection -> PDO -> query("UPDATE users SET active = 0 WHERE id = " . $uc_obj -> user_id);

    if(connection_aborted())
      return;
  
    echo "event: custom_error\n", "data: " . $uc_obj -> latest_message_id ?? PHP_INT_MAX . "\n";
  
    if($e = error_get_last()) {
      if(str_contains($e['message'], 'Maximum execution time'))
        echo "id: timeout\n\n";
      else
        echo "id: unknown\n\n";
      return;
    }

    echo "id: unexpected\n\n";
  }

}