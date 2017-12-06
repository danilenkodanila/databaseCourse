<!doctype html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Поиск книги</title>
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



<form action="get-book.php" method="post">
<div class="grid-x">
  <div class="small-12 medium-2 large-2 columns"></div>
  <div class="small-12 medium-2 large-2 columns"></div>
  <div class="small-12 medium-2 large-2 columns">
     <input class="name" name="id_reader" placeholder="id читателя" value="" aria-describedby="name-format">
  </div>
  <div class="small-12 medium-2 large-2 columns">
     <input class="name" name="id_book" placeholder="id книги" value="" aria-describedby="name-format">
  </div>
  
  <div class="small-12 medium-2 large-2 columns"></div>
</div>

<div class="grid-x">
  <div class="small-12 medium-4 large-4 columns"></div>
  <div class="small-12 medium-4 large-4 columns">
    <button class="button-search" type="submit" value="Submit">Выдать</button>
  </div>
  <div class="small-12 medium-4 large-4 columns"></div>
</div>
</form>

<?php

  function noValue($outputText){
    echo'<div class="grid-x">
           <div class="small-3 large-3 medium-3 columns"></div>
           <div class="small-6 large-6 medium-6 columns et">',$outputText,'</div>
           <div class="small-3 large-3 medium-3 columns"></div>
         </div>';
  }

  $user = "admin";
  $pass = "76543210";
  try {
    $dbh = new PDO('mysql:host=localhost;dbname=library;charset=utf8', $user, $pass);
  } catch (PDOException $e) {
      die('Подключение не удалось: ' . $e->getMessage());
  }

  if (!empty($_POST)){
    $id_reader = $_POST['id_reader'];
    $id_book = $_POST['id_book'];  
  
    //проверяем не пустые ли поля
    if ($id_reader <> "" && $id_book <> "") {
      
      $sth1 = $dbh->prepare("SELECT (SELECT amount FROM books WHERE id = ?) AS amount, (SELECT id FROM readers WHERE id = ?) AS id");
      $sth1->execute(array($id_book, $id_reader));
      $result1 = $sth1->fetchAll();
      
      // проверяем наличие книги
      if ($result1[0]["amount"] <> null && $result1[0]["amount"] <> "0" && $result1[0]["id"] <> null) {
        date_default_timezone_set('Asia/Vladivostok');
        $date = date('Y-m-d', time());

        $sql = $dbh->prepare("INSERT INTO issue (id_reader, id_book, date_s, date_e) VALUES ($id_reader, $id_book, '$date', NULL)");
        $sql->execute(array($id_reader, $id_book, '$date', NULL));

        $int = (int)$result1[0]["amount"];
        $int -= 1;
        $sql = $dbh->prepare("UPDATE books SET amount = $int WHERE id = $id_book");
        $sql->execute(array($int, $id_book));

        noValue("Книга успешно выдана");

      } else {
        noValue("Такой книги нет/Такого читателя нет/Книги нет в наличии/");
      }
    } else {
      noValue("Введите оба параметра");
    }

  }
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
