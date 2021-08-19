<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   06/08/2021
//   Rodrigo Silva
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ini_set('display_errors', 0 );
error_reporting(0);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'plugins/phpmailer/src/Exception.php';
require 'plugins/phpmailer/src/PHPMailer.php';
require 'plugins/phpmailer/src/SMTP.php';

require_once('session.php');
require_once('functions.php');

$db = new SQLite3('db/scsus.db');

$login = isset($_POST["login"]) ? trim($_POST["login"]) : '';
$nome = isset($_POST["nome"]) ? trim($_POST["nome"]) : '';
$perfil = isset($_POST["perfil"]) ? trim($_POST["perfil"]) : 'usuario';
$email = isset($_POST["email"]) ? trim($_POST["email"]) : 'usuario@usuario';
$telefone = isset($_POST["telefone"]) ? trim($_POST["telefone"]) : '(99)99999-9999';
$cnes = isset($_POST["cnes"]) ? trim($_POST["cnes"]) : '0000000';
$ine = isset($_POST["ine"]) ? trim($_POST["ine"]) : '0000000000';

$tmpsenha = rand(0,9).date('H').chr(rand(65,90)).date('s').chr(rand(97,122)).date('i');
$inserir = "
		INSERT INTO usuarios (
			login, 
			nome,
			telefone,
			senha, 
			logado, 
			bloqueado, 
			tent, 
			cnes, 
			ine, 
			tema, 
			email, 
			perfil, 
			dtulogin, 
			hrulogin, 
			ctlogin,
			usenha,
			tptsenha,
			tmpsenha,
			iduss
		) VALUES (
			'".$login."', 
			'".$nome."',
			'".$telefone."',
			'".md5($tmpsenha)."', 
			'0', 
			0, 
			0, 
			'".$cnes."', 
			'".$ine."', 
			'padrao', 
			'".$email."', 
			'".$perfil."', 
			30001231, 
			0, 
			0,
			'".md5($tmpsenha)."',
			180,
			1,
			'".md5($tmpsenha)."'
		);
";

$rows = $db->query("SELECT COUNT(*) as count FROM usuarios WHERE login = '".$login."'");
$row = $rows->fetchArray();
if ($row['count'] <= 0){
	if (strlen($login) == 11){
		$run_estrutura = $db->query($inserir);
		echo $tmpsenha;
			// ###############################################################################################################################################
			// ###############################################################################################################################################
			// ###############################################################################################################################################
			
				if (strlen($email) > 10){
					// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
					$em_assunto = "Cadastro no sistema (sc-SUS)";
					$em_msg = "
					Sua senha temporária é: ".$tmpsenha."<br>
					Altere a sua senha no primeiro login!!<br><br>
					Seu usuário é: ".$login."<br>
					";
					$em_destinatario = $nome;
					$em_email_des = $email;
					// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
					$smtp_email = '';
					$smtp_usuario = '';
					$smtp_senha = '';
					$smtp_servidor = '';
					$smtp_porta = 587;
					$smtp_cript = 'tls';
					$smtp_aut = 'true';
					$smtp_html = 'true';
					$smtp_tsen = 'false';
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
						$smtp_tsen = $array['smtp_tsen'];
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
						//$mail->SMTPDebug = 1;
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
						if ($smtp_tsen == 'true'){
							$mail->send();
						}
					}
				}

			// ###############################################################################################################################################
			// ###############################################################################################################################################
			// ###############################################################################################################################################
	}	
} else {
	echo "Não foi criado, usuário já existe!";
}

?>