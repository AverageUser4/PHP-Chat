<?php

declare(strict_types=1);

use PHP\Global\PDOConnection;

require 'php/global/PDOConnection.php';

$PDOC = new PDOConnection();
var_dump($PDOC);