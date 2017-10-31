<!DOCTYPE html>
<html>
<head>
<style>
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}
th, td {
    padding: 5px;
    text-align: left;
}
</style>
</head>
<body>

<table style="width:100%">
  <caption>Табличечка</caption>
  <tr>
  <?php
   $array = array(
        'id'=>array(1,2,3,4),
        'user'=>array('Ivan', 'Sergey', 'Stepan', 'Vladimir'),
        'email'=>array('iv@mail.ru', 'ser@mail.ru', 'step@mail.ru', 'vl@mail.ru')
    );

    $arrayKeys = array_keys($array); //массив ключей: id, user, email
    $maxColumn = 0; //максимальная длина в колонке

    foreach ($arrayKeys as $key => $value) {
        if (count($array[$value]) > $maxColumn) {
            $maxColumn = count($array[$value]);
        }
    }
    
    echo "<tr>";
    for ($i = 0; $i < count($arrayKeys); $i++) {
        echo "<th>$arrayKeys[$i]</th>";
    }
    echo "</tr>";
    for ($i = 0; $i < $maxColumn; $i++) {
        echo "<tr>";
        for ($j = 0; $j < count($arrayKeys); $j++) {
            echo "<td>";
            if (!empty($array[$arrayKeys[$j]][$i])) {
                echo($array[$arrayKeys[$j]][$i]);
            } else {
                echo("тут котик");
            }
            echo "</td>";
        }
        echo "</tr>";
    }
  ?>
  </tr>

</table>

</body>
</html>
