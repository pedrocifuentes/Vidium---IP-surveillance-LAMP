<?php
session_start();

if(!$_SESSION['autenticado']){
	session_destroy();
    header ("Location: /");
	exit();
}
require_once "../config/config.php";
$dataBase = DataBase::getInstance();

settype($_SESSION['userId'], "integer");
$dataBase->setQuery("SELECT ur.id, ur.user_id, r.alias, ur.permit, r.publicip, r.privateip, r.updated, ur.flag
					FROM user_recorder AS ur
					JOIN recorders AS r ON ur.recorder_id = r.idrecorder WHERE ur.user_id = '".$_SESSION['userId']."';");
$listRecorders = $dataBase->loadArrayList();
$json_string = json_encode($listRecorders);
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
		
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="../js/jquery.form.js"></script> 
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
				<li><a href="changePass.php">Password</a></li>
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
				<li><strong>Lista grabadores</strong></li>
			</ul>
        </div>
		<hr/>
		<div id="content_assistant" style="width:940px;">
				<div class="cdrlog" style="display: none;">
                    <div id="detall"></div>
				</div>
				<div>
					<div id="showResult"></div>
					<?php
					/*$objectUsuario = new usuario();
												$listUsers = $objectUsuario->listUsers();
					$json_string = json_encode($listUsers);
					*/
					//var_dump($listRecorders);
					?>
					<table id="box-table-a" summary="Grabaciones" style="margin-bottom:0px;">
						<thead>
							<tr>
								<th scope="col" style="width:15%">Alias</th>
								<th scope="col" style="width:15%">Permiso</th>
								<th scope="col" style="width:25%">ip</th>
								<th scope="col" style="width:25%">Ultimo Ping</th>
								<th scope="col" style="width:10%">Estado</th>
								<th scope="col" style="width:10%">Acciones</th>
							</tr>
						</thead>
					</table>
						<?php $i = 1;?>
						<table style="margin-bottom:0px;" summary="Usuarios" id="box-table-a">
						<tbody>
						<?php if(!empty($listRecorders)):?>
						<?php foreach ($listRecorders as $recorder):?>
						<tr id="fila_<?php echo $recorder['id']; ?>">
							<td class="ver_<?php echo $recorder['id']; ?>" style="width:15%"><?php echo $recorder['alias'];//$i; ?></td>
							<td class="ver_<?php echo $recorder['id']; ?>" style="width:15%;">
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
							<td class="ver_<?php echo $recorder['id']; ?>" style="width:25%;"><?php echo $recorder['publicip']; ?></td>
							<td class="ver_<?php echo $recorder['id']; ?>" style="width:25%;"><?php echo $recorder['updated']; ?></td>
							<td class="ver_<?php echo $recorder['id']; ?>" style="width:10%">
								<?php 
									if($recorder['flag']==0){
										echo "Conectado";
									}else{
										echo 'Desconectado';
									}
								?>
							</td>
							<td style="width:10%;">
								<a id="borrar_<?php echo $recorder['id']; ?>"><img alt="Eliminar" src="../img/cross.png"></img></a>
							</td>
						</tr>
						<?php $i++;?>	
						<?php endforeach;?>
						<?php else:?>
							<tr>
								<td colspan="6">No se ha encontrado ning&uacute;n grabador.</td>
							</tr>
						<?php endif;?>
					</tbody>
					</table>
				</div>

		
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
		<hr/>
		<div class="cdr_buttons" id="formButtons">
			&nbsp;&nbsp;<input type="button" onclick="document.location='home.php'" value="Actualizar" id="refresh" name="refresh">
		</div>
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
$(document).ready(function() { 
	var jsonData = new Object();
        jsonData = <?php echo $json_string;?>; 
	
	$.each(jsonData, function(index, val) {
		//view recorder
		$(".ver_"+val.id).bind('click', function () {
			$(this).css( 'cursor', 'pointer');
			location.href='viewRecorder.php?publicip='+val.publicip+'&privateip='+val.privateip;
		}).css( 'cursor', 'pointer');
	
		$("#borrar_"+val.id).bind('click', function(){
			if(confirm('Desea eliminar ?')){
				$.ajax({
						url: 'resource/action_del_recorder.php',
						type: 'GET',
						async: true,
						dataType: 'json',
						data: "idpermit="+val.id,
						success: function(data){
							console.log($("#fila_"+val.id));
							$("#fila_"+val.id).html("");
							if(data.result != "ok"){
								alert(data.message);
							}
						}
				});
			}							
		});
	});
});
</script>
</body>
</html>
