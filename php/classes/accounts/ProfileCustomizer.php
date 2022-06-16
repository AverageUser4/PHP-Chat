<?php

declare(strict_types=1);

namespace PHP\Classes\Accounts;

use PHP\Classes\Global\PDOConnection;
use PHP\Classes\Global\Validator;

class ProfileCustomizer {

  private $PDO_connection;
  private $user_id;
  private $color;

  public function customize() {
    $this -> setUp();
    $this -> doPDOStuff();
    return true;
  }

  private function setUp() {
    session_start();
    if(!isset($_SESSION['id']))
      Validator::failureExit('Użytkownik nie jest zalogowany.');
    $this -> user_id = $_SESSION['id'];
    session_commit();
    
    if(!isset($_GET['color']))
      Validator::failureExit('Nie wysłano koloru.');
    if(!Validator::validColor($_GET['color']))
      Validator::failureExit('Podany kolor jest niepoprawny.');

    $this -> color = $_GET['color'];
  }

  private function doPDOStuff() {
    $this -> PDO_connection = new PDOConnection();
    $query = "UPDATE users SET color = :color WHERE id = {$this -> user_id}";
    $PDO_stm = $this -> PDO_connection -> PDO -> prepare($query);
    $PDO_stm -> bindParam(':color', $this -> color);
    if(!$PDO_stm -> execute())
      Validator::failureExit('Nie udało się dodać koloru do bazy danych.');
  }

}



