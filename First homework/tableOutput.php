<!DOCTYPE html>
<html>
<head>
<style>
    table {
        table-layout: fixed;
        width: 100%;
    }
    table, th, td {
        border: 1px solid gray;
        border-collapse: collapse;
    }
    th, td {
        padding: 5px;
        text-align: left;
    }
    th {
        background: DarkGray;
    }
    tr:nth-child(2n){
        background: WhiteSmoke;
    }
</style>
</head>
<body>

<table >
  <tr>
  <?php
   $array = array(
        'id'=>array(1,2,3,4),
        'user'=>array('Ivan', 'Sergey', 'Stepan', 'Vladimir'),
        'email'=>array('iv@mail.ru', 'ser@mail.ru', 'step@mail.ru', 'vl@mail.ru')
    );

    $arrayKeys = array_keys($array); //массив ключей: id, user, email
    $maxColumn = 0; //максимальная длина в колонке

    //находим максимальную длину колонки
    foreach ($arrayKeys as $key => $value) {
        if (count($array[$value]) > $maxColumn) {
            $maxColumn = count($array[$value]);
        }
    }

    //выводим название колонок
    echo "<tr>";
    for ($i = 0; $i < count($arrayKeys); $i++) {
        echo "<th>$arrayKeys[$i]</th>";
    }
    echo "</tr>";

    //выводим колонки
    for ($i = 0; $i < $maxColumn; $i++) {
        echo "<tr>";
        for ($j = 0; $j < count($arrayKeys); $j++) {
            echo "<td>";
            
            //проверка на пустоту
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
