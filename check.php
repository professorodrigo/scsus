<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   06/08/2021
//   Rodrigo Silva
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
date_default_timezone_set('America/Sao_Paulo');
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
$ctlogin = 0;
$logado = date('dmYHis');
$result = $db->query("SELECT * FROM usuarios WHERE login = '".$login."'");

while($array = $result->fetchArray(SQLITE3_ASSOC)){
	if ($array['bloqueado'] == 0){
		if ($array['senha'] == $senha){
			$iduss = md5(date('Ymd').date('His'));
			$ctlogin = $array['ctlogin'] + 1;
			$db->query("UPDATE usuarios SET tent = 0, logado = '".$logado."', dtulogin = ".date('Ymd').", hrulogin = ".date('His').", ctlogin = ".$ctlogin.", iduss = '".$iduss."' WHERE login = '".$login."'");
			$_SESSION['login'] = $login;
			$_SESSION['logado'] = $logado;
			$_SESSION['perfil'] = $array['perfil'];
			$_SESSION['cnes'] = $array['cnes'];
			$_SESSION['ine'] = $array['ine'];
			$_SESSION['tema'] = $array['tema'];
			$mensagem = "Ok";
		} else {
			if ($array['tent'] >= 3){
				$db->query("UPDATE usuarios SET tent = 0, bloqueado = 1 WHERE login = '".$login."'");
				$result2 = $db->query("SELECT email, telefone FROM usuarios WHERE login = 'admin'");
				$admin_telefone = '';
				$admin_email = '';
				while($array2 = $result2->fetchArray(SQLITE3_ASSOC)){
					$admin_telefone = $array2['telefone'];
					$admin_email = $array2['email'];
				}
				$mensagem = "Usuário bloqueado, tentou mais de 3 vezes<br>
				Entre em contato com o administrador pelos contatos:<br>
				E-mail: ".$admin_email."<br>
				Telefone: ".$admin_telefone."<br>
				";
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
?>