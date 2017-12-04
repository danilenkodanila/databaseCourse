<!doctype html>
<html class="no-js" lang="ru" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Поиск читателя</title>
    <link rel="stylesheet" href="css/foundation.css">
    <link rel="stylesheet" href="css/app.css">
    <link rel="stylesheet" href="style.css">
    <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
  </head>
  <body>
    <header id="page-header">
      <div class="row">
        <div class="small-12 columns">
          <h1 class="text-center"><a class="icon-link" href="index.html">Библиотека</a></h1>
        </div>
      </div>
    </header>

<form action="deleteReader.php" method="post">
<div class="grid-x">
  <div class="cell small-4 small-offset-2 medium-2 medium-offset-3 large-2 large-offset-3">
     <input class="name" name="id" placeholder="id читателя" value="" aria-describedby="name-format">
  </div>
  <div class="cell small-4 small-offset-0 medium-2 medium-offset-2 large-2 large-offset-2">
    <button class="button-search" type="submit" value="Submit">Удалить</button>
  </div>
</div>
</form>





<?php
  function noValue($outputText){
    echo'<div class="grid-x">
           <div class="small-3 large-3 medium-3 cell"></div>
           <div class="small-6 large-6 medium-6 cell et">',$outputText,'</div>
           <div class="small-3 large-3 medium-3 cell"></div>
         </div>';
  }
  function insertSelect($array, $sql){ 
    $user = "admin";
    $pass = "76543210";
    try {
      $dbh = new PDO('mysql:host=localhost;dbname=library;charset=utf8', $user, $pass);
    } catch (PDOException $e) {
        die('Подключение не удалось: ' . $e->getMessage());
    }
    $sth = $dbh->prepare($sql);
    $sth->execute($array);
    $result = $sth->fetchAll();
    $dbh = null;
    return $result;
  }

  $user = "admin";
  $pass = "76543210";
  try {
    $dbh = new PDO('mysql:host=localhost;dbname=library;charset=utf8', $user, $pass);
  } catch (PDOException $e) {
      die('Подключение не удалось: ' . $e->getMessage());
  }

  if (!empty($_POST)){
    $id = $_POST['id'];

    if ($id <> ""){
      $sql = "SELECT readers.id, readers.name, readers.email, readers.phone, count(issue.id_reader) as issueCount FROM readers LEFT JOIN issue ON readers.id = issue.id_reader WHERE readers.id = ? AND date_e IS NULL group by readers.id";
      $sth = $dbh->prepare($sql);
      $sth->execute(array($id));
      $result = $sth->fetchAll();
      if ( empty($result) || $result[0]["issueCount"] == 0){
        $sql = "DELETE FROM issue WHERE id_reader = '$id'";
        $dbh->query($sql);
        $sql = "DELETE FROM readers WHERE id = '$id' LIMIT 1";
        $dbh->query($sql);
        noValue("Читатель удален");
      } else {
        noValue("Читатель не может быть удален. Читатель не сдал все книги");
      }
    } else {
      noValue("Введите id читателя");
    }

  }

//UPDATE issue SET date_e='2017-12-08' WHERE id_reader = 8 AND id_book= 2 LIMIT 1

?>
</div>
                   
  <div class="footer">
  </div>
    
    

    <script src="js/vendor/jquery.js"></script>
    <script src="js/vendor/what-input.js"></script>
    <script src="js/vendor/foundation.js"></script>
    <script src="js/app.js"></script>
    <script type="text/javascript">
    $(document).foundation();
    </script>
  </body>
</html>
