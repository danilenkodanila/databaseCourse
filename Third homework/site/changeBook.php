<!doctype html>
<html class="no-js" lang="ru" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Изменить книгу</title>
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
  include_once("ut.php");
  $dbh = connect();

  //Выбор между 1 формой и второй
  if(isset($_POST['action']) && $_POST['action'] == 'form1') {
    if (!empty($_POST)){
      $id_book = $_POST['id'];

      if ($id_book <> "" ){
        $sql = "SELECT books.id, authors.name as author, books.title, genre.name as genre, books.price, books.amount FROM books INNER JOIN genre ON books.id_genre = genre.id INNER JOIN authors ON books.id_author = authors.id WHERE books.id = ?";
        $result = executeRequest($sql,[$id_book]);
        if (!empty($result)){
          foreach($result as $row){
            echo '<form action="changeBook.php" method="post">
              <div class="grid-x">
                <div class="cell small-4 medium-2 small-offset-2 medium-offset-3 large-1 large-offset-3">
                  Автор:
                </div>
                <div class="cell small-4 medium-4 medium-offset-0 large-2 large-offset-0">
                  <input class="name" name="author" placeholder="Автор" value="';
            echo $row["author"];
            echo '" aria-describedby="name-format">
                </div>
                <div class="cell small-4 small-offset-2 medium-2 medium-offset-3 large-1 large-offset-0">
                  <div class="pddng">Название:</div>
                </div>
                <div class="cell small-4 medium-4 medium-offset-0 large-2 large-offset-0">
                  <input class="name" name="title" placeholder="Название" value="';
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
                  <input class="name" name="genre" placeholder="Жанр" value="';
            echo $row["genre"];
            echo '" aria-describedby="name-format">
                </div>
                <div class="cell small-4 small-offset-2 medium-2 medium-offset-3 large-1 large-offset-0">
                  <div class="pddng">Цена:</div>
                </div>
                <div class="cell small-4 medium-4 medium-offset-0 large-2 large-offset-0">
                  <input class="name" name="price" placeholder="Цена" value="';
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
                  <input class="name" name="amount" placeholder="Количество" value="';
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
        }
        else {
          noValue("Такой книги нет ");
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
        //если пользователь не ввел цену/кол-во, то 0
        if ($price == ""){
          $price = 0;
        } 
        if ($amount == ""){
          $amount = 0;
        } 
        //проверяем 3 обязательных параметра: автор, название и жанр
        if ($author <> "" && $title <> "" &&  $genre <> ""){
          $sql = "SELECT ( SELECT id FROM authors WHERE name = ?) as autId, (SELECT id FROM genre WHERE name = ?) as genId ";

          //получем id автора и жанра
          $result = executeRequest($sql,[$author,$genre]);
          $id_author = $result[0][0];
          $id_genre = $result[0][1];

          //если автора нет в БД, то сразу добавляем книгу в БД
          if ($id_author == null){
            //добавляем автора в БД
            $result = executeRequest("INSERT INTO authors (name) VALUES(?)",array($_POST['author']));
            //если жанра нет в БД, то добавляем его
            if ($id_genre == null) {
              $result = executeRequest("INSERT INTO genre (name) VALUES(?)",array($_POST['genre']));
            } 
            //получаем id автора и id жанра из БД [автор точно был добавлен, поэтому нам нужен его id]
            $result = executeRequest($sql,[$author,$genre]);
            $id_author = $result[0][0];
            $id_genre = $result[0][1];

            $sql = "UPDATE books SET id_author='$id_author', title='$title', id_genre='$id_genre', price='$price', amount='$amount' WHERE id='$idBook'";
            queryRequest($sql);
            noValue("Книга успешно обновлена");

          } else { 
            $result1 = executeRequest("SELECT id FROM books WHERE id_author = ? AND title = ?",array($id_author,$title));
            //проверяем, если ли книга с таким автором и названием в БД
            //в задании написано, что жанр не важен. книги совпадают, если у них одинаковы автор и название
            if (!empty($result1)){
              //если книга с таким автором и названием уже есть базе
              //то проверяем, совпадает ли id книги, который ввел пользователь с id в базе
              //[исключаем ситуацию, когда пользователь выбрал книгу А
              // и меняет ей название и автора на книгу В]
              if ($result1[0]["id"] == $idBook) {
                //если жанра нет в БД, то добавляем
                if ($id_genre == null) {
                  $result = executeRequest("INSERT INTO genre (name) VALUES(?)",array($_POST['genre']));
                  $result = executeRequest($sql,[$author,$genre]);
                  $id_genre = $result[0][1];
                }
                //обновляем книгу
                $sql = "UPDATE books SET price='$price', amount='$amount', id_genre='$id_genre' WHERE id='$idBook'";
                queryRequest($sql);
                noValue("Книга успешно обновлена");
              } else {
                noValue("Такая книга уже есть в базе. Попробуйте снова");
              }
            } else {
              //если жанра нет в БД, то добавляем
              if ($id_genre == null) {
                $result = executeRequest("INSERT INTO genre (name) VALUES(?)",array($_POST['genre']));
                $result = executeRequest($sql,[$author,$genre]);
                $id_genre = $result[0][1];
              }
              //обновляем книгу
              $sql = "UPDATE books SET id_author='$id_author', title='$title', id_genre='$id_genre', price='$price', amount='$amount' WHERE id='$idBook'";
              queryRequest($sql);
              noValue("Книга успешно обновлена");
            }

          }
        } else {
          noValue("Вы не ввели все данные. Попробуйте снова");
        }
      }
  }

  ?>

                   
  <div class="footer">
  </div>
    
    

    <script src="js/vendor/jquery.js"></script>
    <script src="js/vendor/what-input.js"></script>
    <script src="js/vendor/foundation.js"></script>
    <script src="js/app.js"></script>
  </body>
</html>
