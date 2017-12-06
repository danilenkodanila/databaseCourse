<!doctype html>
<html class="no-js" lang="ru" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить книгу</title>
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

  
    include_once("ut.php");
    $dbh = connect();

    if (!empty($_POST)){
      $author = $_POST['author'];
      $title = $_POST['title'];
      $genre = $_POST['genre'];
      
      $array = [];
      $resultArray = [];

      //проверяем, что во всех трех полях есть значения
      if ($author <> "" && $title <> "" &&  $genre <> ""){
        $sql = "SELECT ( SELECT id FROM authors WHERE name = ?) as autId, (SELECT id FROM genre WHERE name = ?) as genId ";
        
        //проверяем есть ли уже такой автор и жанр в базе
        //если автора/жанра нет, добавляем и хниже
        $result = executeRequest($sql,[$author,$genre]);

        $id_author = $result[0][0];
        $id_genre = $result[0][1];

        
        //если автора нет в БД, то сразу добавляем книгу в БД
        if ($id_author == null){
          //добавляем автора в БД
          $result = executeRequest("INSERT INTO authors (name) VALUES(?)",array($_POST['author']));
          //если жанра нет в БД, то добавляем жанр
          if ($id_genre == null) {
            $result = executeRequest("INSERT INTO genre (name) VALUES(?)",array($_POST['genre']));
          } 
          //получаем автора и жанр из БД [автор точно был добавлен, поэтому нам нужен его id]
          $result = executeRequest($sql,[$author,$genre]);
          $id_author = $result[0][0];
          $id_genre = $result[0][1];

          //добавляем книгу в БД
          $sql = "INSERT INTO books (id_author, title, id_genre, price, amount) VALUES (?,?,?,?,?)";
          $result = executeRequest($sql,[$id_author,$title,$id_genre,rand(1, 1000),1]);
          noValue("Книга успешно добавлена");

        } else {
          //проверяем, если ли книга с таким автором и названием в БД
          //в задании написано, что жанр не важен. книги совпадают, если у них одинаковы автор и название
          //поэтому, если автор и и название совпадают, но жанр другой, то просто увеличиваем кол-во на 1
          $result1 = executeRequest("SELECT id FROM books WHERE id_author = ? AND title = ?", array($id_author,$title));
          if (!empty($result1)){
            noValue("Такая книга уже есть в базе. Количество увеличино на 1");
            $result = executeRequest("UPDATE books SET amount=amount+1 WHERE id=?", array($result1[0][0]));
          } else {
            //если жанра нет в БД, то добавляем
            if ($id_genre == null) {
              $result = executeRequest("INSERT INTO genre (name) VALUES(?)", array($_POST['genre']));
              //и получаем id жанра в ответ
              $result = executeRequest($sql,[$author,$genre]);
              $id_genre = $result[0][1];
            } 
            //добавляем книгу в БД
            $sql = "INSERT INTO books (id_author, title, id_genre, price, amount) VALUES (?,?,?,?,?)";
            $result = executeRequest($sql,[$id_author,$title,$id_genre,rand(1, 1000),1]);
            noValue("Книга успешно добавлена");
          }

        }
      } else {
        noValue("Введите все данные");
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
