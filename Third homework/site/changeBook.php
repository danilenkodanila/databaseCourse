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

<form action="changeBook.php" method="post">
<div class="grid-x">
  <div class="cell small-4 small-offset-2 medium-2 medium-offset-3 large-2 large-offset-3">
     <input class="name" name="id" placeholder="id книги" value="" aria-describedby="name-format">
  </div>
  <div class="cell small-4 small-offset-0 medium-2 medium-offset-2 large-2 large-offset-2">
    <button class="button-search" type="submit" value="Submit">Найти</button>
  </div>
  <input type="hidden" name="action" value="form1" />
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

  if(isset($_POST['action']) && $_POST['action'] == 'form1') {
    if (!empty($_POST)){
      $id_book = $_POST['id'];

      if ($id_book <> "" ){
        $sql = "SELECT books.id, authors.name as author, books.title, genre.name as genre, books.price, books.amount FROM books INNER JOIN genre ON books.id_genre = genre.id INNER JOIN authors ON books.id_author = authors.id WHERE books.id = ?";
        $result = insertSelect([$id_book],$sql);
        foreach($result as $row){
          echo '<form action="changeBook.php" method="post">
            <div class="grid-x">
              <div class="cell small-4 medium-2 small-offset-2 medium-offset-3 large-1 large-offset-3">
                Автор:
              </div>
              <div class="cell small-4 medium-4 medium-offset-0 large-2 large-offset-0">
                <input class="name" name="author" placeholder="id" value="';
          echo $row["author"];
          echo '" aria-describedby="name-format">
              </div>
              <div class="cell small-4 small-offset-2 medium-2 medium-offset-3 large-1 large-offset-0">
                <div class="pddng">Название:</div>
              </div>
              <div class="cell small-4 medium-4 medium-offset-0 large-2 large-offset-0">
                <input class="name" name="title" placeholder="id" value="';
          echo $row["title"];
          echo '" aria-describedby="name-format">
              </div>
              <div class="cell small-4 medium-4 medium-offset-0 large-1 large-offset-0">
              </div>
            </div>
            <div class="grid-x">
              <div class="cell small-4 medium-2 small-offset-2 medium-offset-3 large-1 large-offset-3">
                Жанр:
              </div>
              <div class="cell small-4 medium-4 medium-offset-0 large-2 large-offset-0">
                <input class="name" name="genre" placeholder="id" value="';
          echo $row["genre"];
          echo '" aria-describedby="name-format">
              </div>
              <div class="cell small-4 small-offset-2 medium-2 medium-offset-3 large-1 large-offset-0">
                <div class="pddng">Цена:</div>
              </div>
              <div class="cell small-4 medium-4 medium-offset-0 large-2 large-offset-0">
                <input class="name" name="price" placeholder="id" value="';
          echo $row["price"];
          echo '" aria-describedby="name-format">
              </div>
              <div class="cell small-4 medium-4 medium-offset-0 large-1 large-offset-0">
              </div>
            </div>
            <div class="grid-x">
              <div class="cell small-4 medium-2 small-offset-2 medium-offset-3 large-1 large-offset-3">
                Количество:
              </div>
              <div class="cell small-4 medium-4 medium-offset-0 large-2 large-offset-0">
                <input class="name" name="amount" placeholder="id" value="';
          echo $row["amount"];
          echo'" aria-describedby="name-format">
              </div>
              <div class="cell small-8 small-offset-2 medium-6 medium-offset-3 large-2 large-offset-1">
                <button class="button-search" type="submit" value="Submit">Сохранить</button>
              </div>
              <input type="hidden" name="action" value="form2"/>
              <input type="hidden" name="idBook" value="';
          echo $row["id"];
          echo '"/>
            </div>
          </form>';

        }


      } else {
        noValue("Введите id книги");
      }


    }
  } else if(isset($_POST['action']) && $_POST['action'] == 'form2') {
    if (!empty($_POST)){
      $author = $_POST['author'];
      $title = $_POST['title'];
      $genre = $_POST['genre'];
      $price = $_POST['price'];
      $amount = $_POST['amount'];
      $idBook = $_POST['idBook'];
      if ($price == ""){
        $price = 0;
      } 
      if ($amount == ""){
        $amount = 0;
      } 
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

            $sql = "UPDATE books SET id_author='$id_author', title='$title', id_genre='$id_genre', price='$price', amount='$amount' WHERE id='$idBook'";
            $dbh->query($sql);
            noValue("Книга успешно обновлена");

          } else {
            if ($id_genre == null) {
              $sth = $dbh->prepare("INSERT INTO genre (name) VALUES(?)");
              $sth->execute(array($_POST['genre']));
              $result = $sth->fetchAll();
              $result = insertSelect([$author,$genre],$sql);
              $id_genre = $result[0][1];
            } 
            $sth1 = $dbh->prepare("SELECT id FROM books WHERE id_author = ? AND title = ? AND id_genre = ?");
            $sth1->execute(array($id_author,$title,$id_genre));
            $result1 = $sth1->fetchAll();
            if (!empty($result1)){
              if ($result1[0]["id"] == $idBook) {
                $sql = "UPDATE books SET price='$price', amount='$amount' WHERE id='$idBook'";
                $dbh->query($sql);
                noValue("Книга успешно обновлена");

              } else {
                noValue("Такая книга уже есть в базе. Попробуйте снова");
              }
            } else {

              $sql = "UPDATE books SET id_author='$id_author', title='$title', id_genre='$id_genre', price='$price', amount='$amount' WHERE id='$idBook'";
              $dbh->query($sql);
              noValue("Книга успешно обновлена");
            }

          }
        } else {
          noValue("Вы не ввели все данные. Попробуйте снова");
        }
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
