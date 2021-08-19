<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   06/08/2021
//   Rodrigo Silva
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ini_set('display_errors', 0 );
error_reporting(0);

require_once('session.php');
require_once('functions.php');


// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$em_assunto = "Assunto";
$em_msg = "
mensagem linha 1<br>
mensagem linha 2
";
$em_destinatario = "Professor";
$em_email_des = "professorodrigo@gmail.com";
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'plugins/phpmailer/src/Exception.php';
require 'plugins/phpmailer/src/PHPMailer.php';
require 'plugins/phpmailer/src/SMTP.php';

$db = new SQLite3('db/scsus.db');
$smtp_email = '';
$smtp_usuario = '';
$smtp_senha = '';
$smtp_servidor = '';
$smtp_porta = 587;
$smtp_cript = 'tls';
$smtp_aut = 'true';
$smtp_html = 'true';
$result = $db->query("SELECT * FROM config WHERE id = 1");
while($array = $result->fetchArray(SQLITE3_ASSOC)){
	$smtp_email = $array['smtp_email'];
	$smtp_usuario = $array['smtp_usuario'];
	$smtp_senha = $array['smtp_senha'];
	$smtp_servidor = $array['smtp_servidor'];
	$smtp_porta = (int) $array['smtp_porta'];
	$smtp_cript = $array['smtp_cript'];
	$smtp_html = $array['smtp_html'];
	$smtp_aut = $array['smtp_aut'];
}
if (strlen($smtp_email.$smtp_servidor.$smtp_senha) > 10){
	$mail = new PHPMailer(true);
	$mail->isSMTP();
	if ($smtp_html == 'true'){
		$mail->isHTML(true);
	}
	if ($smtp_aut == 'true'){
		$mail->SMTPAuth = true;
	}
	$mail->CharSet = 'UTF-8';
	$mail->Username   = $smtp_usuario;
	$mail->Password   = $smtp_senha;
	$mail->SMTPSecure = $smtp_cript;
	$mail->Host = $smtp_servidor;
	$mail->Port = $smtp_porta;
	$mail->setFrom($smtp_email, 'sc-SUS');
	$mail->Subject = $em_assunto;
	$mail->Body    = $em_msg;
	$mail->AltBody = $em_msg;
	$mail->addAddress($em_email_des, $em_destinatario);
	$mail->send();
}

?>