<?php

/*
*   Файл авторизации пользователя
*/
include('../include/db_class.php');
require_once '../include/common.php';
header('Content-Type: application/json;charset=utf-8');
header('Accept: application/json');
session_start();

if (isset($_REQUEST["login"])) {
    if (!empty($_REQUEST['username']) && !empty($_REQUEST['pass'])) {
        $username=htmlspecialchars($_REQUEST['username']);
        $password=md5(htmlspecialchars($_REQUEST['pass']));
      	$db = new DataBase();
  		$query = $db->executeQuery("SELECT * FROM users WHERE `login`='".$username."' AND `password`='".$password."'");
        $numrows=mysqli_num_rows($query);
        if ($numrows !=0 ) {     
            while ($row=mysqli_fetch_assoc($query)) {
                $dbusername=$row['login'];
                $dbpassword=$row['password'];
            }
            if ($username == $dbusername && $password == $dbpassword) {             
                $_SESSION['session_username']=$username;
				$session = [];
              	$session['token'] = session_id();
              	$session['username'] = $_SESSION['session_username']; 
              	$insertQuery = $db->executeQuery("UPDATE users SET token='".$session['token']."' WHERE login='".$username."'");
              	echo json_encode($session);
            }
        } else {
          	$error = [];
          	$error['error_type'] = 1;
          	$error['error_text'] = 'Invalid username or password!';
            echo json_encode($error);
        }
        } else {
            $error = [];
          	$error['error_type'] = 2;
      		$error['error_text'] = 'Fill all fields!';
            echo json_encode($error);
        }
} 
