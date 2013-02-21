<?php
//require_once $_SERVER["DOCUMENT_ROOT"]."config/config.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/admin/resource/recaptchalib.php";
//Llaves de la captcha
$captcha_publickey = "6LdeTscSAAAAACvCHsIJH6MH9VIqyP0ckkLlmNmY";
$captcha_privatekey = "6LdeTscSAAAAANgBvCKSuAxBi_gYu1a76ONGzdbl";
//por ahora ponemos a null el error de la captcha
$error_captcha=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="es" xmlns="http://www.w3.org/1999/xhtml"> 
	<head> 
		<title>Login Web Admin</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="css/reset.css" media="all" />
		<link rel="stylesheet" type="text/css" href="css/text.css" media="all" />
		<link rel="stylesheet" type="text/css" href="css/administrator-style.css" media="all" />
		<link rel="stylesheet" type="text/css" href="css/jquery.loadmask.css" media="all" />
		
		<script src="http://code.jquery.com/jquery-latest.pack.js" type="text/javascript"></script>
		<script type="text/javascript" src="js/jquery.form.js"></script>
		<script type="text/javascript" src="js/jquery.loadmask.js"></script>
		
		<!--[if IE]>
		<script type="text/javascript" src="pie/PIE.js"></script>
		<script type="text/javascript">
			$(function() {
				if (window.PIE) {
					$('input[type="button"], input[type="submit"], #helpMenu').each(function() {
						PIE.attach(this);
					});
				}
			});
		</script>
		<![endif]-->
		
	</head>
<body>
<div id="helpMenu">
<div id="childMenu">
		<div class="logoMenu">
			<img border="0" width="150" src="img/logo_vidium.png" alt="logo"/>
		</div>
		<div class="clear"></div>
</div>
</div>
<div class="clear"></div>
<div id="container">
	<div id="content">
		<div style="width:940px;margin-top:10%;" id="content_assistant">
		<h1 align="right">Alta de correo electr&oacute;nico</h1>
		<hr/>
			<div class="bannerindex">
				<h2>C&oacute;mo funciona</h2>
				<p>
				Vidium permite ver tus c&aacute;maras est&eacute;s donde est&eacute;s, sin cargar ning&uacute;n software.<br/>
				Solo necesitas el navegador de tu ipad, m&oacute;vil &oacute; pc <strong>Tan sencillo como YouTube</strong>
				</p>
				<h2>Qu&eacute; necesitas</h2>
				<p>
				Un grabador NVR de vidium, <a><strong>P&iacute;delo aqu&iacute;</strong></a><br/>
				C&aacute;maras IP, <a><strong>Ver modelos compatibles</strong></a><br/>
				Una conexi&oacute;n a internet de banda ancha
				</p>
				<h2>Es gratis</h2>
				<p>
				Vidium no tiene cuotas de servicio ni gastos escondidos, podr&aacute;s acceder a tus c&aacute;maras<br/>
				tantas veces como quieras
				</p>
			</div>
			<div id="form-content" class="formindex" style="width: 34%;">
				<form target="targetdiv" method="post" action="admin/resource/action_new_user.php" id="loginForm"> 
					<div class="cdr_form_content">
						<div class="rowform">
							<input style="width: 303px;" type="text" class="textform" tabindex="1" value="Email" onfocus="if(this.value=='Email'){this.value=''}" onblur="if(this.value==''){this.value='Email'}" id="email" name="email">
						</div>
						<div class="rowform">
							<script type="text/javascript" >  
								var RecaptchaOptions = {  
									theme : 'white',//red, white, blackglass, clean, custom  
									lang: 'es',
									tabindex : 2
								};  
							</script>
							<?php
								echo recaptcha_get_html($captcha_publickey, $error_captcha);
							?>
						</div>
						<div class="rowform">
							<input type="checkbox" value="1" name="check" id="check"/>
							<label for="check">&#191;Aceptas t&eacute;rminos de uso&#63;</label> 	
						</div>
						<div align="right" class="rowform">
							<input type="submit" value="Dar de alta" id="send" name="send"/>
						</div>
					</div>
				</form>
			</div>
			<hr/>
			<div class="clear"></div>
			<div class="menuindex">
				<ul>
					<li><a href="index.php">Iniciar sesi&oacute;n</a></li>
					<li><a href="http://vidium.es/aviso_legal.php" target="_blank">Legal</a></li>
					<li><a href="#">Soporte</a></li>
					<li><a href="https://sites.google.com/a/vidium.es/vidium-wiki/" target="_blank">Ayuda</a></li>
					<li><a href="#">Contacto</a></li>
				</ul>
			</div>	
		</div>
		<div class="clear"></div>
	</div>
	
	<div id="footer">
		<div class="clear"></div>
	</div>
	<div class="clear"></div>
</div>
<script type="text/javascript">
// prepare the form when the DOM is ready 
$(document).ready(function() {
    $('#loginForm').ajaxForm({ 
        dataType:  'json',
		beforeSubmit:  showRequest, 
        success:   processJson 
    }); 
 
});
function showRequest(formData, jqForm) { 
	var form = jqForm[0];
	var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    var checkValue = $('input[name=check]').fieldValue();
	
	if (!form.email.value || form.email.value == 'Email:(necesario)') {
		alert ('Por favor, escribir email');
		return false;
	}else if(!emailReg.test(form.email.value)){
		alert("Email incorrecto");
		return false;
	}else if(!form.recaptcha_response_field.value){
		alert ('Por favor, escribir las dos palabras');
		return false;
	}else if(checkValue != '1'){
		alert ('Aceptar t\xe9rminos de uso para poder continuar');
		return false;
	}
	
	$("#form-content").mask("Esperando...");
}
function processJson(data) {
	$("#form-content").unmask();
	if(data.aut == "ok"){
		//alert("Ok: se guardo de forma correcta.\n\nSu contrase\xF1a es vidium.");
        alert("Ok: se guardo de forma correcta.\n\nSu password es vidium.");
		location.href='index.php';
    }else{
		alert(data.message);
        location.href='newUser.php';
    }
}
</script>
</body></html>