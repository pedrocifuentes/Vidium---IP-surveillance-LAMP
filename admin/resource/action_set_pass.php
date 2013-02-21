<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"]."/config/config.php";
$userId = $_SESSION['userId'];
settype($userId, "integer");
$newPass = $_REQUEST['password'];
$confirmPass = $_REQUEST['checkpassword'];

if (isset($newPass, $confirmPass) && !empty($newPass)) {
	if($newPass == $confirmPass){
		$dataBase = DataBase::getInstance();
		$dataBase->setQuery("UPDATE `users` SET `password` = '".md5($newPass)."' WHERE `iduser` ='".$userId."';");
		if ($dataBase->execute()){
			$message="Se actualizo de forma correcta";
			$result = "ok";
		}else{
			$message="Error, no se actualizo el password, intentelo nuevamente";
			$result = "error";
		}
	}else{
		$message="Advertencia, password es distinto al password";
		$result = "error";
	}
}else{
	$message="Advertencia, completar campos de texto";
	$result = "error";
}

//json redirect page, error login
echo '{ "result": "' . $result . '", "message": "' . $message . '" }';
?>