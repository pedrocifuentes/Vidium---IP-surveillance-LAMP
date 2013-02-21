<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/config/config.php";

session_start();

if(isset($_REQUEST['salir'])){
	session_destroy();
	header ("Location: /index.php");
	exit();
}

if(!empty($_POST['email']) || !empty($_POST['passwordd'])){
	
	$dataBase = DataBase::getInstance();
	$dataBase->setQuery("SELECT * FROM `users` WHERE MD5(email) = '".md5($_POST['email'])."' AND password = '".md5($_POST['passwordd'])."';");
	$result = $dataBase->loadArrayList();
	if(!empty($result)){
		foreach( $result as $field){
			$_SESSION['email'] = $field['email'];
			$_SESSION['userId'] = $field['iduser'];
			$_SESSION['group_id'] = $field['group_id'];
		}
		
		$_SESSION['autenticado'] = true;
		$aut = "ok";
		if($_POST['passwordd'] == 'vidium'){
			$redir = "changePass.php";
			$_SESSION['message'] = '<p>
					Est&aacute; utilizando el password predeterminado. Para protegerse de usuarios no autorizados 
					le sugerimos que cambie su password en 
					este momento. Por favor, seleccione un nuevo password que sea f&aacute;cil de recordar 
					pero dif&iacute;cil de adivinar para los dem&aacute;s. Le sugerimos combinar texto con 
					n&uacute;meros para que sea m&aacute;s dif&iacute;cil para un intruso adivinar.<br/> 
					Ingrese su nuevo password en los dos campos siguientes y haga clic en "Aceptar". 
					De lo contrario haga clic en "Ignorar" para guardar el password por defecto 
				</p>';
		}else{
			$redir = "home.php";
		}
	}else{
		$_SESSION['autenticado'] = false;
		$aut="error";
		$message = "Error: Datos incorrectos, compruebe nuevamente";
		$redir = "index.php";
	}
}else{
	$_SESSION['autenticado'] = false;
	$aut="error";
	$message = "Advertencia: Ingrese valores en los campos de texto";
	$redir = "index.php";
}

//json redirect page, error login
echo '{ "redir": "' . $redir . '","aut": "' . $aut . '", "message": "' . $message . '" }';

?>