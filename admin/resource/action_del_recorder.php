<?php
//session_start();
require_once $_SERVER["DOCUMENT_ROOT"]."/config/config.php";

$id = $_REQUEST['idpermit'];
settype($id, "integer");


if(!empty($id)){
	$dataBase = DataBase::getInstance();
	$dataBase->setQuery("DELETE FROM user_recorder WHERE id ='".$id."';");
	if ($dataBase->execute()){
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
//json redirect page, error login
echo '{ "result": "' . $isDelete . '", "message": "' . $message . '" }';

?>

