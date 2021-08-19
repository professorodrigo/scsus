<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   06/08/2021
//   Rodrigo Silva
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

require_once('session.php');
require_once('functions.php');

$db = new SQLite3('db/scsus.db');

$senhaa = isset($_POST["senhaa"]) ? trim($_POST["senhaa"]) : '';
$senhan = isset($_POST["senhan"]) ? trim($_POST["senhan"]) : '';
$senhar = isset($_POST["senhar"]) ? trim($_POST["senhar"]) : '';

$db_senha = '';
$result = $db->query("SELECT senha FROM usuarios WHERE login = '".$_SESSION['login']."'");
while($array = $result->fetchArray(SQLITE3_ASSOC)){
	$db_senha = $array['senha'];
}

if ($db_senha == md5($senhaa)){
	if ($senhan == $senhar){
		$update = "
			UPDATE usuarios
			   SET senha = '".md5($senhan)."',
			   tmpsenha = 0
			 WHERE login = '".$_SESSION['login']."';
		";
		$run_estrutura = $db->query($update);
		echo "Ok";
	} else {
		echo "As senhas não estão iguais";
	}
} else {
	echo "Senha atual não confere";
}

?>