<?php

if(
    !isset($_SERVER['REMOTE_ADDR'])
    || !filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP)
  )
  return;

$bot_ip = $_SERVER['REMOTE_ADDR'];

if(isset($_SERVER["HTTP_X_FORWARDED_FOR"])
  && filter_var($_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)
  )
  $bot_ip .= " {$_SERVER['HTTP_X_FORWARDED_FOR']}";

$path = $_SERVER['DOCUMENT_ROOT'] . '/chat/.htaccess';

$file = fopen($path, 'rb');
$file_content = fread($file, 65535);
fclose($file);

$ip_addresses = str_replace("\n</RequireAll>", '', substr($file_content, 48));

$new_content = "<RequireAll>\nRequire all granted\nRequire not ip $ip_addresses $bot_ip\n";
$new_content .= "</RequireAll>";

$file = fopen($path, 'wb');
fwrite($file, $new_content);
fclose($file);


//<RequireAll>
//Require all granted
//Require not ip 248.0.0.1
//</RequireAll>