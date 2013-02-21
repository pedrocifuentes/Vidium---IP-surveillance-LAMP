<?php
/*
Domain Name: 	live.vidium.es

This is a global key. It will work across all domains.
Public Key: 	6LdeTscSAAAAACvCHsIJH6MH9VIqyP0ckkLlmNmY

Use this in the JavaScript code that is served to your users
Private Key: 	6LdeTscSAAAAANgBvCKSuAxBi_gYu1a76ONGzdbl

Use this when communicating between your server and our server. Be sure to keep it a secret.
	Delete these keys
*/

require_once $_SERVER["DOCUMENT_ROOT"]."/config/config.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/class/class.phpmailer.php";
require_once "recaptchalib.php";

//key recapcha
$captcha_publickey = "6LdeTscSAAAAACvCHsIJH6MH9VIqyP0ckkLlmNmY";
$captcha_privatekey = "6LdeTscSAAAAANgBvCKSuAxBi_gYu1a76ONGzdbl";

if(!empty($_POST['email']) && !empty($_POST['check']) && !empty($_POST["recaptcha_challenge_field"]) && !empty($_POST["recaptcha_response_field"])){
	
	$captcha_resp = recaptcha_check_answer ($captcha_privatekey,
	$_SERVER["REMOTE_ADDR"],
	$_POST["recaptcha_challenge_field"],
	$_POST["recaptcha_response_field"]);
	
	if ($captcha_resp->is_valid){
		$_POST['email'] = str_replace(array('..','%00','?',':','/', '\\'),"",strtolower($_POST['email']));
		$dataBase = DataBase::getInstance();
		$dataBase->setQuery("SELECT * FROM `users` WHERE MD5(email) = '".md5($_POST['email'])."';");
        $result = $dataBase->loadArrayList();
        //var_dump($result);
		if(empty($result)){
			//save data
			$created=strftime("%Y-%m-%d-%H-%M-%S", time());
			$psswd = 'vidium';
			$emailUser = $_POST['email'];
			$dataBase->setQuery("INSERT INTO `users` (
																`email` ,
																`password` ,
																`created`
																)
																VALUES (
																'".$emailUser."', '".md5($psswd)."', '".$created."'
																);");
			if($dataBase->execute()){
				$message = "";
				$aut = "ok";
				
				$mail             = new PHPMailer();
				
				$body             = '<html>
										<head>
											<title>live.vidium.es</title>
										</head>
										<body>
										<h1>Bienvenido a Vidium</h1>
										<hr/>
										<br/>
										<br/>
										<ul>
											<li>Su password: <strong>vidium</strong></li>
										</ul>
										<br/>
										<a href="http://live.vidium.es">Ir a live.vidium.es</a>
										<br/>
										<br/>
										<hr/>
										</body>
									</html>';

				$mail->IsSMTP();
				$mail->Host       = "smtp.gmail.com";
				//$mail->SMTPDebug  = 2;                     
														   
														   
				$mail->SMTPAuth   = true;                  
				$mail->SMTPSecure = "ssl";                 
				$mail->Host       = "smtp.gmail.com";      
				$mail->Port       = 465;                   
				$mail->Username   = "no-reply@vidium.es";  
				$mail->Password   = "4raduqa9";            

				$mail->SetFrom('sistemas@vidium.es', 'Vidium, vigilancia fácil');

				$mail->AddReplyTo("no-reply@vidium.es","Vidium, vigilancia fácil");

				$mail->Subject    = "Bienvenido a Vidium";

				$mail->AltBody    = "Su password es: vidium";
				
				$mail->MsgHTML($body);
				
				$mail->AddAttachment($_SERVER["DOCUMENT_ROOT"]."/img/logo_vidium.png");
				
				$address = $_POST['email'];
				$mail->AddAddress($address, "");

				if(!$mail->Send()) {
					$message = "Error al enviar email: " . $mail->ErrorInfo;
					$aut="error";
				}
					
					
				}else{ 
					$message = "Error, no se guardo en la base de datos";
					$aut = "error";
				}
		}else{
			$message = "Advertencia, Email ya existe en la base de datos";
			$aut = "error";
		}
	}else{
		//El código de validación de la imagen está mal escrito.
		$message = "Has escrito mal una de las dos palabras";
		$aut = "error";
		$error_captcha = $captcha_resp->error;
	}		
}else{
	$message = "Por favor, ingrese datos";
	$aut = "error";
}
//json redirect page, error login
echo '{ "aut": "' . $aut . '", "message": "' . $message . '" }';
?>