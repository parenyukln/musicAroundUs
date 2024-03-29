<?php
require_once 'include/common.php';
include('include/SxGeo.php');
include('include/db_class.php');

session_start();

if  (!isset($_SESSION["session_username"])) {
  header("location:login.php");
} else {
  $SxGeo = new SxGeo('include/SxGeoCity.dat', 0);
  $ip = $_SERVER['REMOTE_ADDR'];
  $response = $SxGeo->get($ip);
  $city = $response["city"]["name_ru"];
  $lat = $response["city"]["lat"];
  $lon = $response["city"]["lon"];
  $token = session_id();
  
  // Достаем юзеров
  $request_url = "http://cl18599.tmweb.ru/api/get_history.php?min_lon=1&min_lat=1&max_lon=1000&max_lat=1000&token=".$token."&get_history=1";
  $usersArray = json_decode(file_get_contents($request_url), TRUE);
?>
	
<!DOCTYPE html>
<html>
	<head>
	  	<meta charset="utf-8">
	  	<meta name="viewport" content="width=device-width, initial-scale=1">

	  	<title>Главная</title>
	  	<link rel="stylesheet" href="static/css/style.css">
	   	<link rel="stylesheet" type="text/css" href="static/css/slick.css">
		<link rel="shortcut icon" href="static/images/favicon.ico" type="image/x-icon">
	   	<script src="https://api-maps.yandex.ru/2.1/?apikey=<<?= $config['ya_api_key']; ?>>&lang=ru_RU" type="text/javascript"></script>
		<script src="https://code.jquery.com/jquery-2.2.0.min.js" type="text/javascript"></script>
		<script src="static/js/slick.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="static/js/scripts.js" type="text/javascript"></script>
		
		<script>
			var token = '<?= $token; ?>';
		</script>

	</head>

	<body>

		<header>
			<nav>
				<div class="logo">
					<a href="#"><img src="http://www.clker.com/cliparts/n/z/k/p/Y/j/music-icon.svg" alt='Music Icon clip art' style="height: 10vh;" /></a>
				</div>
		  		<ul class="menu">            
		    		<li><a href="login.php">Вы вошли как: <?= $_SESSION["session_username"]; ?></a></li>
		    		<li><a href="logout.php">Выйти</a></li>
		  		</ul>
			</nav>
		</header>

		<content>
			<div class="site_description">
				<div class="text_description">
					<h1>Делись музыкой и смотри, что слушают другие</h1>
					<p>Мы собрали на одной платформе все твои любимые музыкальные композиции — слушай, смотри, делись с окружающими</p>
				</div>
			</div>

			<div class="map">
				<h2>Музыка вокруг</h2>
				<p>Что и где слушают сейчас?</p>
				<p>Выбери локацию, чтобы узнать, что слушают именно здесь</p>
				<div id="yandex_map"></div>
			</div>

			<div class="user_slider">
				<h2>Сейчас слушают</h2>
				<section class="autoplay slider">
				    <div class="user_box">
				        <img src="static/images/person_1.png" alt="Пользователь" title="Пользователь слушает сейчас">
				        <div>
				        	<h4>Вася Пупкин</h4>
				        	<p>Sonic Youth - Peace Attack</p>
				    	</div>
				    </div>
				    <div class="user_box">
				        <img src="static/images/person_2.png" alt="Пользователь" title="Пользователь слушает сейчас">
				        <div>
				        	<h4>Wonder Woman</h4>
							<p>Ellie Goulding - Close To Me</p>
						</div>
				    </div>
				    <div class="user_box">
				        <img src="static/images/person_1.png" alt="Пользователь" title="Пользователь слушает сейчас">
				        <div>
				        	<h4>Вася Пупкин</h4>
				        	<p>Sonic Youth - Peace Attack</p>
				        </div>
				    </div>
				    <div class="user_box">
				      	<img src="static/images/person_2.png" alt="Пользователь" title="Пользователь слушает сейчас"><div><h4>Wonder Woman</h4>
						<p>Ellie Goulding - Close To Me</p></div>
				    </div>
				    <div class="user_box">
				      	<img src="static/images/person_1.png" alt="Пользователь" title="Пользователь слушает сейчас">
				      	<div>
				      		<h4>Вася Пупкин</h4>
				      		<p>Sonic Youth - Peace Attack</p>
				      	</div>
				    </div>
				    <div class="user_box">
				      	<img src="static/images/person_2.png" alt="Пользователь" title="Пользователь слушает сейчас">
				      	<div>
				      		<h4>Wonder Woman</h4>
							<p>Ellie Goulding - Close To Me</p>
						</div>
				    </div>
				    <?php 
						foreach ($usersArray as $one_user) {
					?>
						<div class="user_box">
					        <img src="static/images/person_1.png" alt="<?= $one_user['login']; ?>" title="Пользователь слушает сейчас">
					        <div>
					        	<h4><?= $one_user['login']; ?></h4>
					        	<p><?= $one_user['author']; ?> - <?= $one_user['title']; ?></p>
					    	</div>
					    </div>
					<?php
						}
					?>
			  	</section>
			</div>
		</content>

		<footer>
			<p>© 2019 Fast.lm Inc. Все права защищены </p>
		</footer>
	</body>
</html>
<?php } ?>