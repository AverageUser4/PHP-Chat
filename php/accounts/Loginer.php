<?php

declare(strict_types=1);

namespace PHP\Accounts;

use PHP\Global\PDOConnection;
use PHP\Accounts\ActiveStateChanger;
use \PDO;

class Loginer {

  private $PDO_connection;

  public function login(
    $user_id,
    $username,
    $account_type,
    $gender,
    $color
  ) {
    session_start();
    $_SESSION['id'] = $user_id;
    $_SESSION['username'] = $username;
    $_SESSION['account_type'] = $account_type;
    $_SESSION['gender'] = $gender;
    $_SESSION['color'] = $color;
    session_commit();

    $active_state_changer = new ActiveStateChanger();
    $active_state_changer -> switchUserActive($user_id, 'active');
  }

  public function loginWithAccessToken() {
    $this -> PDO_connection = new PDOConnection();

    $query = 
    "SELECT id, username, account_type, gender, color
    FROM users WHERE access_token = :token";

    $PDO_stm = $this -> PDO_connection -> PDO -> prepare($query);
    $PDO_stm -> bindParam(':token', $_COOKIE['access_token'], PDO::PARAM_STR);
    $PDO_stm -> execute();
    $result = $PDO_stm -> fetch(PDO::FETCH_ASSOC);

    if(!$result)
      return false;

    $this -> login(
      $result['id'],
      $result['username'],
      $result['account_type'],
      $result['gender'],
      $result['color']
    );

    return true;
  }

}
