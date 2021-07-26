<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   15/07/2021
//   Rodrigo Silva
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

require_once('config/users.php');
session_start();

$login = isset($_POST["login"]) ? addslashes(trim($_POST["login"])) : FALSE;
$senha = isset($_POST["senha"]) ? trim($_POST["senha"]) : FALSE;
//$senha = isset($_POST["senha"]) ? md5(trim($_POST["senha"])) : FALSE;
if(!$login || !$senha) {
	echo "Você deve digitar sua senha e login!";
	exit;
}

$resultado = false;

$key = -1;
for($i=0;$i<=count($sys_log);$i++){
	if ($login == $sys_log[$i]['login']){
		$key = $i;
		break;
	}
}

if ($key >= 0){
	if ($login == $sys_log[$key]['login']){
		if ($senha == $sys_log[$key]['senha']){
			$resultado = true;
		}
	}
}

if($resultado) {
	$_SESSION['login'] = $login;
	$_SESSION['perfil'] = $sys_log[$key]['perfil'];
	$_SESSION['cnes'] = $sys_log[$key]['cnes'];
	$_SESSION['ine'] = $sys_log[$key]['ine'];
	$_SESSION['tema'] = $sys_log[$key]['tema'];
	$_SESSION['key'] = $key;
	header('location:principal.php');
	exit;
} else {
	unset ($_SESSION['login']);
	unset ($_SESSION['perfil']);
	unset ($_SESSION['cnes']);
	unset ($_SESSION['ine']);
	unset ($_SESSION['tema']);
	unset ($_SESSION['key']);
	header('location:index.php');
	exit;
}
?>