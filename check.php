<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   15/07/2021
//   Rodrigo Silva
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
session_start();

$login = isset($_POST["login"]) ? addslashes(trim($_POST["login"])) : FALSE;
$senha = isset($_POST["senha"]) ? md5(trim($_POST["senha"])) : FALSE;
if(!$login || !$senha) {
	echo "Você deve digitar sua senha e login!";
	exit;
}

$db = new SQLite3('db/scsus.db');
$mensagem = "Usuário não encontrado<br>";
$tent = 0;
$logado = date('dmYHis');
$result = $db->query("SELECT * FROM usuarios WHERE login = '".$login."'");

while($array = $result->fetchArray(SQLITE3_ASSOC)){
	if ($array['bloqueado'] == 0){
		if ($array['senha'] == $senha){
			$db->query("UPDATE usuarios SET tent = 0, logado = '".$logado."' WHERE login = '".$login."'");
			$_SESSION['login'] = $login;
			$_SESSION['logado'] = $logado;
			$_SESSION['perfil'] = $array['perfil'];
			$_SESSION['cnes'] = $array['cnes'];
			$_SESSION['ine'] = $array['ine'];
			$_SESSION['tema'] = $array['tema'];
			$_SESSION['key'] = $login;
			$mensagem = "Ok";
		} else {
			if ($array['tent'] >= 3){
				$db->query("UPDATE usuarios SET tent = 0, bloqueado = 1 WHERE login = '".$login."'");
				$mensagem = "Usuário bloqueado, tentou mais de 3 vezes<br>";
			} else {
				$tent = $array['tent'] + 1;
				$db->query("UPDATE usuarios SET tent = ".$tent." WHERE login = '".$login."'");
				if ((3-$tent) == 0){
					$mensagem = "Foi sua última tentativa, se errar mais uma vez será bloqueado<br>";
				} else {
					$mensagem = "Você só tem mais ".(3-$tent)." tentativa(s)<br>";
				}
			}
		}
	} else {
		$mensagem = "Usuário bloqueado<br>";
	}
}

echo $mensagem;

/*
require_once('config/users.php');
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
*/
?>