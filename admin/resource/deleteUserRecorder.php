<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/config/config.php";
if(isset($_POST['macAddress'], $_POST['apiKey'], $_POST['email']) && $_POST['apiKey'] == '-----'){
	$dataBase = DataBase::getInstance();	
	
	//exist User??
	$dataBase->setQuery("SELECT * FROM `users` WHERE MD5(`users`.`email`) = '".md5($_POST['email'])."';");
	$arrayUser=$dataBase->loadArrayList();
	if(!empty($arrayUser)){
		foreach ($arrayUser as $field){
			$idUser = $field['iduser'];
		}
		if(!empty($idUser))
			$existUser = true;
	}
	//exist recorder??
	$dataBase->setQuery("SELECT * FROM `recorders` WHERE MD5(`recorders`.`hw_addr`) = '".md5($_POST['macAddress'])."';");
	$arrayRec=$dataBase->loadArrayList();
	if(!empty($arrayRec)){
		foreach ($arrayRec as $field){
			$idRecorder = $field['idrecorder'];
		}
		if(!empty($idRecorder))
			$existRec = true;
	}
	//if exist user and recorder.....
	if($existUser && $existRec){
		//delete exist relation user - recorder
		$dataBase->setQuery("DELETE FROM `user_recorder` WHERE `user_recorder`.`user_id` = '".$idUser."' AND `user_recorder`.`recorder_id` = '".$idRecorder."' LIMIT 1;");
		
		if($dataBase->execute()){
			$jsonData['message'] = $email.", ok, se ha eliminado el permiso (Email - grabador)";
		}else{
			$jsonData['message'] = $email.", error, no se ha eliminado el permiso (Email - grabador)";
		}	
	}else{
		$jsonData['message'] = $email.", error, no existe Email en live.vidium.es";
	}
	
	
	$json_string = json_encode($jsonData);
	echo $json_string;
}
?>
