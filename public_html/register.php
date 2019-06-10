<?php

/**
 * Регистрация пользователя
 */
require_once 'include/common.php';
 // Процесс регистрации
if (isset($_POST["register"])) {	
    if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['login']) && !empty($_POST['pass'])) {
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $username = htmlspecialchars($_POST['login']);
        $password = md5(htmlspecialchars($_POST['pass']));
        $query = mysqli_query($link,"SELECT * FROM `users` WHERE `login`='".$username."'") or die (mysql_error());
        $numrows = mysqli_num_rows($query);
        
        if ($numrows == 0) {
            $sql = "INSERT INTO users
                (name, email, login, password)
                VALUES('".$name."','".$email."', '".$username."', '".$password."')";
            $result=mysqli_query($link,$sql);
            if ($result) {
                $message = "Аккаунт ". $username ." успешно создан";
                // Редирект на login.php
                header('Location: login.php');
            } else {
                $message = "Упс, ошибка базы данных";
            }
        } else {
            $message = "Такой логин уже занят, попробуйте другой!";
            header('Location: login.php');
        }
    } else {
        $message = "Необходимо заполнить все поля!";
    } 
    setcookie("message", $message,time()+5);
} else {
    // Форма регистрации
    ?>

<!DOCTYPE html>
<html>
	<head>
	  	<meta charset="utf-8">
	  	<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="shortcut icon" href="static/images/favicon.ico" type="image/x-icon">
	  	<title>Регистрация</title>
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
		    		<li><a href="login.php">Войти</a></li>
		  		</ul>
			</nav>
		</header>

		<content>
			<div class="site_description">
				<div class="text_description">
					<form action="register.php" method="post">
                        <div class="form-group">
                            <label for="name__item">Ваше имя</label>
                            <input type="text" id="name__item" class="form-control" name="name" placeholder="Введите Ваше имя" required>
                        </div>
                        <div class="form-group">
                            <label for="login__item">Ваш логин</label>
                            <input type="text" id="login__item" class="form-control" name="login" placeholder="Введите Ваш логин" required>
                        </div>
                        <div class="form-group">
                            <label for="email__item">Ваш email</label>
                            <input type="email" id="email__item" class="form-control" name="email" placeholder="Введите Ваш email" required>
                        </div>
                        <div class="form-group">
                            <label for="pass__item">Ваш пароль</label>
                            <input type="password" id="pass__item" class="form-control" name="pass" placeholder="Введите Ваш пароль" required>
                        </div>
                        <input type="text" id="register__item" class="form-control" name="register" hidden="hidden">
                        <input type="submit" class="btn btn-primary" value="Зарегистрироваться">
                    </form>	
				</div>
			</div>
      </content>
      <footer>
			<p>© 2019 Fast.lm Inc. Все права защищены </p>
		</footer>
  </body>
</html>
    <?php
}
?>