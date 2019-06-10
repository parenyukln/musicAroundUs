<?php
require_once 'include/common.php';
	session_start();
	unset($_SESSION['session_username']);
	session_unset();
	session_destroy();
	header("location:login.php");
	?>