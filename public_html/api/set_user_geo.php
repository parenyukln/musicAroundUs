<?php

/*
* Старый скрипт добавления гео
*/

include('../include/db_class.php');
require_once '../include/common.php';
header('Content-Type: application/json;charset=utf-8');
header('Accept: application/json');
session_start();

if ($_REQUEST['set_geo']) {
  if (!empty($_REQUEST['lat']) && !empty($_REQUEST['lon']) && !empty($_REQUEST['zip']) && !empty($_REQUEST['token'])) {
    $lat = $_REQUEST['lat'];
    $lon = $_REQUEST['lon'];
    $zip = $_REQUEST['zip'];
    $request_token = $_REQUEST['token'];
    
    $db = new DataBase();
    // Проверяем есть ли запрашиваемый токен в бд
    $token_query = $db->checkToken($request_token);
    $numrows=mysqli_num_rows($token_query);
    if ($numrows != 0 ) {     
      while ($row=mysqli_fetch_assoc($token_query)) {
        $user_id=$row['id'];
      }
      
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
        $update_geo = $db->executeQuery("UPDATE geo SET zip='".$zip."', time='".$cur_time."' WHERE id='".$geo_id."'");
        $result['success'] = '2';
        $result['success_text'] = 'Geo data updated';
      } else {
        // Добавляем геоданные в таблицу geo если у этого пользователя нет записи
      	$insert_geo = $db->executeQuery("INSERT INTO geo (zip,time) VALUES ('".$zip."','".$cur_time."')");
      	// Также добавляем запись в гео персонал с id записи, которую только что вставили в гео
      	$inserted_geo_id = $db->getInsertedId();
      	$insert_geo_personal = $db->executeQuery("INSERT INTO geo_personal (user_id,geo_id) VALUES ('".$user_id."','".$inserted_geo_id."')");
        $result['success'] = '1';
        $result['success_text'] = 'Geo data inserted';
      }
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