<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/config/config.php";

if(isset($_POST['macAddress'], $_POST['apiKey'], $_POST['email']) && $_POST['apiKey'] == '-----'){
	
	$dataBase = DataBase::getInstance();	
	$i = 0;
	foreach($_POST['email'] as $email){
		//exist User??
		$dataBase->setQuery("SELECT * FROM `users` WHERE MD5(`users`.`email`) = '".md5($email)."';");
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
			$created=strftime("%Y-%m-%d-%H-%M-%S", time());
			$flag = 0;
			settype($_POST['permit'], "integer");
			//request exist relation user - recorder ????
			$dataBase->setQuery("SELECT * FROM `user_recorder` WHERE user_id = '".$idUser."' AND recorder_id = '".$idRecorder."';");
			$arrayUserRec=$dataBase->loadArrayList();
			if(empty($arrayUserRec)){
				
				$dataBase->setQuery("INSERT INTO `user_recorder` (`user_id`, `recorder_id`, `permit`, `created`, `flag`) 
											VALUES ('".$idUser."', '".$idRecorder."', '".$_POST['permit']."', '".$created."', '".$flag."');");
				if(!$dataBase->execute()){
					$jsonData['message'] = "Error, No se ha guardado el permiso (Email - grabador)";
				}else{
					$jsonData['message'] = "Ok, se actualizo de forma correcta";
				}
			}else{
				$jsonData['message'] = "Error, permiso ya existe en live.vidium.es";
			}
		}else{
			$jsonData['message'] = "Error, no existe Email en live.vidium.es";
		}
		$i++;
	}
	
	$json_string = json_encode($jsonData);
	echo $json_string;
}
?>
