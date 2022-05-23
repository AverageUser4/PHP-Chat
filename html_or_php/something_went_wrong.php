<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Super Chat - coś poszło nie tak</title>
</head>
<body>
  
  <h1 style="font-size:4rem;color:red;">Coś poszło nie tak :( Bardzo nam przykro.</h1>
  <?php
    session_start();
    if(isset($_SESSION['sww_err']))
      echo '<br>', $_SESSION['sww_err'];
  ?>

</body>
</html>