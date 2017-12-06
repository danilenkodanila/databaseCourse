<!doctype html>
<html class="no-js" lang="ru" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Выданные книги</title>
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


    <?php
    	include_once("ut.php");
        $dbh = connect();

        //просто выбираем все книги)))
    	$sql="SELECT issue.date_s, issue.id_book, books.title AS book_name, authors.name AS author_name, readers.name AS reader_name FROM issue LEFT JOIN books ON issue.id_book = books.id LEFT JOIN authors ON authors.id = books.id_author LEFT JOIN readers ON readers.id = issue.id_readeR WHERE issue.date_e IS ?";
        $result = executeRequest($sql, array(NULL));

        noValue("Список выданных книг:");

        if (!empty($result)){
        	echo '<div class="grid-x">
                <div class="cell small-8 small-offset-2 medium-6 medium-offset-3 large-6 large-offset-3">
                  <table>
                    <thead>
                      <tr>
    					<th class="th-font-width" width="50">id книги</th>
                        <th class="th-font-width" width="200">Дата выдачи</th>
                        <th class="th-font-width" width="200">Автор</th>
                        <th class="th-font-width" width="200">Название</th>
                        <th class="th-font-width" width="200">Взявший</th>
                      </tr>
                    </thead>
                    <tbody>';

            foreach($result as $row) {
    			echo '</td><td class="td-width50">';
                echo ($row["id_book"]);
                echo '<td class="td-width50">';
                echo ($row["date_s"]);
                echo '</td><td class="td-width50">';
                echo ($row["book_name"]);
                echo '</td><td class="td-width50">';
                echo ($row["author_name"]);
                echo '</td><td class="td-width50">';
                echo ($row["reader_name"]);
                echo "</td></tr>";
            }
            echo '		</tbody>
                	</table>
              		</div>
            	</div>';
            }
        else {
        	echo'<div class="grid-x">
            	<div class="small-3 large-3 medium-3 columns"></div>
            	<div class="small-6 large-6 medium-6 columns et">Выданных книг нет</div>
            	<div class="small-3 large-3 medium-3 columns"></div>
            </div>';
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
