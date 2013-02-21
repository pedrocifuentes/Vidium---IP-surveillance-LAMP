<?php
session_start();

if(!$_SESSION['autenticado'] && isset($_SESSION['group_id'] != 1){
	session_destroy();
	header ("Location: /index.php");
	exit();
}

require_once "../config/config.php";
$dataBase = DataBase::getInstance();
$dataBase->setQuery("SELECT * FROM groups ORDER BY idgroup DESC;");
$listGroups = $dataBase->loadArrayList();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Bienvenido a Vidium</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">

<link rel="stylesheet" type="text/css" href="../css/reset.css" media="all"/>
<link rel="stylesheet" type="text/css" href="../css/text.css" media="all"/>
<link rel="stylesheet" type="text/css" href="../css/administrator-style.css" media="all"/>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="../js/jquery.form.js"></script> 

</head>
<body>

<div id="helpMenu" style="padding-right:10px;">
	<div id="container">
		<div class="logoMenu">
			<a href="home.php">
				<img border="0" width="150" src="../img/logo_vidium.png" alt="logo"/>
			</a>
		</div>
		<div class="childMenu">
			<ul style="padding-top:5px">
				<li><strong><?php echo $_SESSION['email'];?></strong></li>
								
				<li><a href="home.php">Grabadores</a></li>
				<?php if(isset($_SESSION['group_id']) && $_SESSION['group_id'] == 1):?>
					<li><a href="listUsers.php">Usuarios</a></li>
				<?php endif;?>
				<li><a href="https://sites.google.com/a/vidium.es/vidium-wiki/">Ayuda</a>
				<li><a id="close">Salir</a></li>
			</ul>
		</div>
	</div>
</div>
<div class="clear"></div>

<div id="container">
        <div id="content">
                <div id="node">
					<ul>
						<li><a href="home.php">Inicio</a> &#62; <a href="listUsers.php">Usuarios</a> &#62; <strong>Agregar usuario</strong></li>
					</ul>
				</div>
				<hr/>
                <div id="content_assistant">
					<form id="addForm" action="resource/action_add_user.php" method="post">
                                <div class="cdr_form_content">
									<div id="loading">
										<img src="/pms/images/loading4.gif" border="0" />
									</div>
									<div id="formInputs">
										<div class="rowform">
											<input type="text" name="email" id="email" onblur="if(this.value==''){this.value='Email'}" onfocus="if(this.value=='Email'){this.value=''}" value="Email" tabindex="1" class="textform">
											<div class="clear"></div>
										</div>
										<div class="rowform">
											<input type="password" class="textform" tabindex="1"  style="display:none;" name="password" id="password"  onblur="show_text(this, 'pass_text');"/>
											<input  name="pass_text" type="text" tabindex="2"  class="textform" id="pass_text"  onfocus="show_pass(this, 'password');" value="Password"/>
											<div class="clear"></div>
										</div>
										<div class="rowform">
											<input type="password" id="checkpassword" tabindex="3" name="checkpassword" value="" class="textform" style="display:none;" onblur="show_text(this, 'pass_text2');">
											<input name="pass_text2" type="text" tabindex="4"  class="textform" id="pass_text2"  onfocus="show_pass(this, 'checkpassword');" value="Confirmar password"/>																	<div class="clear"></div>
										</div>
										<div>
                                        <?php if(!empty($listGroups)):?>
										<?php foreach ($listGroups as $group):?>
											<div class="rowform">
												<div class="inputform">
													<input type="radio" value="<?php echo $group['idgroup']; ?>" checked="" name="group" id="group"><?php echo $group['title']; ?><br>
												</div>
											</div>
                                        <?php endforeach;?>
										<?php else:?>
											<div class="rowform">
												<span>No se ha encontrado ning&uacute;n grupo.</span>
											</div>
										<?php endif;?>
										</div>
									</div>
                                </div>
								<hr/>
                                <div id="formButtons" class="cdr_buttons">    
                                    &nbsp;&nbsp;<input type="submit" name="send" id="send" value="Agregar">
									<!--<input type="button" name="back" id="back" value="Ignorar" onClick="document.location='home.php'">-->
								</div>
                        </form>

                        <div class="clear"></div>
                </div>
                <div class="clear"></div>
				<hr/>
        </div>
        <div id="footer">
                <div class="clear"></div>
        </div>
        <div class="clear"></div>
</div>
<script>
$('#close').click(function(){
	location.href='resource/action_login.php?salir=true';
});

// prepara el formulario cuando el DOM está preparado
$(document).ready(function() {
    $('#addForm').ajaxForm({ 
        dataType:  'json',
		beforeSubmit:  showRequest, 
        success:   processJson 
    }); 
 
});

function showRequest(formData, jqForm){ 
	var form = jqForm[0];
	
	var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
	
	if (form.email.value == 'Email' || !form.email.value){
		alert ('Por favor, introduzca valores en los dos campos');
		return false;
	}else if(!emailReg.test(form.email.value)){
		alert("Email incorrecto");
		return false;
	}else if(!form.password.value || !form.checkpassword.value){
		alert ('Advertencia, escribir password dos veces');
		//clear_form_elements('#changeForm');
		return false;
	}else if(form.password.value != form.checkpassword.value){
		alert ('Advertencia, password es distinto al password');
		//clear_form_elements('#changeForm');
		return false;
	}else{
		return true;
	}
}

function processJson(data){
	if(data.result == "ok"){
        location.href='home.php';
    }else{
		alert(data.message);
		location.href='addUser.php';
    }
}

function show_text(id, textshow){
	if(id.value.length == 0){
		id.style.display='none';
		texto = document.getElementById(textshow);
		texto.style.display = 'inline';
	}
}
function show_pass(id, textshow){
	id.style.display='none';
	pass = document.getElementById(textshow);
	pass.style.display = 'inline';
	pass.focus();
}
 
function clear_form_elements(ele) {
	$(ele).find(':input').each(function() {
		switch(this.type) {
			case 'password':
			case 'select-multiple':
			case 'select-one':
			case 'text':
			case 'textarea':
			$(this).val('');
			break;
			case 'checkbox':
			case 'radio':
			this.checked = false;
		}
	});
} 
</script>



</body>
</html>
