<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   15/07/2021
//   Rodrigo Silva
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

require_once('session.php');
require_once('functions.php');

$dtif = isset($_POST["dtif"]) ? $_POST["dtif"] : 0;
if ($dtif == 0){
	$dti = date('Ymd');
	$dtf = datasomadias(date('Ymd'),30);
	$ultimo_q = qfechado();
	$ano_q = substr($ultimo_q,3,4);
	$per_q = substr($ultimo_q,0,2);
	if ($per_q == 'Q1'){
		$dti = $ano_q.'0101';
		$dtf = $ano_q.'0430';
	}
	if ($per_q == 'Q2'){
		$dti = $ano_q.'0501';
		$dtf = $ano_q.'0831';
	}
	if ($per_q == 'Q3'){
		$dti = $ano_q.'0901';
		$dtf = $ano_q.'1231';
	}
} else {
	$dti = dataint(trim(substr($dtif,0,10)),'b');
	$dtf = dataint(trim(substr($dtif,13,10)),'b');	
}

$gpa = isset($_POST["gpa"]) ? trim($_POST["gpa"]) : 0;
$paginacao = isset($_POST["paginacao"]) ? trim($_POST["paginacao"]) : 0;
$ordem = isset($_POST["ordem"]) ? trim($_POST["ordem"]) : 'N';
$grupo = isset($_POST["grupo"]) ? trim($_POST["grupo"]) : 'ine';
$mcabecalho = isset($_POST["mcabecalho"]) ? trim($_POST["mcabecalho"]) : 1;
$des = isset($_POST["des"]) ? trim($_POST["des"]) : 0;
$ridade = isset($_POST["ridade"]) ? trim($_POST["ridade"]) : 0;
$cfa = isset($_POST["cfa"]) ? trim($_POST["cfa"]) : 0;
$tbusca = isset($_POST["tbusca"]) ? trim($_POST["tbusca"]) : 12;
$apvac = isset($_POST["apvac"]) ? trim($_POST["apvac"]) : 0;
$idin = isset($_POST["idin"]) ? trim($_POST["idin"]) : 0;
$idfi = isset($_POST["idfi"]) ? trim($_POST["idfi"]) : 150;
$proceds = isset($_POST["proceds"]) ? trim($_POST["proceds"]) : '';
$dt3anos = isset($_POST["dt3anos"]) ? trim($_POST["dt3anos"]) : 'U';
$texto = "<?php
\$dti = ".$dti.";
\$dtf = ".$dtf.";
\$gpa = ".$gpa.";
\$tbusca = ".$tbusca.";
\$apvac = ".$apvac.";
\$idin = ".$idin.";
\$idfi = ".$idfi.";
\$proceds = '".$proceds."';
\$paginacao = ".$paginacao.";
\$ordem = '".$ordem."';
\$grupo = '".$grupo."';
\$mcabecalho = ".$mcabecalho.";
\$ridade = ".$ridade.";
\$cfa = ".$cfa.";
\$dt3anos = '".$dt3anos."';
\$des = ".$des.";
?>\r\n";
$file = "config/c_rel_m_".$_SESSION['key'].".php";
if (file_exists($file)){unlink($file);}
$fconfig = fopen($file,'w');
fwrite($fconfig, $texto);
fclose($fconfig);
?>