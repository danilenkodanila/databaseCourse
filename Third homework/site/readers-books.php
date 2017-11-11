<!doctype html>
<html class="no-js" lang="en" dir="ltr">
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

<form action="readers-books.php" method="post">
<div class="grid-x">
  <div class="small-12 medium-6 large-1 columns">
  </div>
  <div class="small-12 medium-6 large-1 columns">
  </div>
   <div class="small-12 medium-6 large-1 columns">
  </div>
  <div class="small-12 medium-3 large-1 columns">
     <input class="name" name="id" placeholder="id" value="" aria-describedby="name-format">
  </div>
  <div class="small-12 medium-3 large-1 columns">
     <input class="name" name="name" placeholder="Имя" value="" aria-describedby="name-format">
  </div>
  <div class="small-12 medium-3 large-1 columns">
    <input class="name" name="email" placeholder="Почта" aria-describedby="exampleHelpTex" data-abide-ignore>
  </div>
  <div class="small-12 medium-3 large-1 columns">
    <input class="name" name="phone" placeholder="Телефон" aria-describedby="exampleHelpTex" data-abide-ignore>
  </div>
  <div class="small-12 medium-6 large-1 columns">
  </div>
  <div class="small-12 medium-6 large-1 columns">
    <button class="button-search" type="submit" value="Submit">Поиск</button>
  </div>
  <div class="small-12 medium-6 large-1 columns">
  </div>
</div>
</form>





<?php
  $user = "admin";
  $pass = "76543210";
  try {
    $dbh = new PDO('mysql:host=localhost;dbname=library;charset=utf8', $user, $pass);
  } catch (PDOException $e) {
      die('Подключение не удалось: ' . $e->getMessage());
  }

  if (!empty($_POST)){
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $sql = "SELECT * FROM readers WHERE ";
    $array = [];
    $resultArray = [];

    if ($id <> "" || $email <> "" || $phone <> "" || $name <> ""){
      foreach($_POST as $key=>$value) {
        if(strlen($value)<>0) {
          $sql .= $key." = ? AND ";
          array_push($array,$value);
        }
      }
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
                    <th width="200">Имя</th>
                    <th width="200">Почта</th>
                    <th width="200">Телефон</th>
                  </tr>
                </thead>
                <tbody>';

        foreach($result as $row) {
          echo "<td>";
          echo ($row["id"]);
          echo "</td><td>";
          echo ($row["name"]);
          echo "</td><td>";
          echo ($row["email"]);
          echo "</td><td>";
          echo ($row["phone"]);
          echo "</td></tr>";
        }
        echo '</tbody>
            </table>
          </div>
          <div class="small-12 medium-3 large-3 columns">
          </div>
        </div>';
        }
        else {
          echo'<div class="grid-x">
             <div class="small-3 large-3 medium-3 columns"></div>
             <div class="small-6 large-6 medium-6 columns et">Результатов нет</div>
             <div class="small-3 large-3 medium-3 columns"></div>
           </div>';
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
