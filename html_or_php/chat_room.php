<?php
set_time_limit(5);
session_start();
if(!isset($_SESSION['id'])) {
  header('Location: ../index.php');
  exit();
}
$id = $_SESSION['id'];
$username = $_SESSION['username'];
$account_type = $_SESSION['account_type'];
$gender = $_SESSION['gender'];
$color = $_SESSION['color'];
session_commit();
?>

<!DOCTYPE html>

<html lang="pl">

<head>

  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Super Chat</title>
  <link rel="stylesheet" href="../css/global.css">
  <link rel="stylesheet" href="../css/main.css">

</head>

<body>

  <div id="mainWrapper">
    
      <div id="leftWrapper">
        <div id="outputContainer" class="genericContainer"></div>
    
        <div id="inputWrapper">
            <input id="textInput" type="text"><input id="sendButton" type="button" value="Wyślij">
        </div>
      </div>

      <div id="rightWrapper">

        <div id="activeUsersContainer" class="genericContainer">
          <h1>Aktywni użyktownicy</h1>
          <ul id="activeUsersList">
            <?php echo "<li>$username</li>"; ?>
          </ul>
        </div>

        <div id="optionsContainer" class="genericContainer">
          <button id="colorOpenButton">Zmień kolor zdjęcia profilowego</button>
        </div>

        <div id="loginOrRegisterContainer" class="genericContainer">
          <?php if($account_type === 'guest'): ?>
          <div><a href="register.php">Zarejestruj się</a></div>
          <div><a href="login.php">Zaloguj się</a></div>
          <?php else: ?>
          <div><a href="../php/accounts/logout.php">Wyloguj się</a></div>
          <?php endif; ?>
        </div>

      </div>

      <div id="colorPickerContainer">

        <div id="colorContainer">
          <img src="../resources/<?=$gender?>.jpg">
          <div id="color"></div>
        </div>

        <div id="slidersContainer">
          <input id="r" name="r" type="range" min="0" max="255" value="255">
          <input id="g" name="g" type="range" min="0" max="255" value="255">
          <input id="b" name="b" type="range" min="0" max="255" value="255">
          <input id="a" name="a" type="range" min="0" max="1" value="0.5" step="0.1">
        </div>

        <div id="sliderNamesContainer">
          <p>Red</p>
          <p>Green</p>
          <p>Blue</p>
          <p>Alpha</p>
        </div>

        <div id="valuesContainer">
          <p id="rVal">R: 255</p>
          <p id="gVal">G: 255</p>
          <p id="bVal">B: 255</p>
          <p id="aVal">A: 0.5</p>
        </div>

        <div id="randomColorButton">Losuj</div>
        <div id="applyButton">Zapisz</div>

        <button id="colorCloseButton">X</button>

      </div>

  </div>

  <style id="usersPicturesColors"></style>
  
  <input id="honeypot" type="text" style="display:none">
  <a href="../php/honeypot/honeypot.php" style="display:none"
  rel="nofollow">BAN ME(ACCESSING THIS LINK WILL BAN YOU)</a>


  <?php 

    set_include_path($_SERVER['DOCUMENT_ROOT'] . '/chat/php');

    require_once 'messages/load_old_messages.php';
    echo "<template id='old_mes_data'>$messages_string</template>";
    
    echo
    '
    <template id="user_data">
      {
        "id":'.$id.',
        "username":"'.$username.'",
        "account_type":"'.$account_type.'",
        "gender":"'.$gender.'",
        "color":"'.$color.'"
      }
    </template>
    ';

  ?>

  <script defer src="../js/global/s1_global.js"></script>
  <script defer src="../js/global/s3_validate.js"></script>

  <script defer src="../js/accounts/Bs1_chat_room_global.js"></script>
  <script defer src="../js/accounts/Bs2_info_and_colors.js"></script>

  <script defer src="../js/messages/s1_messages_global.js"></script>
  <script defer src="../js/messages/s2_load_old_messages.js"></script>
  <script defer src="../js/messages/s3_send_message.js"></script>
  <script defer src="../js/messages/s4_check_for_updates.js"></script>
  
  <script defer src="../js/run_when_loaded.js"></script>
  
</body>

</html>