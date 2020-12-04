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
        try {
          $servername = 'localhost';
          $dbname = 'moczarskibd';
          $username = $_SESSION['username'];
          $password = $_SESSION['password'];
          $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          echo "Połączono z bazą danych!";
        } catch(PDOException $e) {
          echo "Nie można połączyć z bazą danych: " . $e->getMessage();
        }
      ?>

      <form action="/login.php" method="POST">
        <label for="url">URL: </label><br>
        <input type="text" id="url" name="url"><br>
        <label for="podpis">Podpis: </label><br>
        <input type="text" id="podpis" name="podpis"><br>
        <input type="submit" value="Wyślij" name='send'><br>
      </form>

      <?php
        function pushImg() {
          try {
            global $conn, $username;
            $url = $_POST['url'];
            $text = $_POST['podpis'];
            $sql = "INSERT INTO images (url, text, username, points) VALUES ('$url', '$text', '$username', 0)";
            $conn->exec($sql);
          } catch(PDOException $e) {
            echo $sql. "<br>" .$e->getMessage();
          }
        }

        function renderImgs() {
          try {
            global $conn;
            $data = $conn->query("SELECT id, url, text, username, points FROM images")->fetchAll();
            foreach ($data as $elem) {
              echo "<img width='50%' src='".$elem["url"]."'>";
              echo "<div>".$elem["text"]."</div>";
              echo "<div>Dodane przez: ".$elem["username"]."</div>";
              echo "<div>Punkty: ".$elem["points"]."</div>";
              echo "<form action='/login.php' method='POST'>";
                echo "<input type='submit' value='( + )' name='+'>";
                echo "<input type='submit' value='( - )' name='-'><br>";
                echo '<input type="hidden" name="id" value="'.$elem['id'].'"/>';
                echo '<input type="hidden" name="points" value="'.$elem['points'].'"/>';
                echo '<input type="hidden" name="username" value="'.$elem['username'].'"/>';
              echo "</form>";
              echo "<form action='/login.php' method='POST'>";
                echo "<input type='submit' value='Usuń' name='delImg'>";
                echo '<input type="hidden" name="id" value="'.$elem['id'].'"/>';
                echo '<input type="hidden" name="username" value="'.$elem['username'].'"/>';
              echo "</form>";
            } 
          }catch (PDOException $e) {
            echo "Coś poszło nie tak... ".$e;
          }
        }

        if(isset($_POST['+']) || isset($_POST['-'])) {
          try {
            global $conn;
            $id = $_POST['id'];
            //$_SESSION['voted'] = null;
            $_SESSION['voted'][0] = 'test';
            if (isset($_SESSION['voted'])) {
              if(!(in_array($id, $_SESSION['voted']))) {
                if (isset($_POST['+'])) {
                  $newPoints = $_POST['points'] + 1;
                }
                if (isset($_POST['-'])) {
                  $newPoints = $_POST['points'] - 1;
                }
                $sql = "UPDATE images SET points=$newPoints WHERE id=$id";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $_SESSION['voted'][] = $id;
              } else {
                echo "Możesz oddać tylko jeden głos!";
              }
            }
          } catch (PDOException $e) {
            echo $sql. "<br>" .$e->getMessage();
          }
          renderImgs();
        } else {
          if(isset($_POST['delImg'])) {
            try {
              global $conn, $username;
              if ($username == $_POST['username']) {
                $id = $_POST['id'];
                $sql = "DELETE FROM images WHERE id=$id";
                $conn->exec($sql);
              } else {
                echo "Nie jesteś właścicielem!";
              }
            } catch(PDOException $e) {
              echo $sql. "<br>" .$e->getMessage();
            }
            renderImgs();
          } else {             
            if(isset($_POST['send'])) {
              if (empty($_POST["url"])) {
                echo "URL wymagany!";
              } elseif (!filter_var($_POST["url"], FILTER_VALIDATE_URL)) {
                echo "'".$_POST["url"]."' nie jest poprawnym URL!";
              } else {
                if (empty($_POST["podpis"])) {
                  echo "Podpis wymagany!";
                } else {
                  pushImg();
                  renderImgs();
                }
              }
            } else {
              renderImgs();
            }
          }
        }
      ?>
    </div>

  </body>
</html>