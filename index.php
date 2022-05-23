<?php
session_start();
if(isset($_SESSION['id']))
  header('Location: html_or_php/chat_room.php');
else if(isset($_COOKIE['access_token'])) {
  if($_COOKIE['access_token'] === 'null')
    header('Location: html_or_php/login.php');
  else
    header('Location: php/accounts/verify_user.php');
}
else
  header('Location: html_or_php/are_you_new.php');
?>