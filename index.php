<?php
session_start();
$_SESSION['autenticado'] = false;
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
					$('input[type="submit"]').each(function() {
						PIE.attach(this);
					});
				}
			});
		</script>
		<![endif]-->
		
	</head>
<body>
<div class="clear"></div>
<div id="container">
	<div class="clear"></div>
	<div id="content">
		<div style="width:940px;margin-top:10%;" id="content_assistant">
			<h1>Bienvenido a Vidium, vigilancia f&aacute;cil</h1>
			<hr/>
			<div class="bannerindex">
				<img width="450" src="img/vidium-mobile.jpeg" alt="vidium mobile" />
			</div>
			<div id="form-content" class="formindex">
				<form target="targetdiv" method="post" action="admin/resource/action_login.php" id="loginForm"> 
					<!--<div class="cdr_form">-->
					<div class="cdr_form_content">
						<div class="rowform">
							<input type="text" class="textform" tabindex="1" value="Email" onfocus="if(this.value=='Email'){this.value=''}" onblur="if(this.value==''){this.value='Email'}" id="email" name="email">
						</div>
						<div class="rowform">
							<!--<input type="password" class="textform" tabindex="2" value="123" onfocus="if(this.value=='123'){this.value=''}" onblur="if(this.value==''){this.value='123'}" id="passwordd" name="passwordd">-->
							<input type="password" class="textform" tabindex="2"  style="display:none;" name="passwordd" id="passwordd"  onblur="show_text(this);"/>
							<input  name="pass_text" type="text" tabindex="3"  class="textform" id="pass_text"  onfocus="show_pass(this);" value="Password"/>
							<br/>
							<a href="newPass.php">*Recordar password<a>
						</div>
						<div align="right" class="rowform">
							<input type="submit" value="Entrar" id="send" name="send"/>
						</div>
						<div class="rowform">
							<br/>
							<br/>
							<a style="font-size:16px;" href="newUser.php"><strong>Reg&iacute;strate</strong></a><br/>
							<p>Es gratis y lo seguir&aacute; siendo</p>
						</div>
					</div>
				</form>
			</div>
			<hr/>
			<div class="clear"></div>
			<div class="menuindex">
				<ul>
					<!--<li><a href="index.php">Iniciar sesi&oacute;n</a></li>-->
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
    var checkValue = $('input[name=web]').fieldValue();
    
	
	if (!form.email.value || !form.passwordd.value){
		alert ('Por favor, introduzca valores en los dos campos');
		return false;
	}else if (form.email.value == 'Email' || form.passwordd.value == '123'){
		alert ('Por favor, introduzca valores en los dos campos');
		return false;
	}else if(!emailReg.test(form.email.value)){
		alert("Email incorrecto");
		return false;
	}
	
	$("#form-content").mask("Esperando...");
	
}
function processJson(data) {
	$("#form-content").unmask();
	if(data.aut == "ok"){
        location.href='admin/'+data.redir;
    }else{
		alert(data.message);
        location.href='index.php';
    }
}
function show_text(id){
	if(id.value.length == 0){
		id.style.display='none';
		texto = document.getElementById("pass_text");
		texto.style.display = 'inline';
	}
}
function show_pass(id){
	id.style.display='none';
	pass = document.getElementById("passwordd");
	pass.style.display = 'inline';
	pass.focus();
} 
</script>
</body></html>