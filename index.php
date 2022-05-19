<!DOCTYPE html>

<html lang="pl">

<head>

  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Super Chat</title>
  <link rel="stylesheet" href="css/global.css">
  <link rel="stylesheet" href="css/main.css">

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
          <ul>
            <li>końik</li>
            <li>mentos18</li>
          </ul>
        </div>

        <div>
          <button id="colorOpenButton">Otwórz wybór</button>
        </div>

        <div id="loginOrRegisterContainer" class="genericContainer">
          <div><a href="php/register/register.php">Zarejestruj się</a></div>
          <div><a href="login.php">Zaloguj się</a></div>
        </div>

      </div>

      <div id="colorPickerContainer">

        <div id="color"></div>

        <div id="slidersContainer">
          <input id="r" name="r" type="range" min="0" max="255" value="255">
          <input id="g" name="g" type="range" min="0" max="255" value="255">
          <input id="b" name="b" type="range" min="0" max="255" value="255">
        </div>

        <button id="colorCloseButton">X</button>

      </div>

  </div>

  
  <input id="honeypot" type="text" style="display:none">
  <a href="php/honeypot/honeypot.php" style="display:none"
  rel="nofollow">BAN ME(ACCESSING THIS LINK WILL BAN YOU)</a>


  <?php 

    set_include_path($_SERVER['DOCUMENT_ROOT'] . '/chat/php');

    $messages = require_once 'messages/load_old_messages.php';
    echo "<template id='old_mes_data'>$messages</template>";

    require_once 'accounts/verify_guest.php';

    echo "<template id='guest_data'>$result[0]%$result[1]</template>";

  ?>


  <script defer src="js/global/s1_global.js"></script>
  <script defer src="js/global/s2_cookies.js"></script>

  <script defer src="js/messages/s1_messages_global.js"></script>
  <script defer src="js/messages/s2_load_old_messages.js"></script>
  <script defer src="js/messages/s3_send_message.js"></script>
  <script defer src="js/messages/s4_load_new_messages.js"></script>

  <script>

    const color_picker = document.getElementById('colorPickerContainer');
    const color_close_button = document.getElementById('colorCloseButton');
    const color_open_button = document.getElementById('colorOpenButton');
    color_open_button.addEventListener('click', openColorPicker);
    color_close_button.addEventListener('click', closeColorPicker);
    color_picker.addEventListener('click', (e) => e.stopPropagation());

    function openColorPicker(e) {
      e.stopPropagation();
      color_picker.style.display = 'block';
      window.addEventListener('click', closeColorPicker);
    }

    function closeColorPicker() {
      color_picker.style.display = 'none';
      window.removeEventListener('click', closeColorPicker);
    }

    const color = document.getElementById('color');
    const r_slider = document.getElementById('r');
    const g_slider = document.getElementById('g');
    const b_slider = document.getElementById('b');
    r_slider.addEventListener('input', updateColor);
    g_slider.addEventListener('input', updateColor);
    b_slider.addEventListener('input', updateColor);

    function updateColor() {
      const r = r_slider.value;
      const g = g_slider.value;
      const b = b_slider.value;
      color.style.backgroundColor = `rgb(${r},${g},${b})`;
    }

  </script>

</body>

</html>