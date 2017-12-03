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
  <div class="cell small-4 medium-1 small-offset-2 medium-offset-3 large-1 large-offset-3">
     <input class="name" name="id" placeholder="id" value="" aria-describedby="name-format">
  </div>
  <div class="cell small-4 medium-1 medium-offset-0 large-1 large-offset-0">
     <input class="name" name="author" placeholder="Автор" value="" aria-describedby="name-format">
  </div>
  <div class="cell small-4 small-offset-2 medium-1 medium-offset-0 large-1 large-offset-0">
     <input class="name" name="title" placeholder="Название" value="" aria-describedby="name-format">
  </div>
  <div class="cell small-4 medium-1 medium-offset-0 large-1 large-offset-0">
     <input class="name" name="id_genre" placeholder="Жанр" value="" aria-describedby="name-format">
    <!--  <select name="id_genre">
     <?php
      $user = "admin";
      $pass = "76543210";
      try {
        $dbh = new PDO('mysql:host=localhost;dbname=library;charset=utf8', $user, $pass);
      } catch (PDOException $e) {
          die('Подключение не удалось: ' . $e->getMessage());
      } 
      $sql = "SELECT name FROM genre";
      $sth = $dbh->query($sql);
      $result = $sth->fetchAll();
      var_dump($result);
      foreach ($result as &$value) {
          echo "<option>$value[0]</option>";
      }
    ?>
    </select> -->
  </div>
  <div class="cell small-4 small-offset-2 medium-1 medium-offset-0 large-1 large-offset-0">
     <input id="checkbox1" name="amount" value="Yes" type="checkbox"><label for="checkbox1">Наличие</label>
     <!-- <div class="content-checkBox">
         <input id="amountTrue" name="amount" type="checkbox"><label for="amountTrue">Наличие</label>
        </div> -->
  </div>
  <div class="cell small-4 medium-1 medium-offset-0 large-1 large-offset-0">
    <button class="button-search" type="submit" value="Submit">Поиск</button>
  </div>
</div>
</form>


<div class="grid-x">
  <div class="cell small-10 small-offset-2 medium-12 medium-offset-3 large-12 large-offset-3 th-font-width">
    <?php
      $sql = "SELECT SUM(amount) AS atotal from books";
      $sth = $dbh->query($sql);
      $result = $sth->fetchAll();
      $countBooks = $result[0][0];
      echo "Всего книг: $countBooks. ";
      $sql = "SELECT count(*) FROM issue WHERE date_e IS NULL";
      $sth = $dbh->query($sql);
      $result = $sth->fetchAll();
      $amountBooks = $countBooks - $result[0][0];
      echo "Доступных к выдаче: $amountBooks.";
    ?>
    <!-- Всего книг: 150. Доступных к выдаче: 100. -->
  </div>
</div>


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
  if (!empty($_POST)){
    $id = $_POST['id'];
    $name = $_POST['author'];
    $email = $_POST['title'];
    $id_genre = $_POST['id_genre'];
    $amount = false;
    if (empty($_POST['amount'])){
      $amount = true;
    } else {
      $amount = false;
    }



    $sql = "SELECT books.id, authors.name as author, books.title, genre.name as genre, books.price, books.amount FROM books INNER JOIN genre ON books.id_genre = genre.id INNER JOIN authors ON books.id_author = authors.id WHERE ";
    $array = [];
    $resultArray = [];
    $keyArray = array(
      "id"  => "books.id",
      "author" => "authors.name",
      "title" => "books.title",
      "id_genre" => "genre.name"
    );

    //проверяем заполнена ли хотя бы одна форма
    if ($id <> "" || $email <> "" || $name <> "" || $id_genre <>""){
      foreach($_POST as $key=>$value) {
        if(strlen($value)<>0) {
          if($key<>'amount'){ 
            $sql .= $keyArray[$key]." = ? AND ";
            array_push($array,$value);
          }
        }
      }
      if ($amount == true) {
        $sql = substr($sql, 0, -5);
      } else {
        $sql .= "books.amount <> 0";
      }

      $sth = $dbh->prepare($sql);
      $sth->execute($array);
      $result = $sth->fetchAll();
      if (!empty($result)){
        echo '<div class="grid-x">
                <div class="cell small-8 small-offset-2 medium-6 medium-offset-3 large-6 large-offset-3">
                  <table>
                    <thead>
                      <tr>
                        <th class="th-font-width" width="50">id</th>
                        <th class="th-font-width" width="200">Автор</th>
                        <th class="th-font-width" width="200">Название</th>
                        <th class="th-font-width" width="200">Жанр</th>
                        <th class="th-font-width" width="200">Цена</th>
                        <th class="th-font-width" width="200">Всего</th>
                        <th class="th-font-width" width="200">В наличие</th>
                      </tr>
                    </thead>
                    <tbody>';

            foreach($result as $row) {
              echo '<td class="td-width50">';
              echo ($row["id"]);
              echo '</td><td class="td-width50">';
              echo ($row["author"]);
              echo '</td><td class="td-width50">';
              echo ($row["title"]);
              echo '</td><td class="td-width50">';
              echo ($row["genre"]);
              echo '</td><td class="td-width50">';
              echo($row["price"]);
              echo '</td><td class="td-width50">';

              $sql = "SELECT count(*) AS count FROM issue WHERE date_e IS NULL AND id_book = ".$row["id"];
              $sth = $dbh->query($sql);
              $result = $sth->fetchAll();

              if ($row["amount"]>0){
                echo $row["amount"] + $result[0][0];
    
                echo '</td><td class="td-width50">';
                echo $row["amount"];
                echo "</td></tr>";
              } else {
                echo $row["amount"] + $result[0][0];
                echo '</td><td class="td-width50">';
                echo $row["amount"];
                echo "</td></tr>";
              }

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
