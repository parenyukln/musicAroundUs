<?php

/*
*   В этом файле подключается БД и основные функции для работы приложения
*/

require_once 'config.php';
require_once 'db.php';

global $message;

// Подключение к БД
$link = mysqli_connect($db_info['host'], $db_info['user'], $db_info['pass'], $db_info['db_name']) 
    or die("Ошибка " . mysqli_error($link));
    
// Функция проверки на макс и мин
function checkMinMaxValues($minVal, $maxVal) {
	if ( $minVal > $maxVal ) {
		$temp = $maxVal;
		$maxVal = $minVal;
		$minVal = $temp;
	}
	
	$result = [
		'min' => $minVal,
		'max' => $maxVal
	];
	
	return $result;
}
