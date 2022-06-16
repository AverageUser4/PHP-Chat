<?php

declare(strict_types=1);

namespace PHP\Classes\Accounts;

use PHP\Classes\Global\PDOConnection;
use PHP\Classes\Global\Validator;
use \PDO;

class UserInfoRetriever {

  private $PDO_connection;

  public function getJSONInfo(string $username) {
    $this -> PDO_connection = new PDOConnection();
    $query = 
    'SELECT account_type, gender, color
    FROM users 
    WHERE username = :username';
    
    $PDO_stm = $this -> PDO_connection -> PDO -> prepare($query);
    $PDO_stm -> bindParam(':username', $username, PDO::PARAM_STR);
    $PDO_stm -> execute();
    $result = $PDO_stm -> fetch(PDO::FETCH_ASSOC);
    if(!$result)
      Validator::failureExit('W bazie danych nie ma gościa o podanym ID.');
    
    $json_object = new class(
        $result['account_type'],
        $result['gender'],
        $result['color']
      ) 
      {
      public function __construct(
        public $account_type,
        public $gender,
        public $color
      ) {;}
    };
    
    echo json_encode($json_object);
  }

}