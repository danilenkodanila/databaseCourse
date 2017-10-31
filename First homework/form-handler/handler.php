<!DOCTYPE html>
<html>
<body>
<style type="text/css">
	body{
	    background: #F7F7F7;
	    font: 95% Arial, Helvetica, sans-serif;
	}
	#wrapper {
		text-align: center;
	}
	#yourdiv {
		display: inline-block;
	}
</style>
<div id="wrapper">    
    <div id="yourdiv">
	    <?php

			// The global $_POST variable allows you to access the data sent with the POST method by name
			// To access the data sent with the GET method, you can use $_GET
			$login = htmlspecialchars($_POST['login']);
			$email  = htmlspecialchars($_POST['email']);
			$password  = htmlspecialchars($_POST['password']);
			echo  'login: ', $login, '<br>email: ', $email, '<br>password: ', $password;
		?>
    </div>
</div>

</div>
</body>
</html>