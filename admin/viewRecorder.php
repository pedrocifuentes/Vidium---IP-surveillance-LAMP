<?php 
session_start();
if(!$_SESSION['autenticado']){
	session_destroy();
	header ("Location: /index.php");
	exit();
}

function getIpClient(){
	$ip = 0;
	if (!empty($_SERVER["HTTP_CLIENT_IP"]))
			$ip = $_SERVER["HTTP_CLIENT_IP"];
	if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
		$iplist = explode(", ", $_SERVER["HTTP_X_FORWARDED_FOR"]);
		if ($ip){
				array_unshift($iplist, $ip);
				$ip = 0;
		}
		foreach($iplist as $v)
				if (!eregi("^(192\.168|172\.16|10|224|240|127|0)\.", $v))
				return $v;
	}
	return ($ip) ? $ip : $_SERVER["REMOTE_ADDR"];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html style="height: 100%;" lang="es" xmlns="http://www.w3.org/1999/xhtml">
	<head> 
		<title>Ver grabador</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="../css/reset.css" media="all"/>
		<link rel="stylesheet" type="text/css" href="../css/text.css" media="all"/>
		<link rel="stylesheet" type="text/css" href="../css/administrator-style.css" media="all"/>
		
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
		<script type="text/javascript" src="../js/jquery.jsonp-2.1.4.min.js"></script>
		<script type="text/javascript" src="../js/jquery.cookie.js"></script>
		<?php //include "pie.php";?>
	</head>
	<body style="height: 100%;">
		<div id="helpMenu" style="position: absolute; top:0; right:0; border: 0px;width:100%;">
			<div class="logoMenu" style="padding-left:15px">
				<a href="home.php">
					<img border="0" width="150" src="../img/logo_vidium.png" alt="logo"/>
				</a>
			</div>
			<div class="childMenu" style="margin-top:5px;padding-right:15px">
				<?php 
					if(!empty($_REQUEST['publicip']) && !empty($_REQUEST['privateip'])){
						$ipClient =	getIpClient();
						if($ipClient == $_REQUEST['publicip']){
							$ipRecorder = $_REQUEST['privateip'];
						}else{
							$ipRecorder = $_REQUEST['publicip'];
						}
						echo '<script>$.cookie("LocalHostIp", "'.$ipRecorder.'");</script>';
					}else{
						echo '<script>$.cookie("LocalHostIp", null);</script>';
					}
				?>
				<ul>
					<li><strong><?php echo $_SESSION['email'];?></strong></li>
					<li><a href="home.php">Grabadores</a></li>
					<li><a href="https://sites.google.com/a/vidium.es/vidium-wiki/">Ayuda</a></li>
					<li><a id="close">Salir</a></li>
				</ul>
			</div>
			<div class="clear"></div>
		</div>
		<div id="loading">
			<img alt="loading" src="../img/loading.gif" border="0" />
		</div>
		
		<div id="divhome" style="margin:auto;margin-top:200px;width:500px;">
			<div align="center" id="safari"></div>
			<img width="450" src="../img/vidium-mobile.jpeg" alt="vidium mobile"/>
		</div>
		<div id="divDestino" style="height: 100%;"></div>
		
		<?php 
		#Encrypting key data
		$getData = base64_encode($_SESSION['email']);
		$getDataUser = base64_encode(md5($_SESSION['email'].date('d/m/Y')));
		
		#jsonData for jsonp
		$jsonData = '{ "email": "' . $_SESSION['email'] . '","securityData": "' . md5($_SESSION['email'].date('d/m/Y')) . '" }';		
		?>
		
	<script type="text/javascript">
				//alert($.cookie('LocalHostIp'));
				//var permission = $.cookie('permission');
				var localhostIpactive = $.cookie('localhostIpactive');
				var sessionId;
				$('#close').click(function(){
					if(!localhostIpactive){
						location.href='resource/action_login.php?salir=true';
					}else{
						closeSession();
					}
				});
				$(document).ready(function(){
					var userAgent = navigator.userAgent.toLowerCase();
					jQuery.browser = {
						version: (userAgent.match( /.+(?:rv|it|ra|ie|me)[\/: ]([\d.]+)/ ) || [])[1],
						chrome: /chrome/.test( userAgent ),
						safari: /webkit/.test( userAgent ) && !/chrome/.test( userAgent ),
						opera: /opera/.test( userAgent ),
						msie: /msie/.test( userAgent ) && !/opera/.test( userAgent ),
						mozilla: /mozilla/.test( userAgent ) && !/(compatible|webkit)/.test( userAgent )
					};
					
					if($.cookie('LocalHostIp') != null){
						var localhostIp = $.cookie('LocalHostIp');
						var myJSONObject = <?php echo $jsonData;?>; 
						if(localhostIp == ""){
							alert("seleccione grabador");
						}else{		
							if ($.browser.safari){
								$('div#safari').html(' ').append('<a id="safarilink" target="_blank" href="http://'+localhostIp+':9000/pms/webadmin/action_login_safari.php?getDataSafari=<?=$getData;?>&getDataUser=<?=$getDataUser;?>"><h2>Click AQU&Iacute; para ir al IDVR</h2></a>');
								return false;
							}
							if(!localhostIpactive){
								openSession(localhostIp, myJSONObject.email, myJSONObject.securityData);
							}else{
								closeAndOpenSession(localhostIp, myJSONObject.email, myJSONObject.securityData);
							}
						}
					}else{
						alert("No tiene permisos spara ver este grabador");
					}
				});
				
				function closeAndOpenSession(localhostIp, email, securityData){
					$.jsonp({
							url: "http://"+localhostIpactive+":9000/pms/api/loginRemote.php",
							callbackParameter: "callback",
							data: {"action": "close"},
							beforeSend: function (xOptions){
								viewLoaded();
							},
							success: function (json, textStatus) {
								json.sessionId;
								sessionId = "";
								openSession(localhostIp, email, securityData);
							},
							error: function (xOptions, textStatus) {
								errorDivs();
								alert("Error: la pagina no existe");
							}
					});
				}
				
				function closeSession(){
					$.jsonp({
						url: "http://"+localhostIpactive+":9000/pms/api/loginRemote.php",
						callbackParameter: "callback",
						data: {"action": "close"},
						success: function (json, textStatus) {
							location.href='resource/action_login.php?salir=true';
						}
					});
				}
				function openSession(localhostIp, emaill, securityDataa){
					$.jsonp({
							url: "http://"+localhostIp+":9000/pms/api/loginRemote.php",
							cache: true,
							pageCache :true,
							callbackParameter: "callback",
							data: {"email": emaill, "securityData": securityDataa},
							beforeSend: function (xOptions){
								viewLoaded();
								//alert("open session");
							}, 
							success: function (json, textStatus){
								localhostIpactive = localhostIp;
								//$("#loading img").hide();
								if (json.aut == "ok"){
									sessionId = json.sessionId;
									$('div#divDestino').html(' ').append('<iframe id="framevidium" name="targetdiv" width="100%" height="100%" src="http://'+localhostIp+':9000/pms/webadmin/home.php"/>');
									$.cookie('localhostIpactive', localhostIp);
									successDivs();		
								}else{
									$('div#divDestino').html(' ');
									errorDivs();
									alert(json.message);
								}
							},
							error: function (xOptions, textStatus) {
								$('div#divDestino').html(' ');
								errorDivs();
								alert("Error: la pagina no existe");
							}
					});
				}
				
				function viewLoaded(){
					$("#divhome").hide();
					$("#divDestino").hide();
					$("#loading img").show();
				}
				
				function successDivs(){
					$("#loading img").hide();
					$("#divDestino").show();
				}
				
				function errorDivs(){
					$("#loading img").hide();
					$("#divhome").show();
				}
		</script>	
	</body>
</html>