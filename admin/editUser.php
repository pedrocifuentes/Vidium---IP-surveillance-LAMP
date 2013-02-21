<?php
session_start();

if(!$_SESSION['autenticado'] && $_SESSION['group_id'] != 1){
	session_destroy();
	header ("Location: /");
	exit();
}

require_once "../config/config.php";
$dataBase = DataBase::getInstance();

if(isset($_REQUEST['iduser'])){

	$id = $_REQUEST['iduser'];
	settype($id, "integer");
	
	//Data user
	$dataBase->setQuery("SELECT * FROM users WHERE iduser =".$id.";");
	$dataUser = $dataBase->loadArrayList();
	
	//Data grabadores
	$dataBase->setQuery("SELECT ur.id, r.alias, ur.permit FROM user_recorder AS ur
					JOIN recorders AS r ON ur.recorder_id = r.idrecorder WHERE ur.user_id = '".$id."';");
	$listRecorders = $dataBase->loadArrayList();
	$json_string = json_encode($listRecorders);
	
	//Data groups
	$dataBase->setQuery("SELECT * FROM groups ORDER BY idgroup DESC;");
	$listGroups = $dataBase->loadArrayList();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Bienvenido a Vidium</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">

<link rel="stylesheet" type="text/css" href="../css/reset.css" media="all"/>
<link rel="stylesheet" type="text/css" href="../css/text.css" media="all"/>
<link rel="stylesheet" type="text/css" href="../css/administrator-style.css" media="all"/>
<link rel="stylesheet" type="text/css" href="../css/table.css" media="all"/>
<link rel="stylesheet" type="text/css" href="../css/jquery.loadmask.css" media="all" />

<script src="http://code.jquery.com/jquery-latest.pack.js" type="text/javascript"></script>
<script type="text/javascript" src="../js/jquery.form.js"></script>
<script type="text/javascript" src="../js/jquery.loadmask.js"></script> 
<?php include "pie.php";?>
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
					<li><a href="home.php">Inicio</a> &#62; <a href="listUsers.php">Usuarios</a> &#62; <strong>Editar</strong></li>
				</ul>
			</div>
			<hr/>
			<div id="content_assistant">
				<div id="form-content">
				<form id="addForm" action="resource/action_edit_user.php" method="post">
					<div class="cdr_form_content">
						<div id="loading">
							<img src="../img/loading.gif" border="0" />
						</div>
						<div id="formInputs" style="float:left;width:50%;margin:0;">
						<?php if(!empty($dataUser)):?>
						<?php foreach ($dataUser as $user):?>
							<div class="rowform">
								<input type="text" disabled="" name="email" id="email" onblur="if(this.value==''){this.value='Email'}" onfocus="if(this.value=='Email'){this.value=''}" value="<?php echo $user['email'];?>" tabindex="1" class="textform">
								<div class="clear"></div>
							</div>
							<div class="rowform">
								<input  name="password" type="text" tabindex="2"  class="textform" id="password"  onblur="if(this.value==''){this.value='Password'}" onfocus="if(this.value=='Password'){this.value=''}" value="Password"/>
							</div>
							<div class="rowform">
								<input name="checkpassword" type="text" tabindex="3"  class="textform" id="checkpassword"  onblur="if(this.value==''){this.value='Confirmar password'}" onfocus="if(this.value=='Confirmar password'){this.value=''}" value="Confirmar password"/>																	<div class="clear"></div>
							</div>
							
							<?php if(!empty($listGroups)):?>
							<div class="rowform">
								<div class="inputform">
								<strong>Permiso:&nbsp;</strong>
							<?php foreach ($listGroups as $group):?>
								<input type="radio" value="<?php echo $group['idgroup']; ?>" <?php if($user['group_id'] == $group['idgroup']) echo "checked";?> name="group" id="group"><?php echo $group['title']; ?>&nbsp;&nbsp;&nbsp;
							<?php endforeach;?>
								<input type="hidden" id="iduser" name="iduser" value="<?php echo $user['iduser'];?>">
								</div>
							</div>
							<?php else:?>
								<div class="rowform">
									<span>No se ha encontrado ning&uacute;n grupo.</span>
								</div>
							<?php endif;?>
						<?php endforeach;?>
						<?php endif;?>
							<div class="clear"></div>
						</div>
						
						<div id="recorAsig" style="float:right; width:49%;">
							<table id="box-table-a" summary="recorders" style="margin-bottom:0px;">
								<thead>
									<tr>
										<th scope="col" style="width:55%">Grabador</th>
										<th scope="col" style="width:35%">Permiso</th>
										<th scope="col" style="width:10%"></th>
									</tr>
								</thead>
							</table>
							<table id="box-table-a" summary="recorders" style="margin-bottom:0px;">
								<tbody>
									<?php if(!empty($listRecorders)):?>
									<?php foreach ($listRecorders as $recorder):?>
										<tr id="fila_<?php echo $recorder['id'];?>">
											<td scope="col" style="width:55%"><?php echo $recorder['alias'];//$i; ?></td>
											<td scope="col" style="width:35%">
												<?php 
													switch ($recorder['permit']){
														case 1:
															echo "Administrador";
															break;
														case 2:
															echo "Ver y Grabación";
															break;
														case 3:
															echo "Ver directo";
															break;
													}
												?>
											</td>
											<td scope="col" style="width:10%">
												<a id="borrar_<?php echo $recorder['id'];?>" href="#"><img alt="Eliminar" src="../img/cross.png"></img></a>
												<img id="loader_<?php echo $recorder['id'];?>" width="16" border="0" src="../img/loading29.gif" style="display: none;">
											</td>
										</tr>
									<?php endforeach;?>
									<?php else:?>
										<tr>
											<td colspan="3">No se ha encontrado ning&uacute;n grabador.</td>
										</tr>
									<?php endif;?>
								</tbody>
							</table>
						</div>
					</div>
					<hr/>
					<div id="formButtons" class="cdr_buttons">    
						<input type="button" name="back" id="back" value="Atras" onClick="document.location='listUsers.php'">
						&nbsp;&nbsp;<input type="submit" name="send" id="send" value="Enviar">
					</div>
				</form>
				<div class="clear"></div>
				</div>
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


	var jsonData = new Object();
	jsonData = <?php echo $json_string;?>;

	$.each(jsonData, function(index, val) {
		$("#borrar_"+val.id).bind('click', function () {
			if(confirm('Desea eliminar '+val.alias+' ?')){
					$.ajax({
						url: 'resource/action_del_recorder.php',
						type: 'GET',
						async: true,
						dataType: 'json',
						data: 'idpermit='+val.id,
						beforeSend: function(data){
						$("#loader_"+val.id).show();
							$("#borrar_"+val.id).hide();
						},
						error: function(data){
							alert("Error al comunicar con el servidor");
						},
						complete: function(data){
							$("#loader_"+val.id).hide();
							$("#borrar_"+val.id).show();
						},
						success: function(data){
							if(data.result == "error"){
									alert(data.message);
							}else{
									$("#fila_"+val.id).html("");
							}
						}
					});
			}
		});
	});



    $('#addForm').ajaxForm({ 
        dataType:  'json',
		beforeSubmit:  showRequest, 
        success:   processJson 
    }); 
 
});

function showRequest(formData, jqForm){ 
	var form = jqForm[0];
	
	if(form.password.value){
		if(form.password.value != 'Password'){
			if(form.password.value != form.checkpassword.value){
				alert("Escribe dos veces el password");
				return false;
			}
		}
    }
	
	$("#form-content").mask("Esperando...");
}

function processJson(data){
	$("#form-content").unmask();
	if(data.result != "error"){
		alert(data.message);
        location.href='listUsers.php';
    }else{
		alert(data.message);
		//location.href='editUser.php';
    }
} 
</script>



</body>
</html>
