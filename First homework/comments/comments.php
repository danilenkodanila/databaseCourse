<!DOCTYPE html>
<html>
<body>
<style type="text/css">
body{
   background: #F7F7F7;
}
.form-style-6{
   font: 95% Arial, Helvetica, sans-serif;
   max-width: 400px;
   margin: 10px auto;
   padding: 16px;
   background: #F7F7F7;
}
.form-style-6 h1{
   background: #43D1AF;
   padding: 20px 0;
   font-size: 140%;
   font-weight: 300;
   text-align: center;
   color: #fff;
   margin: -16px -16px 16px -16px;
}
.form-style-6 input[type="text"],
.form-style-6 input[type="email"],
.form-style-6 input[type="password"],
.form-style-6 textarea,
.form-style-6 select 
{
    -webkit-transition: all 0.30s ease-in-out;
    -moz-transition: all 0.30s ease-in-out;
    -ms-transition: all 0.30s ease-in-out;
    -o-transition: all 0.30s ease-in-out;
    outline: none;
    box-sizing: border-box;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    width: 100%;
    background: #fff;
    margin-bottom: 4%;
    border: 1px solid #ccc;
    padding: 3%;
    color: #555;
    font: 95% Arial, Helvetica, sans-serif;
}
.form-style-6 input[type="text"]:focus,
.form-style-6 input[type="email"]:focus,
.form-style-6 input[type="password"]:focus,
.form-style-6 textarea:focus,
.form-style-6 select:focus
{
    box-shadow: 0 0 5px #43D1AF;
    padding: 3%;
    border: 1px solid #43D1AF;
}

.form-style-6 input[type="submit"],
.form-style-6 input[type="button"]{
    box-sizing: border-box;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    width: 100%;
    padding: 3%;
    background: #43D1AF;
    border-bottom: 2px solid #30C29E;
    border-top-style: none;
    border-right-style: none;
    border-left-style: none;    
    color: #fff;
}
.form-style-6 input[type="submit"]:hover,
.form-style-6 input[type="button"]:hover{
    background: #2EBC99;
}
.comment, .date{
   font: 95% Arial, Helvetica, sans-serif;
   max-width: 400px;
   margin: 10px auto;
   /*padding-bottom: 16px;*/
   background: #F7F7F7;

}

.over{
   padding-bottom: 1px;
}
div.over:nth-child(2n) {
   text-align: right;

}
</style>
      <?php
         //вывод из файла
         $filename = 'comments.json';
         $json = file_get_contents($filename);
         // Декодируем
         $json = json_decode($json, true);
         if (!empty($json)){
            foreach ($json as $key => $value) {
               echo '<div class="over">';
               echo '<div class="date">';
               echo date('d.m.Y', $value['date']);
               echo '</div>';
               echo '<div class="comment">';
               echo $value['text'];
               echo '</div>';
               echo '</div>'; 
            } 
         }
         
      ?>
      </section>
      <div class="form-style-6">
      <form action="/comments.php" method="get">
         <textarea name="text" name="text" placeholder="Text" required></textarea>
         <br>
         <input type="submit" value="Send"/>
      </form> 
    
   <?php
      //запись в файл
      if (!empty($_GET)) {

         $filename = 'comments.json';
         date_default_timezone_set('Asia/Vladivostok');
         $array = array(
            "date" => time(),
            "text" => $_GET['text'],
         );
         
         $json = file_get_contents($filename);
         $json = json_decode($json, true);
         $json[] = $array;
         $json = json_encode($json);
         file_put_contents($filename, $json);
         //немного говнокода, мне лень 
         header("Refresh:0; url=comments.php");
      }  
   ?>
    
</div>
</body>
</html>


