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

$smtp_email = isset($_POST["smtp_email"]) ? trim($_POST["smtp_email"]) : '';
$smtp_usuario = isset($_POST["smtp_usuario"]) ? trim($_POST["smtp_usuario"]) : '';
$smtp_senha = isset($_POST["smtp_senha"]) ? trim($_POST["smtp_senha"]) : '';
$smtp_servidor = isset($_POST["smtp_servidor"]) ? trim($_POST["smtp_servidor"]) : '';
$smtp_porta = isset($_POST["smtp_porta"]) ? trim($_POST["smtp_porta"]) : '587';
$smtp_cript = isset($_POST["smtp_cript"]) ? trim($_POST["smtp_cript"]) : 'tls';
$smtp_html = isset($_POST["smtp_html"]) ? trim($_POST["smtp_html"]) : 'true';
$smtp_aut = isset($_POST["smtp_aut"]) ? trim($_POST["smtp_aut"]) : 'true';
$smtp_tsen = isset($_POST["smtp_tsen"]) ? trim($_POST["smtp_tsen"]) : 'false';

$update = "
	UPDATE config SET 
		smtp_email = '".$smtp_email."',
		smtp_usuario = '".$smtp_usuario."',
		smtp_senha = '".$smtp_senha."',
		smtp_servidor = '".$smtp_servidor."',
		smtp_porta = '".$smtp_porta."',
		smtp_cript = '".$smtp_cript."',
		smtp_html = '".$smtp_html."',
		smtp_aut = '".$smtp_aut."',
		smtp_tsen = '".$smtp_tsen."'
	 WHERE id = 1;
";
$run_estrutura = $db->query($update);

?>