<?php
//session_start();
require_once $_SERVER["DOCUMENT_ROOT"]."/config/config.php";

$id = $_REQUEST['iduser'];
settype($id, "integer");

if(!empty($id)){
	$dataBase = DataBase::getInstance();
	$dataBase->setQuery("DELETE FROM users WHERE iduser ='".$id."';");
	if ($dataBase->execute()){
		$dataBase->setQuery("DELETE FROM user_recorder WHERE user_id ='".$id."';");
		$dataBase->execute();
		$result = true;
	}else $result = false;
	
	if($result){
		$message = "Se elimino el registro de la base de datos";
		$isDelete = "ok";
	}else{
		$message = "Error al elimina en la base de datos";
		$isDelete = "error";
	}
}else{
	$message = "Advertencia, completar los campos oblicatorios";
	$isDelete = "error";
}

echo '{ "result": "' . $isDelete . '", "message": "' . $message . '" }';

?>

