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

$ulg = isset($_GET["ulg"]) ? trim($_GET["ulg"]) : '';
$fn = isset($_GET["fn"]) ? trim($_GET["fn"]) : '';

$db = new SQLite3('db/scsus.db');

if ($_SESSION['login'] != $ulg){
	if ($fn == 'dblo'){ // desbloquear
		$db->query("UPDATE usuarios SET tent = 0, bloqueado = 0 WHERE login = '".$ulg."'");
	}
	if ($fn == 'bloq'){ // desbloquear
		$db->query("UPDATE usuarios SET tent = 0, bloqueado = 1 WHERE login = '".$ulg."'");
	}
	if ($fn == 'dlog'){ // deslogar
		$db->query("UPDATE usuarios SET logado = '0' WHERE login = '".$ulg."'");
	}
	if ($fn == 'zt'){ // zerar tentativas
		$db->query("UPDATE usuarios SET tent = 0 WHERE login = '".$ulg."'");
	}
	if ($fn == 'sen'){ // senha
		$tmpsenha = rand(0,9).date('H').chr(rand(65,90)).date('s').chr(rand(97,122)).date('i');
		$db->query("UPDATE usuarios SET tent = 0, bloqueado = 0, logado = '0', tmpsenha = 1, senha = '".md5($tmpsenha)."' WHERE login = '".$ulg."'");
		// ###############################################################################################################################################
		// ###############################################################################################################################################
		// ###############################################################################################################################################
		
			$e_nome = '';
			$e_email = '';
			$result0 = $db->query("SELECT nome, email FROM usuarios WHERE login = '".$ulg."'");
			while($array0 = $result0->fetchArray(SQLITE3_ASSOC)){
				$e_nome = $array0['nome'];
				$e_email = $array0['email'];
			}
			if (strlen($e_email) > 10){
				// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				$em_assunto = "Troca de Senha (sc-SUS)";
				$em_msg = "
				Sua nova senha temporária é: ".$tmpsenha."<br>
				Altere a sua senha logo no próximo login!!<br><br>
				Seu usuário é: ".$ulg."<br>
				";
				$em_destinatario = $e_nome;
				$em_email_des = $e_email;
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
	if ($fn == 'ex'){ // exluir
		$db->query("DELETE FROM banco WHERE id = '".$ulg."';");
		$db->query("DELETE FROM dados WHERE id = '".$ulg."';");
		$db->query("DELETE FROM diabeticos WHERE id = '".$ulg."';");
		$db->query("DELETE FROM duplicados WHERE id = '".$ulg."';");
		$db->query("DELETE FROM gestantes WHERE id = '".$ulg."';");
		$db->query("DELETE FROM hipertensos WHERE id = '".$ulg."';");
		$db->query("DELETE FROM mulheres WHERE id = '".$ulg."';");
		$db->query("DELETE FROM vacinas WHERE id = '".$ulg."';");
		$db->query("DELETE FROM usuarios WHERE login = '".$ulg."';");
		delArqCor("csv",$ulg);
		delArqCor("html",$ulg);
		delArqCor("resumo",$ulg);
	}
}

?>
<script>
	$(function () {
	  <?php
	  if ($_SESSION['login'] == "admin"){
		  if ($fn == 'sen'){
			echo "alert('Nova senha: ".$tmpsenha."');"; 
		  }
	  }
	  if ($fn == 'ex'){
		  echo "$('#main-body').load('pg_usuarios.php');";
	  } else {
		  echo "$('#main-body').load('frm_usuario.php?ulg=".$ulg."');";
	  }
	  ?>
	});
</script>