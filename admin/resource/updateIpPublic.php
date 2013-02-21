<?php
//update ipPublic, ipPrivate, alias, alias, macAddress

require_once $_SERVER["DOCUMENT_ROOT"]."/config/config.php";

if(isset($_POST['apiKey'], $_POST['ipPrivate'], $_POST['ipPublic'], $_POST['alias'], $_POST['port'], $_POST['macAddress'])){

	$api_key = $_POST['apiKey'];
	$ipPublic = $_POST['ipPublic'];
	$ipPrivate = $_POST['ipPrivate'];
	$port = $_POST['port'];
	$alias = $_POST['alias'];
	$macAddress = $_POST['macAddress'];
	
	$created=strftime("%Y-%m-%d-%H-%M-%S", time());
	$flag = 0;

	if($api_key == '-----'){
		
		$dataBase = DataBase::getInstance();
		
		$dataBase->setQuery("SELECT * FROM `recorders` WHERE MD5(`recorders`.`hw_addr`) = '".md5($macAddress)."';");
		$arrayUser=$dataBase->loadArrayList();
		if(!empty($arrayUser)){
			//$dataBase->setQuery("UPDATE `recorders` SET `recorders`.`publicip` = '".$newIpPublic."' WHERE `recorders`.`publicip` ='".$ipPublic."';");
			$dataBase->setQuery("UPDATE `recorders` SET `alias` = '".$alias."',
														`publicip` = '".$ipPublic."',
														`hw_addr` = '".$macAddress."',
														`port` = '".$port."',
														`privateip` = '".$ipPrivate."',
														`updated` = '".$created."' WHERE `hw_addr` = '".$macAddress."';");
			
			if($dataBase->execute()){
				$jsonData['result'] = "ok, update";
			}else{
				$jsonData['result'] = "error update";
			}
		}else{
			$dataBase->setQuery("INSERT INTO `recorders` (
															`alias`,
															`publicip` ,
															`hw_addr`,
															`port`,
															`privateip`,
															`created`,
															`flag`
															)
															VALUES (
															'".$alias."', '".$ipPublic."', '".$macAddress."', '".$port."', '".$ipPrivate."', '".$created."','".$flag."'
															);");
			if($dataBase->execute()){
				$jsonData['result'] = "ok, insert";
			}else{
				$jsonData['result'] = "error insert";
			}
		}	
		
	}else{
		$jsonData['result'] = "error apikey";
	}

}else{
	$jsonData['result'] = "error variables";
}

$json_string = json_encode($jsonData);
echo $json_string;
?>
