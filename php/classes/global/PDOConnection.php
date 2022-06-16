<?php

declare(strict_types=1);

namespace PHP\Classes\Global;

use \PDO;
use \PDOException;

class PDOConnection {

  protected $data_source_name;
  public $PDO;

  public function __construct
  (
    protected $db_engine = 'mysql',
    protected $db_host = 'localhost',
    protected $db_name = 'chat',
    protected $db_username = 'root',
    protected $db_password = '',
    protected $options_array = [PDO::ATTR_PERSISTENT => true]
  ) {
    $this -> data_source_name = "$db_engine:host=$db_host;dbname=$db_name";
    $this -> createPDOInstance();
  }

  protected function createPDOInstance() {
    try {
      $this -> PDO = new PDO(
        $this -> data_source_name,
        $this -> db_username,
        $this -> db_password,
        $this -> options_array
      );
    } catch(PDOException $e) { exit("<pre>$e</pre>"); }
  }

}