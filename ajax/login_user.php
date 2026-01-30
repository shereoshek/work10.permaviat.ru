<?php
	session_start();
	include("../settings/connect_datebase.php");
	require_once("../libs/recaptcha/autoload.php");
	
	$login = $_POST['login'];
	$password = $_POST['password'];
	
	if(isset($_POST['g-recaptcha-response'])==false){
		echo "Нет ответа от капчи";
		exit;
	}

	$Secret = "6LcCpFcsAAAAANSWcBl0tmDnSNk9KFOIxp-O6quw";
	$Recaptcha = new \ReCaptcha\ReCaptcha($Secret);
	$Responce = $Recaptcha->verify($_POST["g-recaptcha-response"], $_SERVER['REMOTE_ADDR']);

	if($Responce->isSuccess()){
		echo "Авторизация прошла";
	}else{
		echo "Пользователь не распознан";
	}
	
	// ищем пользователя
	$query_user = $mysqli->query("SELECT * FROM `users` WHERE `login`='".$login."' AND `password`= '".$password."';");
	$id = -1;

	while($user_read = $query_user->fetch_row()) {
		$id = $user_read[0];
	}
	
	if($id != -1) {
		$_SESSION['user'] = $id;
	}
	echo md5(md5($id));
?>