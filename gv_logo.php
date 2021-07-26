<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   15/07/2021
//   Rodrigo Silva
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
require_once('session.php');

$_UP['pasta'] = 'plugins/scsus/temas/'.$_SESSION['tema'].'/img/';
$_UP['tamanho'] = 1024 * 1024 * 2; // 2Mb
$_UP['extensoes'] = array('jpg');
$_UP['renomeia'] = true;
$_UP['erros'][0] = 'Não houve erro';
$_UP['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
$_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
$_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';
$_UP['erros'][4] = 'Não foi feito o upload do arquivo';
$msg_final = "";
if ($_FILES['arquivo']['error'] != 0) {
	die("Não foi possível fazer o upload, erro:\n" . $_UP['erros'][$_FILES['arquivo']['error']]);
	exit;
}
$extensao = strtolower(pathinfo($_FILES['arquivo']['name'], PATHINFO_EXTENSION));
if (array_search($extensao, $_UP['extensoes']) === false) {
	$msg_final .= "Er: Por favor, envie arquivos com as seguintes extensões: JPG\n";
} else if ($_UP['tamanho'] < $_FILES['arquivo']['size']) {
	$msg_final .= "Er: O arquivo enviado é muito grande, envie arquivos de até 2Mb\n";
} else {
	if ($_UP['renomeia'] == true) {
		$nome_final = '1logom_'.$_SESSION['key'].'.jpg';
	} else {
		$nome_final = $_FILES['arquivo']['name'];
	}
	if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $_UP['pasta'] . $nome_final)) {
		if (file_exists("plugins/scsus/temas/".$_SESSION['tema']."/img/logom_".$_SESSION['key'].".jpg")){
			unlink("plugins/scsus/temas/".$_SESSION['tema']."/img/logom_".$_SESSION['key'].".jpg");
		}
		if (file_exists("plugins/scsus/temas/".$_SESSION['tema']."/img/1logom_".$_SESSION['key'].".jpg")){
			rename("plugins/scsus/temas/".$_SESSION['tema']."/img/1logom_".$_SESSION['key'].".jpg", "plugins/scsus/temas/".$_SESSION['tema']."/img/logom_".$_SESSION['key'].".jpg");
			$msg_final = "Ok! Arquivo enviado";
		}
	} else {
		$msg_final .= "Er: Não foi possível enviar o arquivo, tente novamente\n";
	}
}
echo $msg_final;
?>
