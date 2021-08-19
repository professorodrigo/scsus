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

$login = isset($_POST["login"]) ? trim($_POST["login"]) : '';
$nome = isset($_POST["nome"]) ? trim($_POST["nome"]) : '';
$perfil = isset($_POST["perfil"]) ? trim($_POST["perfil"]) : 'admin';
$email = isset($_POST["email"]) ? trim($_POST["email"]) : 'usuario@usuario';
$telefone = isset($_POST["telefone"]) ? trim($_POST["telefone"]) : '(99)99999-9999';
$cnes = isset($_POST["cnes"]) ? trim($_POST["cnes"]) : '0000000';
$ine = isset($_POST["ine"]) ? trim($_POST["ine"]) : '0000000000';

$update = "
	UPDATE usuarios
	   SET nome = '".$nome."',
		   perfil = '".$perfil."',
		   email = '".$email."',
		   telefone = '".$telefone."',
		   cnes = '".$cnes."',
		   ine = '".$ine."'
	 WHERE login = '".$login."';
";
$run_estrutura = $db->query($update);

?>