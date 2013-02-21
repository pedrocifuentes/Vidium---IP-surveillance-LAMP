<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/config/config.php";

if(isset($_POST['group'], $_POST['iduser'])){
	
	$id = $_POST['iduser'];
	settype($id, "integer");
	
	$dataUser['group_id'] = $_POST['group'];
	settype($dataUser['group_id'], "integer");
	$dataUser['password'] = $_POST['password'];
	
	$message = "";
	$aut = "";
	
	$dataBase = DataBase::getInstance();
	foreach($dataUser as $field => $value){
		if($field == 'password'){
			if(!empty($value) && $value != "Password"){
				$dataBase->setQuery("UPDATE users SET ".$field."='".md5($value)."' WHERE iduser=".$id.";");
				$executesql = true;
			}
		}else{
			$dataBase->setQuery("UPDATE users SET ".$field."='".$value."' WHERE iduser=".$id.";");
			$executesql = true;
		}

		if($executesql){
			if(!$dataBase->execute()){
				$message = "Error, no se guardo en la base de datos";
				$aut = "error";
				break;
			}else{
				$message = "OK, se actualizo la base de datos";
				$aut = "ok";
			}
		}
	}
}else{
	$message = "Por favor, ingrese datos";
	$aut = "error";
}

echo '{ "aut": "' . $aut . '", "message": "' . $message . '" }';
?>