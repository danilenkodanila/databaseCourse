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

<form action="readers-books.php" method="post">
<div class="grid-x">
  <div class="cell small-4 small-offset-2 medium-1 medium-offset-3 large-1 large-offset-3">
     <input class="name" name="id" placeholder="id" value="" aria-describedby="name-format">
  </div>
  <div class="cell small-4 medium-1 medium-offset-0 large-1 large-offset-0">
     <input class="name" name="name" placeholder="Имя" value="" aria-describedby="name-format">
  </div>
  <div class="cell small-4 small-offset-2 medium-1 medium-offset-0 large-1 large-offset-0">
    <input class="name" name="email" placeholder="Почта" aria-describedby="exampleHelpTex" data-abide-ignore>
  </div>
  <div class="cell small-4 medium-1 medium-offset-0 large-1 large-offset-0">
    <input class="name" name="phone" placeholder="Телефон" aria-describedby="exampleHelpTex" data-abide-ignore>
  </div>
  <div class="cell small-8 small-offset-2 medium-1 medium-offset-1 large-1 large-offset-1">
    <button class="button-search" type="submit" value="Submit">Поиск</button>
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

    $sql = "SELECT readers.id, readers.name, readers.email, readers.phone, count(issue.id_reader) as issueCount FROM readers LEFT JOIN issue ON readers.id = issue.id_reader WHERE ";
    $array = [];
    $resultArray = [];
    $keyArray = array(
      "id"  => "readers.id",
      "name" => "readers.name",
      "email" => "readers.email",
      "phone" => "readers.phone"
    );

    if ($id <> "" || $email <> "" || $phone <> "" || $name <> ""){
      foreach($_POST as $key=>$value) {
        if(strlen($value)<>0) {
          $sql .= $keyArray[$key]." = ? AND ";
          array_push($array,$value);
        }
      }
      $sql = substr($sql, 0, -5);
      $sql.= "group by readers.id";
      // echo $sql;

      $sth = $dbh->prepare($sql);
      $sth->execute($array);
      $result = $sth->fetchAll();

      

      // var_dump($result);
      if (!empty($result)){
        echo '<div class="grid-x">
            <div class="cell small-8 small-offset-2 medium-6 medium-offset-3 large-6 large-offset-3">
              <table>
                <thead>
                  <tr>
                    <th class="th-font-width" width="50">id</th>
                    <th class="th-font-width" width="200">Имя</th>
                    <th class="th-font-width" width="200">Почта</th>
                    <th class="th-font-width" width="200">Телефон</th>
                  </tr>
                </thead>
                <tbody>';

        foreach($result as $row) {
          // echo $row["issueCount"];
          $sql2 = "SELECT COUNT(*) FROM issue WHERE id_reader = ? AND date_e IS NULL";
          $sth1 = $dbh->prepare($sql2);
          $sth1->execute(array($row["id"]));
          $result1 = $sth1->fetchAll();
          // var_dump($result1);

          if ($result1[0][0] == 0) {
            echo '<td class="td-width50" style="background-color: white;">';
            echo ($row["id"]);
            echo '</td><td class="td-width50" style="background-color: white;">';
            echo ($row["name"]);
            echo '</td><td class="td-width50" style="background-color: white;">';
            echo ($row["email"]);
            echo '</td><td class="td-width50" style="background-color: white;">';
            echo ($row["phone"]);
            echo "</td></tr>";
          } else {
            echo '<td class="td-width50" style="background-color: rgba(255,204,188,0.5);">';
            echo ($row["id"]);
            echo '</td><td class="td-width50" style="background-color: rgba(255,204,188,0.5);">';
            echo ($row["name"]);
            echo '</td><td class="td-width50" style="background-color: rgba(255,204,188,0.5);">';
            echo ($row["email"]);
            echo '</td><td class="td-width50" style="background-color: rgba(255,204,188,0.5);">';
            echo ($row["phone"]);
            echo "</td></tr>";
          }
        }
        echo '</tbody>
            </table>
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
