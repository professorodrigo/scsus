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

$nome = isset($_POST["nome"]) ? trim($_POST["nome"]) : '';
$email = isset($_POST["email"]) ? trim($_POST["email"]) : 'usuario@usuario';
$telefone = isset($_POST["telefone"]) ? trim($_POST["telefone"]) : '(99)99999-9999';


$update = "
	UPDATE usuarios
	   SET nome = '".$nome."',
		   email = '".$email."',
		   telefone = '".$telefone."'
	 WHERE login = '".$_SESSION['login']."';
";
$run_estrutura = $db->query($update);

?>