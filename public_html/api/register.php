<?php

/**
 * Регистрация пользователя
 */
include('../include/db_class.php');
require_once '../include/common.php';
header('Content-Type: application/json');
 // Процесс регистрации
if (isset($_REQUEST["register"])) {	
    if (!empty($_REQUEST['name']) && !empty($_REQUEST['email']) && !empty($_REQUEST['login']) && !empty($_REQUEST['pass'])) {
        $name = htmlspecialchars($_REQUEST['name']);
        $email = htmlspecialchars($_REQUEST['email']);
        $username = htmlspecialchars($_REQUEST['login']);
        $password = md5(htmlspecialchars($_REQUEST['pass']));
      	$db = new DataBase();
  		$query = $db->executeQuery("SELECT * FROM users WHERE login='".$username."'");      
        $numrows = mysqli_num_rows($query);
        if ($numrows == 0) {
            $sql = "INSERT INTO users
                (name, email, login, password, token)
                VALUES('".$name."','".$email."', '".$username."', '".$password."','".$_COOKIE['PHPSESSID']."')";
            $result = $db->executeQuery($sql);
            if ($result) {
            	session_start();
              	$response = [];            
              	$response['success'] = 1;
              	$response['token'] = session_id();
              	$response['username'] = $username;
                echo json_encode($response);
            } else {
              	$response = [];
              	$response['error_type'] = 1;
              	$response['error_text'] = "Database error!";
                echo json_encode($response);
            }
        } else {
            $response = [];
          	$response['error_type'] = 2;
          	$response['error_text'] = "Login busy";
          	echo json_encode($response);
        }
    } else {
      	$response = [];
      	$response['error_type'] = 3;
      	$response['error_text'] = "Fill all fields!";
      	echo json_encode($response);
    } 
} 