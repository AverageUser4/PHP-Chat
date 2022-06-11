<?php

try {
  $PDO = new PDO('mysql:host=localhost;dbname=chat', 'root', '', [PDO::ATTR_PERSISTENT => true]);
}catch(Exception $e) { exit("Nie udało się zainicjalizować połączenia z bazą danych.<br>$e"); }