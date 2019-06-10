<?php

/*
*   Файл авторизации пользователя
*/

require_once 'include/common.php';

session_start();

if (isset($_SESSION["session_username"])) {
    // вывод "Session is set"; // в целях проверки
    header("Location: index.php");
}

if (isset($_POST["login"])) {
    if (!empty($_POST['username']) && !empty($_POST['pass'])) {
        $username=htmlspecialchars($_POST['username']);
        $password=md5(htmlspecialchars($_POST['pass']));
        $query =mysqli_query($link, "SELECT * FROM users WHERE `login`='".$username."' AND `password`='".$password."'");
        $numrows=mysqli_num_rows($query);
        if ($numrows !=0 ) {     
            while ($row=mysqli_fetch_assoc($query)) {
                $dbusername=$row['login'];
                $dbpassword=$row['password'];
            }
            if ($username == $dbusername && $password == $dbpassword) {
                // старое место расположения
                //  session_start();
              
                $_SESSION['session_username']=$username;
                $ses_id = session_id();
				$insertQuery = mysqli_query($link, "UPDATE users SET token='".$ses_id."' WHERE login='".$username."'");
                /* Перенаправление браузера */
                header("Location: index.php");  
            }
        } else {
            //  $message = "Invalid username or password!";
            $message = "Неправильный логин или пароль, попробуйте заново!";
          	setcookie("message", $message,time()+5);
          	header("Location: login.php");  
        }
        } else {
            $message = "Необходимо заполнить все поля!";
      		setcookie("message", $message,time()+5);
          	header("Location: login.php");  
        }
} else { // Форма логина
    ?>

<!DOCTYPE html>
<html>
	<head>
	  	<meta charset="utf-8">
	  	<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="shortcut icon" href="static/images/favicon.ico" type="image/x-icon">
	  	<title>Вход</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" href="static/styles.css">
	  	<link rel="stylesheet" href="static/css/style.css">
	   	<link rel="stylesheet" type="text/css" href="static/css/slick.css">

	   	<script src="https://api-maps.yandex.ru/2.1/?apikey=<6a57fb75-3212-43de-8b69-55c65eeff611>&lang=ru_RU" type="text/javascript"></script>
		<script src="https://code.jquery.com/jquery-2.2.0.min.js" type="text/javascript"></script>
		<script src="static/js/slick.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="static/js/scripts.js" type="text/javascript"></script>

	</head>

	<body>

		<header>
			<nav>
				<div class="logo">
					<a href="#"><img src="http://www.clker.com/cliparts/n/z/k/p/Y/j/music-icon.svg" alt='Music Icon clip art' style="height: 10vh;" /></a>
				</div>
		  		<ul class="menu">            
		    		<li><a href="register.php">Регистрация</a></li>
		  		</ul>
			</nav>
		</header>

		<content>
			<div class="site_description">
				<div class="text_description">
					<form action="login.php" method="post">
                      <div class="form-group">
                          <label for="username__item">Ваш логин</label>
                          <input type="text" id="username__item" class="form-control" name="username" placeholder="Введите Ваш логин" required>
                      </div>
                      <div class="form-group">
                          <label for="pass__item">Ваш пароль</label>
                          <input type="password" id="pass__item" class="form-control" name="pass" placeholder="Введите Ваш пароль" required>
                      </div>
                      <input type="text" id="login__item" class="form-control" name="login" hidden="hidden">
                      <input type="submit" class="btn btn-primary" value="Войти">
                  </form>	
				</div>
			</div>
      </content>
      <?php
        // Вывод ошибки
        $message = $_COOKIE['message'];
        if (!empty($message)) {
            echo "<p class='container'>" . "Системное сообщение: ". $message . "</p>";
        }
        ?>
      <footer>
			<p>© 2019 Fast.lm Inc. Все права защищены </p>
		</footer>
  </body>
</html>
    <?php
} 
?>
