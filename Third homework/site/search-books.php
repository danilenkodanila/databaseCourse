<!doctype html>
<html class="no-js" lang="ru" dir="ltr">
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
      </div>
      <div class="cell small-4 small-offset-2 medium-1 medium-offset-0 large-1 large-offset-0">
         <input id="checkbox1" name="amount" value="Yes" type="checkbox"><label for="checkbox1">Наличие</label>
      </div>
      <div class="cell small-4 medium-1 medium-offset-0 large-1 large-offset-0">
        <button class="button-search" type="submit" value="Submit">Поиск</button>
      </div>
    </div>
    </form>

    
    <div class="grid-x">
      <div class="cell small-10 small-offset-2 medium-12 medium-offset-3 large-6 th-font-width">
        <?php
          include_once("ut.php");
          $dbh = connect();
          $sql = "SELECT(SELECT SUM(amount) from books) AS all_amount,(SELECT count(*) FROM issue WHERE date_e IS NULL) AS issue_amount";
          $result = queryRequest($sql);
          $countBooks = $result[0][0];
          $amountBooks = $countBooks - $result[0][1];
          echo "Всего книг: $countBooks. Доступных к выдаче: $amountBooks.";
        ?>
      </div>
    </div>


  <?php


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

        //чекаем галочку в наличие
        if ($amount == true) {
          $sql = substr($sql, 0, -5);
        } else {
          $sql .= "books.amount <> 0";
        }

        $result = executeRequest($sql, $array);
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
                $result = queryRequest($sql);

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
              echo '    </tbody>
                      </table>
                    </div>
                  <div class="small-12 medium-3 large-3 columns">
                </div>
              </div>';
            } else {
              noValue("Результатов нет");
            }
          } else {
            noValue("Введите хотя бы один параметр");
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
