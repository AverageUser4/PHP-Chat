<?php

declare(strict_types=1);
namespace PHP\Classes\Accounts;

use PHP\Classes\Accounts\GuestCreator;
use \PDO;

class UserCreator extends GuestCreator {

  protected function checkEmailAndUsernameTaken() {
    $query = "SELECT id FROM users WHERE email = :email";
    $PDO_stm = $this -> PDO_connection -> PDO -> prepare($query);
    $PDO_stm -> bindParam(':email', $this -> email);
    $PDO_stm -> execute();
    $result = $PDO_stm -> fetch(PDO::FETCH_NUM);
    if($result)
      return [false, 'eE-mail zajęty.'];
  
    $query = "SELECT id FROM users WHERE username = :username";
    $PDO_stm = $this -> PDO_connection -> PDO -> prepare($query);
    $PDO_stm -> bindParam(':username', $this -> username);
    $PDO_stm -> execute();
    $result = $PDO_stm -> fetch(PDO::FETCH_NUM);
    if($result)
      return [false, 'uLogin zajęty.'];
  
    return [true, 1];
  }

  public function insertNewUser() {
    $this -> setUpPDOStatement();
    $this -> setRandomColorString();
  
    $test = $this -> checkEmailAndUsernameTaken();
    if(!$test[0])
      return[false, $test[1]];

    $this -> access_token = 'undef';
    $this -> hash = password_hash($this -> password, PASSWORD_DEFAULT);
      
    if(!$this -> first_PDO_stm -> execute())
      return [false, 'Nie udało się dodać użytkownika do bazy danych.'];

    setcookie('access_token', $this -> access_token, time() + 60*60*24*365, '/');
    $_COOKIE['access_token'] = $this -> access_token;
    return [true, true];
  }

  public function changeGuestToUser() {
    $test = $this -> checkEmailAndUsernameTaken();
    if(!$test[0])
      return [false, $test[1]];
      
    $this -> hash = password_hash($this -> password, PASSWORD_DEFAULT);

    $query =
    "UPDATE users SET 
    email = :email,
    username = :username,
    password = :hash,
    gender = :gender,
    account_type = 'user'
    WHERE id = :user_id";

    $PDO_stm = $this -> PDO_connection -> PDO -> prepare($query);
    $PDO_stm -> bindParam(':email', $this -> email);
    $PDO_stm -> bindParam(':username', $this -> username);
    $PDO_stm -> bindParam(':hash', $this -> hash);
    $PDO_stm -> bindParam(':gender', $this -> gender);
    $PDO_stm -> bindParam(':user_id', $this -> user_id);
    if(!$PDO_stm -> execute() || !$PDO_stm -> rowCount())
      return [false, 'Nie udało się zmienić gościa na użytkownika.'];

    session_start();
    $_SESSION['username'] = $this -> username;
    $_SESSION['gender'] = $this -> gender;
    $_SESSION['account_type'] = 'user';
    session_commit();
    setcookie('access_token', 'undef', time() + 60*60*24*365, '/');
    $_COOKIE['access_token'] = 'undef';
    // zrobić tu i może w paru innych miejscach pełne wylogowanie

    return [true, true];
  }

}








