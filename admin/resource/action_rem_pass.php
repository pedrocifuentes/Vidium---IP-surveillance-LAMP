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

if(!empty($_POST['email']) && !empty($_POST["recaptcha_challenge_field"]) && !empty($_POST["recaptcha_response_field"])){
	
	$captcha_resp = recaptcha_check_answer ($captcha_privatekey,
	$_SERVER["REMOTE_ADDR"],
	$_POST["recaptcha_challenge_field"],
	$_POST["recaptcha_response_field"]);
	
	if ($captcha_resp->is_valid) {
		//save data
		$dataBase = DataBase::getInstance();
		
		$dataBase->setQuery("SELECT * FROM `users` WHERE MD5(email) = '".md5($_POST['email'])."';");
        $result = $dataBase->loadArrayList();
        //var_dump($result);
		if(!empty($result)){
			$created=strftime( "%Y-%m-%d-%H-%M-%S", time() );
			$psswd = substr( md5(microtime()), 1, 8);
			
			$dataBase->setQuery("UPDATE `users` SET `password` = '".md5($psswd)."' WHERE `users`.`email` ='".$_POST['email']."';");
			if ($dataBase->execute()){
				$aut="ok";
				$message="";
				
				$mail             = new PHPMailer();
				$body             = '<html>
										<head>
											<title>live.vidium.es</title>
										</head>
										<body>
											<h1>Live vidium: Nuevo password</h1><hr/><br/><br/>
											<ul>
												<li>Su nueva password: <strong>'.$psswd.'</strong></li>
											</ul><br/><a href="http://live.vidium.es">Ir a live.vidium.es</a>
											<br/><br/><hr/>
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
				$mail->Subject    = "Reset password";
				$mail->AltBody    = "Su nuevo password es:".$psswd;
				$mail->MsgHTML($body);
				$mail->AddAttachment($_SERVER["DOCUMENT_ROOT"]."/img/logo_vidium.png");
				
				$address = $_POST['email'];
				$mail->AddAddress($address, "");

				if(!$mail->Send()) {
					$message = "Error al enviar email: " . $mail->ErrorInfo;
					$aut="error";
				}
			}else{
				$aut="error"; 
				$message = "Error, no se actualizo en la base de datos";
			}	
		}else{
			$aut="error"; 
			$message = "Error, Compruebe sus datos, email no existe.";
		}
	}else{
		$message = "Has escrito mal una de las dos palabras";
		$aut = "error";
		$error_captcha = $captcha_resp->error;
	}		
}else{
	$message = "Por favor, ingrese datos";
	$aut = "error";
}
//json redirect page, error login
echo '{ "message": "' . $message . '","aut": "' . $aut . '" }';
?>