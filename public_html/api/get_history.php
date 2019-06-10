<?php

include('../include/db_class.php');
require_once '../include/common.php';
header('Content-Type: application/json;charset=utf-8');
header('Accept: application/json');
session_start();

if ($_REQUEST['get_history']) {
  if (!empty($_REQUEST['max_lat']) && !empty($_REQUEST['max_lon']) && !empty($_REQUEST['min_lat']) && !empty($_REQUEST['min_lon']) && !empty($_REQUEST['token'])) {
    $max_lat = $_REQUEST['max_lat'];
    $max_lon = $_REQUEST['max_lon'];
    $min_lat = $_REQUEST['min_lat'];
    $min_lon = $_REQUEST['min_lon'];
    $request_token = $_REQUEST['token'];
    
    // Проверяем коректность min max
    $latArray = checkMinMaxValues($min_lat, $max_lat);
    $min_lat = floatval($latArray['min']);
    $max_lat = floatval($latArray['max']);
    
    $lonArray = checkMinMaxValues($min_lon, $max_lon);
    $min_lon = floatval($lonArray['min']);
    $max_lon = floatval($lonArray['max']);
    
    $db = new DataBase();
    // Проверяем есть ли запрашиваемый токен в бд
    $token_query = $db->checkToken($request_token);
    $numrows=mysqli_num_rows($token_query);
    if ($numrows != 0 ) {     
      while ($row=mysqli_fetch_assoc($token_query)) {
        $user_id=$row['id'];
      }
      
      // Выбираем пользователей в этом районе
      $users_query = $db->executeQuery("SELECT geo_personal.user_id as id, geo.lat as lat, geo.lon as lon from geo_personal JOIN geo ON geo.id = geo_personal.geo_id 
      WHERE geo.lat BETWEEN '".$min_lat."' AND '".$max_lat."'
      AND geo.lon BETWEEN '".$min_lon."' AND '".$max_lon."'");
      $numrows=mysqli_num_rows($users_query);
      // Если пользователи есть
	    if ($numrows != 0 ) {     
	    	$users = [];
	      while ($row=mysqli_fetch_assoc($users_query)) {
	        $users[] = [
	        	'id'  => $row['id'],
	        	'lat' => $row['lat'],
	        	'lon' => $row['lon']
        	];
	      }
	    } else { 
	      	// Если нет пользователей в заданном районе
		    $result = [];
		    echo json_encode($result);
		    die();
	      }
      
      $result_array = [];
      // Выбираем логины пользователей и их песни (по каждому пользователю)
      foreach ($users as $u_id) {
      	$select_array = $db->executeQuery("SELECT users.login as login, music.author as author, music.title as title 
	      FROM users JOIN music ON users.id = music.user_id WHERE music.id = (SELECT music_id from music_history WHERE user_id ='".$u_id['id']."' ORDER BY id DESC LIMIT 1)");
	      
	      $numrows=mysqli_num_rows($select_array);
		    if ($numrows != 0 ) {     
		      while ($row=mysqli_fetch_assoc($select_array)) {
		      	$result_array[] = [
		      		'user_id'	=>	$u_id['id'],
		      		'login'		=>	$row['login'],
		      		'author'	=>	$row['author'],
		      		'title'		=>	$row['title'],
		      		'lat'		=>	$u_id['lat'],
		      		'lon'		=>	$u_id['lon']
	      		];
		      }
           }
      }
      
      echo json_encode($result_array);

    } else {
      	$result = [];
      	$result['error_code'] = '1';
      	$result['error_text'] = 'Invalid token';
    	echo json_encode($result);
    }
  } else {
    $result = [];
    $result['error_code'] = '2';
    $result['error_text'] = 'Fill all fields!';
    echo json_encode($result);
  }
}
?>