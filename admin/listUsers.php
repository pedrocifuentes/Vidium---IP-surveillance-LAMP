<?php
session_start();

if(!$_SESSION['autenticado'] && $_SESSION['group_id'] != 1){
	session_destroy();
	header ("Location: /index.php");
	exit();
}
require_once "../config/config.php";
$dataBase = DataBase::getInstance();
$dataBase->setQuery("SELECT u.iduser, u.email, g.title, u.created FROM users u, groups g WHERE u.group_id = g.idgroup;");
$listUsers = $dataBase->loadArrayList();

$json_string = json_encode($listUsers);
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
				<li><a href="home.php">Inicio</a> &#62; <strong>Usuarios</strong></li>
			</ul>
        </div>
		<hr/>
		<div id="content_assistant" style="width:958px;">
				<div class="cdrlog" style="display: none;">
                    <div id="detall"></div>
				</div>
				<div id="listData" style="width:100%">
					<div id="showResult"></div>
					<table id="box-table-a" summary="Grabaciones" style="margin-bottom:0px;">
						<thead>
							<tr>
								<th scope="col" style="width:10%">Id</th>
								<th scope="col" style="width:30%">E-mail</th>
								<th scope="col" style="width:15%">Tipo</th>
								<th scope="col" style="width:25%">Created</th>
								<th scope="col" style="width:5%"><!--Acciones-->&nbsp;</th>
							</tr>
						</thead>
					</table>
						<?php $i = 1;?>
						<table style="margin-bottom:0px;" summary="Usuarios" id="box-table-a">
						<tbody>
						<?php if(!empty($listUsers)):?>
						<?php foreach ($listUsers as $user):?>
						<tr id="fila_<?php echo $user['iduser']; ?>">
							<td class="ver_<?php echo $user['iduser']; ?>" style="width:10%"><?php echo $user['iduser'];//$i; ?></td>
							<td class="ver_<?php echo $user['iduser']; ?>" style="width:30%;"><?php echo $user['email']; ?></td>
							<td class="ver_<?php echo $user['iduser']; ?>" style="width:25%;"><?php echo $user['created']; ?></td>
							
							<td style="width:5%; padding:0px;">
								<a href="editUser.php?iduser=<?php echo $user['iduser']; ?>"><img alt="Editar" src="../img/edit.png"></img></a>
								<a id="borrar_<?php echo $user['iduser']; ?>"><img alt="Eliminar" src="../img/cross.png"></img></a>
								<img id="loader_<?php echo $user['iduser'];?>" width="16" border="0" src="../img/loading29.gif" style="display: none;">
							</td>
						</tr>
						<?php $i++;?>	
						<?php endforeach;?>
						<?php else:?>
							<tr>
								<td colspan="6">No se ha encontrado ning&uacute;n usuario.</td>
							</tr>
						<?php endif;?>
						</tbody>
					</table>
				</div>
				<!--<div id="detallData">
					<div id="showResult" style="padding:5px;">
						<ul>
							<li>Usuario: Su correo</li>
							<li>4 Grabadores asignados
								<ul>
									<li>1.- alias &nbsp;&nbsp;Permiso</li>
									<li>2.- alias &nbsp;&nbsp;Permiso</li>
									<li>3.- alias &nbsp;&nbsp;Permiso</li>
									<li>4.- alias &nbsp;&nbsp;Permiso</li>
								</ul>
							</li>
							<li><a href="#">Regresar</a></li>
						</ul>
					</div>
					<div id="loading2" style="display: none;padding:5px;">
						<img border="0" src="/pms/images/loading29.gif" style="display: none;">
						<div class="clear"></div>
					</div>
				</div>-->
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
		<hr/>
		
		<div class="cdr_buttons" id="formButtons">
		   <input type="button" onclick="document.location='home.php'" value="Atras" id="atras" name="atras">&nbsp;&nbsp;
		   <!--<input type="button" onclick="document.location='addUser.php'" value="Agregar" id="atras" name="atras">-->
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
		$("#borrar_"+val.iduser).bind('click', function(){
			if(confirm('Desea eliminar '+val.email+' ?')){
				$.ajax({
					url: 'resource/action_del_user.php',
					type: 'GET',
					async: true,
					dataType: 'json',
					data: "iduser="+val.iduser,
					beforeSend: function(data){
						$("#loader_"+val.iduser).show();
						$("#borrar_"+val.iduser).hide();
					},
					error: function(data){
						alert("Error al comunicar con el servidor");
					},
					complete: function(data){
						$("#loader_"+val.iduser).hide();
						$("#borrar_"+val.iduser).show();
					},
					success: function(data){
						$("#fila_"+val.iduser).html("");
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
