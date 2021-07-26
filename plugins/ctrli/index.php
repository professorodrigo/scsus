<?php
/*
Esses dados são enviados apenas para estatística de instalação do projeto, nenhum outro dado é enviado além da data e dados pertinentes à versão.
Também é enviado o município que fez a instalação. Nenhum outra informação é enviada.
*/
ini_set('display_errors', 0 );
error_reporting(0);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'plugins/phpmailer/src/Exception.php';
require 'plugins/phpmailer/src/PHPMailer.php';
require 'plugins/phpmailer/src/SMTP.php';

if ($ctrli == 0){
	$em_assunto = "Nova instalação: ".$codinstall;
	$em_msg = "
	Código instalação: ".$codinstall."<br>
	Data: ".date('d/m/Y')."<br>
	Hora: ".date('H:i:s')."<br>
	Versão: ".$sobre['versao']."<br>
	Data alteração: ".$sobre['alteracao']."<br>
	";
} else {
	$em_assunto = "Alteração de município [".$codinstall."]";
	$em_msg = "
	Municipio: ".$cbend4."<br><br>
	Código instalação: ".$codinstall."<br>
	Data: ".date('d/m/Y')."<br>
	Hora: ".date('H:i:s')."<br>
	Versão: ".$sobre['versao']."<br>
	Data alteração: ".$sobre['alteracao']."<br>
	";	
}

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->CharSet = 'UTF-8';
$mail->SMTPAuth = true;
$mail->Username   = 'installscsus@outlook.com';
$mail->Password   = '@InstallS587458';
$mail->SMTPSecure = 'tls';
$mail->Host = 'smtp.live.com';
$mail->Port = 587;
$mail->setFrom('installscsus@outlook.com', 'Install sc-SUS');
$mail->addAddress('professorodrigo@gmail.com', 'Professor');
$mail->isHTML(true);
$mail->Subject = $em_assunto;
$mail->Body    = $em_msg;
$mail->AltBody = $em_msg;
$mail->send();

?>