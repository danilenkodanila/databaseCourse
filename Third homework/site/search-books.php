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

<form action="search-books.php" method="post">
<div class="grid-x">
  <div class="small-12 medium-1 large-1 columns"></div>
  <div class="small-12 medium-1 large-1 columns">

  </div>
  <div class="small-12 medium-1 large-1 columns"></div>
  <div class="small-12 medium-1 large-1 columns">
     <input class="name" name="id" placeholder="id" value="" aria-describedby="name-format">
  </div>
  <div class="small-12 medium-1 large-1 columns">
     <input class="name" name="author" placeholder="Автор" value="" aria-describedby="name-format">
  </div>
  <div class="small-12 medium-1 large-1 columns">
     <input class="name" name="title" placeholder="Название" value="" aria-describedby="name-format">
  </div>
  <div class="small-12 medium-1 large-1 columns">
     <input class="name" name="id_genre" placeholder="Жанр id" value="" aria-describedby="name-format">
  </div>
  <div class="small-12 medium-1 large-1 columns custom">
     <input id="checkbox1" name="amount" type="checkbox"><label for="checkbox1">Наличие</label>
  </div>
  <div class="small-12 medium-1 large-1 columns">
    <button class="button-search" type="submit" value="Submit">Поиск</button>
  </div>
</div>
</form>



<?php

  function noValue(){
    echo'<div class="grid-x">
           <div class="small-3 large-3 medium-3 columns"></div>
           <div class="small-6 large-6 medium-6 columns et">Результатов нет</div>
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


  //если запрос не пустой
  if (!empty($_POST)){

    $id = $_POST['id'];
    $name = $_POST['author'];
    $email = $_POST['title'];
    $id_genre = $_POST['id_genre'];

    //если галочка "в наличии" не поставлена
    if (empty($_POST['amount'])){
      $sql = "SELECT * FROM books WHERE ";
      $array = [];
      $resultArray = [];

      //проверяем заполнена ли хотя бы одна форма
      if ($id <> "" || $email <> "" || $name <> "" || $id_genre <>""){

        //создаем запрос 
        foreach($_POST as $key=>$value) {
          if(strlen($value)<>0) {
            $sql .= $key." = ? AND ";
            array_push($array,$value);
          }
        }
        //небольшой костыль: обрезаем последний AND
        $sql = substr($sql, 0, -5);

        $sth = $dbh->prepare($sql);
        $sth->execute($array);
        $result = $sth->fetchAll();

        if (!empty($result)){
          echo '<div class="grid-x"><div class="small-12 medium-3 large-3 columns">
                </div>
                <div class="small-12 medium-4 large-6 columns">
                  <table>
                    <thead>
                      <tr>
                        <th width="50">id</th>
                        <th width="200">Автор</th>
                        <th width="200">Название</th>
                        <th width="200">Жанр</th>
                        <th width="200">Цена</th>
                        <th width="200">Наличие</th>
                      </tr>
                    </thead>
                    <tbody>';

            foreach($result as $row) {
              echo "<td>";
              echo ($row["id"]);
              echo "</td><td>";
              echo ($row["author"]);
              echo "</td><td>";
              echo ($row["title"]);
              echo "</td><td>";

              $sth1 = $dbh->prepare("SELECT name FROM genre WHERE id = ?");
              $sth1->execute(array($row['id_genre']));
              $result1 = $sth1->fetchAll();

              echo($result1[0]['name']);
              echo "</td><td>";
              echo($row["price"]);
              echo "</td><td>";

              if ($row["amount"]>0){
                echo "Есть";
              } else {
                echo "Нет";
              }
              echo "</td></tr>";
            }
          echo '</tbody>
              </table>
            </div>
            <div class="small-12 medium-3 large-3 columns">
            </div>
          </div>';
          } else {
            noValue();
          }
        } else {
          noValue();
        }
    } else {
      $sql = "SELECT * FROM books WHERE ";
      $array = [];
      $resultArray = [];

      if ($id <> "" || $email <> "" || $name <> "" || $id_genre <>""){
        foreach($_POST as $key=>$value) {
          if($key<>'amount'){
            if(strlen($value)<>0) {
              $sql .= $key." = ? AND ";
              array_push($array,$value);
            }
          }
        }

      $sql = substr($sql, 0, -5);
      $sth = $dbh->prepare($sql);
      $sth->execute($array);
      $result = $sth->fetchAll();

      if (count($result)==1 && $result[0]["amount"]==0){
         noValue();
        } else {
          echo '<div class="grid-x"><div class="small-12 medium-3 large-3 columns">
              </div>
              <div class="small-12 medium-4 large-6 columns">
                <table>
                  <thead>
                    <tr>
                      <th width="50">id</th>
                      <th width="200">Автор</th>
                      <th width="200">Название</th>
                      <th width="200">Жанр</th>
                      <th width="200">Цена</th>
                      <th width="200">Наличие</th>
                    </tr>
                  </thead>
                  <tbody>';
        foreach($result as $row) {
          if ($row["amount"]>0){
            echo "<td>";
            echo ($row["id"]);
            echo "</td><td>";
            echo ($row["author"]);
            echo "</td><td>";
            echo ($row["title"]);
            echo "</td><td>";

            $sth1 = $dbh->prepare("SELECT name FROM genre WHERE id = ?");
            $sth1->execute(array($row['id_genre']));
            $result1 = $sth1->fetchAll();

            echo($result1[0]['name']);
            echo "</td><td>";
            echo($row["price"]);
            echo "</td><td>";

            if ($row["amount"]>0){
              echo "Есть";
            } else {
              echo "Нет";
            }
            echo "</td></tr>";
            } 
        }
        echo '</tbody>
            </table>
          </div>
          <div class="small-12 medium-3 large-3 columns">
          </div>
        </div>';
        }
      } else {
        noValue();
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
    <script type="text/javascript">
    $(document).foundation();
    </script>
  </body>
</html>
