<?php session_start(); ?>
<!doctype html>
<html lang="pl">
  <head>
    <title>PS8 - Zadanie 1</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="zad1.css">
    <script src="zad1.js"></script>
  </head>
  <body>

    <div id="content">
      <?php
        $servername = 'localhost';
        $dbname = 'moczarskibd';
        $username = 'admin';
        $password = 'admin';
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $data = $conn->query("SELECT id, url, text, username, points FROM images")->fetchAll();

        echo '<form action="/index.php" method="POST">';
          echo '<label for="login">Login: </label><br>';
          echo '<input type="text" id="login" name="login"><br>';
          echo '<label for="password">Hasło: </label><br>';
          echo '<input type="password" id="password" name="password"><br>';
          echo '<input type="submit" value="Zaloguj" name="check"><br>';
        echo'</form>';

        try {
          foreach ($data as $elem) {
            echo "<img width='50%' src='".$elem["url"]."'>";
            echo "<div>".$elem["text"]."</div>";
            echo "<div>Dodane przez: ".$elem["username"]."</div>";
            echo "<div>Punkty: ".$elem["points"]."</div>";
          } 
        } catch (PDOException $e) {
          echo "Coś poszło nie tak... ".$e;
        }

        if(isset($_POST['login']) && isset($_POST['password'])) {
          try {
            $username = $_POST['login'];
            $password = $_POST['password'];
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $_SESSION['username'] = $username;
            $_SESSION['password'] = $password;
            header("Location: login.php");
          } catch(PDOException $e) {
            echo "Nie można połączyć z bazą danych: " . $e->getMessage();
          }
        }
      ?>
    </div>

  </body>
</html>