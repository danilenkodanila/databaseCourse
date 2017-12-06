<!doctype html>
<html class="no-js" lang="ru" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Изменить читателя</title>
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

  <form action="changeReader.php" method="post">
  <div class="grid-x">
    <div class="cell small-4 small-offset-2 medium-2 medium-offset-3 large-2 large-offset-3">
       <input class="name" name="id" placeholder="id читателя" value="" aria-describedby="name-format">
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

  //проверяем с какой формы пришел post
  if(isset($_POST['action']) && $_POST['action'] == 'form1') {
    if (!empty($_POST)){
      $id_reader = $_POST['id'];

      if ($id_reader <> "" ){
        //выбираем все из таблицы читатели
        $sql = "SELECT * FROM `readers` WHERE id=?";
        $result = executeRequest($sql,[$id_reader]);
        
        //проверяем, существует ли читатель
        if (!empty($result)) {
          foreach($result as $row){
            echo '<form action="changeReader.php" method="post">
              <div class="grid-x">
                <div class="cell small-4 medium-2 small-offset-2 medium-offset-3 large-1 large-offset-3">
                  Имя:
                </div>
                <div class="cell small-4 medium-4 medium-offset-0 large-2 large-offset-0">
                  <input type="text" class="name" name="name" placeholder="Имя" value="';
            echo $row["name"];
            echo '" aria-describedby="name-format">
                </div>
                <div class="cell small-4 small-offset-2 medium-2 medium-offset-3 large-1 large-offset-0">
                  <div class="pddng">Почта:</div>
                </div>
                <div class="cell small-4 medium-4 medium-offset-0 large-2 large-offset-0">
                  <input type="email" required class="name" name="email" placeholder="Почта" value="';
            echo $row["email"];
            echo '" aria-describedby="name-format">
                </div>
                <div class="cell small-4 medium-4 medium-offset-0 large-1 large-offset-0">
                </div>
              </div>
              <div class="grid-x">
                <div class="cell small-4 medium-2 small-offset-2 medium-offset-3 large-1 large-offset-3">
                  Телефон:
                </div>
                <div class="cell small-4 medium-4 medium-offset-0 large-2 large-offset-0">
                  <input type="tel" pattern="[\+]\d{11}$" class="name" name="phone" placeholder="+79999999999" value="';
            echo $row["phone"];
            echo '" aria-describedby="name-format">
                </div>
                <div class="cell small-8 small-offset-2 medium-6 medium-offset-3 large-2 large-offset-1">
                  <button class="button-search" type="submit" value="Submit">Сохранить</button>
                </div>
                <input type="hidden" name="action" value="form2"/>
                <input type="hidden" name="idReader" value="';
            echo $row["id"];
            echo '"/>
              </div>
            </form>';

          }
        } else {
          noValue("Такого id нет");
        }


      } else {
        noValue("Введите id читателя");
      }


    }
  } else if(isset($_POST['action']) && $_POST['action'] == 'form2') {
    if (!empty($_POST)){
      $name = $_POST['name'];
      $email = $_POST['email'];
      $phone = $_POST['phone'];
      $idReader = $_POST['idReader'];

      //проверяем заполненность всех форм
      if ($name <> "" && $email <> "" &&  $phone <> ""){
          //обновляем данные
          $sql = "UPDATE readers SET name='$name', email='$email', phone='$phone' WHERE id='$idReader'";
          queryRequest($sql);
          noValue("Данные успешно обновлены");
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
  </body>
</html>
