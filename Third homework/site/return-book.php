<!doctype html>
<html class="no-js" lang="ru" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вернуть книгу</title>
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



  <form action="return-book.php" method="post">
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
      <button class="button-search" type="submit" value="Submit">Вернуть</button>
    </div>
    <div class="small-12 medium-4 large-4 columns"></div>
  </div>
  </form>

  <?php

    include_once("ut.php");
    $dbh = connect();


    if (!empty($_POST)){
      $id_reader = $_POST['id_reader'];
      $id_book = $_POST['id_book'];  
    
      //проверяем не пустые ли поля
      if ($id_reader <> "" && $id_book <> "") {
        date_default_timezone_set('Asia/Vladivostok');
        $date = date('Y-m-d', time());
        $sql = "UPDATE `issue` SET `date_e`='$date' WHERE `id_reader` = '$id_reader' AND `id_book` = '$id_book' AND `date_e` IS NULL LIMIT 1";
        $dbh = connect();
        $count = $dbh->exec($sql);

        if ($count == 0) {
          noValue("Такой выдачи не существует");
        } else {
          noValue("Книга возвращена");
          $sql = "UPDATE `books` SET `amount`=`amount`+1 WHERE `id` = '$id_book'";
          queryRequest($sql);
        }
        
      } else {
        noValue("Введите оба параметра");
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
