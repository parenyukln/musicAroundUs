<?php

include('../include/db_class.php');
require_once '../include/common.php';
header('Content-Type: application/json;charset=utf-8');
header('Accept: application/json');
session_start();

if ($_REQUEST['post_music']) {
  if (!empty($_REQUEST['author']) && !empty($_REQUEST['title']) && !empty($_REQUEST['lat']) && !empty($_REQUEST['lon']) && !empty($_REQUEST['token'])) {
    $author = $_REQUEST['author'];
    $title = $_REQUEST['title'];
    $lat = $_REQUEST['lat'];
    $lon = $_REQUEST['lon'];
    if (isset($_REQUEST['description'])) {
      $description = $_REQUEST['description'];
    } else {
      $description = '';
    }
    $request_token = $_REQUEST['token'];
    
    $db = new DataBase();
    // Проверяем есть ли запрашиваемый токен в бд
    $token_query = $db->checkToken($request_token);
    $numrows=mysqli_num_rows($token_query);
    if ($numrows != 0 ) {     
      while ($row=mysqli_fetch_assoc($token_query)) {
        $user_id=$row['id'];
      }
      
      // Заносим данные по ГЕО
      // Если данные гео по пользовотелю уже существуют, тогда UPDATE, если нет, тогда INSERT
      $select_query = $db->executeQuery("SELECT geo_id FROM geo_personal WHERE user_id='".$user_id."'");
      $numrowsel=mysqli_num_rows($select_query);
      $result = [];
      $cur_time = date("Y-m-d H:i:s");
      if ($numrowsel != 0 ) {
      	// Обновляем данные по гео_id
      	while ($row=mysqli_fetch_assoc($select_query)) {
	        $geo_id=$row['geo_id'];
	      }
        $update_geo = $db->executeQuery("UPDATE geo SET lat='".$lat."', lon='".$lon."', time='".$cur_time."' WHERE id='".$geo_id."'");
        $result['success'] = '2';
        $result['success_text'] = 'Geo data updated';
      } else {
        // Добавляем геоданные в таблицу geo если у этого пользователя нет записи
      	$insert_geo = $db->executeQuery("INSERT INTO geo (lat,lon,time) VALUES ('".$lat."','".$lon."','".$cur_time."')");
      	// Также добавляем запись в гео персонал с id записи, которую только что вставили в гео
      	$inserted_geo_id = $db->getInsertedId();
      	$insert_geo_personal = $db->executeQuery("INSERT INTO geo_personal (user_id,geo_id) VALUES ('".$user_id."','".$inserted_geo_id."')");
        $result['success'] = '1';
        $result['success_text'] = 'Geo data inserted';
      }
      
      // Добавляем данные музыки в таблицу (даже если есть дубли) c учетом юзера, которые добавил песню
      $insert_music = $db->executeQuery("INSERT INTO music (author,title,description,user_id) VALUES ('".$author."','".$title."','".$description."','".$user_id."')");
      // Выбираем последнюю добавленную запись пользователя
      $music_id = $db->getInsertedId();

      // Заносим данные в таблицу истории, где хранится инфа о песне, которую сейчас слушает пользователь
      $history_insert = $db->executeQuery("INSERT INTO music_history (music_id,user_id) VALUES ('".$music_id."','".$user_id."')");
      
      $result['success_text'] .= ' Track successfully added';
      
      echo json_encode($result);
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