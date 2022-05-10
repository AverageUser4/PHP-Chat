<?php

try {
  return new PDO('mysql:host=localhost;dbname=chat', 'root', '', [PDO::ATTR_PERSISTENT => true]);
}catch(Exception $e) { return "Nie udało się zainicjalizować połączenia z bazą danych."; }