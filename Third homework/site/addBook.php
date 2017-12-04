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

<form action="addBook.php" method="post">
<div class="grid-x">
  <div class="cell small-4 small-offset-2 medium-1 medium-offset-3 large-2 large-offset-2">
     <input class="name" name="author" placeholder="Автор" value="" aria-describedby="name-format">
  </div>
  <div class="cell small-4 medium-1 medium-offset-0 large-2 large-offset-0">
     <input class="name" name="title" placeholder="Название книги" value="" aria-describedby="name-format">
  </div>
  <div class="cell small-4 small-offset-2 medium-1 medium-offset-0 large-2 large-offset-0">
    <input class="name" name="genre" placeholder="Жанр" aria-describedby="exampleHelpTex" data-abide-ignore>
  </div>
  <!-- <div class="cell small-4 medium-1 medium-offset-0 large-1 large-offset-0">
    <input class="name" name="phone" placeholder="Цена" aria-describedby="exampleHelpTex" data-abide-ignore>
  </div> -->
  <div class="cell small-8 small-offset-2 medium-1 medium-offset-1 large-2 large-offset-0">
    <button class="button-search" type="submit" value="Submit">Добавить</button>
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
    $author = $_POST['author'];
    $title = $_POST['title'];
    $genre = $_POST['genre'];
    
    $array = [];
    $resultArray = [];

    if ($author <> "" && $title <> "" &&  $genre <> ""){
      $sql = "SELECT ( SELECT id FROM authors WHERE name = ?) as autId, (SELECT id FROM genre WHERE name = ?) as genId ";
        
      $result = insertSelect([$author,$genre],$sql);

      $id_author = $result[0][0];
      $id_genre = $result[0][1];

      

      if ($id_author == null){
        if ($id_author == null) {
          $sth = $dbh->prepare("INSERT INTO authors (name) VALUES(?)");
          $sth->execute(array($_POST['author']));
          $result = $sth->fetchAll();
        } 
        if ($id_genre == null) {
          $sth = $dbh->prepare("INSERT INTO genre (name) VALUES(?)");
          $sth->execute(array($_POST['genre']));
          $result = $sth->fetchAll();
        } 
        $result = insertSelect([$author,$genre],$sql);
        $id_author = $result[0][0];
        $id_genre = $result[0][1];

        $sql = "INSERT INTO books (id_author, title, id_genre, price, amount) VALUES (?,?,?,?,?)";
        $result = insertSelect([$id_author,$title,$id_genre,rand(1, 1000),1],$sql);
        noValue("Книга успешно добавлена");

      } else {
        if ($id_genre == null) {
          $sth = $dbh->prepare("INSERT INTO genre (name) VALUES(?)");
          $sth->execute(array($_POST['genre']));
          $result = $sth->fetchAll();
          $result = insertSelect([$author,$genre],$sql);
          $id_genre = $result[0][1];
        } 
        $sth1 = $dbh->prepare("SELECT id FROM books WHERE id_author = ? AND title = ?");
        $sth1->execute(array($id_author,$title));
        $result1 = $sth1->fetchAll();
        if (!empty($result1)){
          noValue("Такая книга уже есть в базе. Количество увеличино на 1");
          $sth1 = $dbh->prepare("UPDATE books SET amount=amount+1 WHERE id=?");
          $sth1->execute(array($result1[0][0]));
          $result1 = $sth1->fetchAll();
        } else {
          $sql = "INSERT INTO books (id_author, title, id_genre, price, amount) VALUES (?,?,?,?,?)";
          $result = insertSelect([$id_author,$title,$id_genre,rand(1, 1000),1],$sql);
          noValue("Книга успешно добавлена");
        }

      }

      


    } else {
      noValue("Введите все данные");
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
