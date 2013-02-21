<html>
	<head> 
		<link rel="stylesheet" type="text/css" href="../../css/reset.css" media="all"/>
		<link rel="stylesheet" type="text/css" href="../../css/text.css" media="all"/>
		<link rel="stylesheet" type="text/css" href="../../css/administrator-style.css" media="all"/>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
		<script type="text/javascript" src="../../js/jquery.cookie.js"></script>
		<?php
			echo '<script>$.cookie("permission", "", { expires: 7, path: "/" });</script>';
			echo '<script>$.cookie("localhostIpactive", "", { expires: 7, path: "/" });</script>';
		?>
	</head>
	<body>
<?php
session_start();
$post_vals = array(
    'email' => $_SESSION['email'],
    'securityData' => md5($_SESSION['email'].date('d/m/Y'))
);

foreach($post_vals as $key => $value) {
    $request .= $key.'='.urlencode($value).'&';
}
 
$request = rtrim($request, '&');
$iplocalhost = $_REQUEST['localhost'];

$ch = curl_init('http://'.$iplocalhost.':9000/pms/api/loginRemoteCurl.php');

$ret = curl_setopt($ch, CURLOPT_HEADER, 0);
$ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$ret = curl_setopt($ch, CURLOPT_POST,true);  
$ret = curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
$ret = curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$cookie_file = "/tmp/".time();
$ret = curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
$ret = curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
$ret = curl_exec($ch);

if(empty($ret)){
	curl_error($ch);
	echo '<script>$.cookie("permission", "error", { expires: 7, path: "/" });</script>';
	echo '<script>$.cookie("localhostIpactive", "'.$iplocalhost.'", { expires: 7, path: "/" });</script>';
	echo 'No se pudo conectar con el servidor';
	curl_close($ch);
	
}else{
	$info = curl_getinfo($ch);
	curl_close($ch);
	
	if(empty($info['http_code'])){
		echo '<script>$.cookie("permission", "error", { expires: 7, path: "/" });</script>';
		echo '<script>$.cookie("localhostIpactive", "'.$iplocalhost.'", { expires: 7, path: "/" });</script>';
		echo 'No retorno ningun Codigo HTTP';
	}else{
		echo '<script>$.cookie("permission", "ok", { expires: 7, path: "/" });</script>';
		echo '<script>$.cookie("localhostIpactive", "'.$iplocalhost.'", { expires: 7, path: "/" });</script>';
		echo '<script>location.href="http://'.$iplocalhost.':9000/pms/webadmin/permission.php";</script>';
	}
}
?>
</body>
</html>