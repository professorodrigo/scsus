<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   06/08/2021
//   Rodrigo Silva
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
header("Content-type: text/html; charset=utf-8");
require_once('functions.php');
$_UP['pasta'] = 'sigtap/';
$_UP['tamanho'] = 1024 * 1024 * 2; // 2Mb
$_UP['extensoes'] = array('zip');
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
//$extensao = strtolower(end(explode('.', $_FILES['arquivo']['name'])));
$extensao = strtolower(pathinfo($_FILES['arquivo']['name'], PATHINFO_EXTENSION));
if (array_search($extensao, $_UP['extensoes']) === false) {
	$msg_final .= "Er: Por favor, envie arquivos com as seguintes extensões: zip\n";
} else if ($_UP['tamanho'] < $_FILES['arquivo']['size']) {
	$msg_final .= "Er: O arquivo enviado é muito grande, envie arquivos de até 2Mb\n";
} else {
	if ($_UP['renomeia'] == true) {
		//$nome_final = time().'.jpg';
		$nome_final = 'sigtap.zip';
	} else {
		$nome_final = $_FILES['arquivo']['name'];
	}
	if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $_UP['pasta'] . $nome_final)) {
		$zip = new ZipArchive;
		//apt-get install -y php-zip
		if ($zip->open('sigtap/sigtap.zip') === TRUE) {
			$zip->extractTo('sigtap/');
			$zip->close();
			if (file_exists('sigtap/sigtap.zip')){
				unlink('sigtap/sigtap.zip');
			}
			unlink('sigtap/DATASUS - Tabela de Procedimentos - Lay-out.xls');
			unlink('sigtap/config.inf');
			unlink('sigtap/versao');
			$msg_final = "Ok! Arquivo processado";
		} else {
			$msg_final .= "Er: Não foi possível descompactar o arquivo\n";
		}
	} else {
		$msg_final .= "Er: Não foi possível enviar o arquivo, tente novamente\n";
	}
}
echo $msg_final;
?>
