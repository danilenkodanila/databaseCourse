<?php
	function connect(){
		$user = "admin";
		$pass = "76543210";
		try {
			$dbh = new PDO('mysql:host=localhost;dbname=library;charset=utf8', $user, $pass);
		} catch (PDOException $e) {
			die('Подключение не удалось: ' . $e->getMessage());
		}
		return $dbh;
	}
	function queryRequest($sql){
		$dbh = connect();
		$sth = $dbh->query($sql);
        $result = $sth->fetchAll();
        return $result;
	}
	function noValue($outputText){
	    echo'<div class="grid-x">
	         	<div class="small-3 large-3 medium-3 cell"></div>
	         	<div class="small-6 large-6 medium-6 cell et">',$outputText,'</div>
	         	<div class="small-3 large-3 medium-3 cell"></div>
	         </div>';
  	}
  	function executeRequest($sql,$array){
  		$dbh = connect();
  		$sth = $dbh->prepare($sql);
        $sth->execute($array);
        $result = $sth->fetchAll();
        return $result;
  	}
?>