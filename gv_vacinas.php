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
$des = isset($_POST["des"]) ? trim($_POST["des"]) : 0;
$ordem = isset($_POST["ordem"]) ? trim($_POST["ordem"]) : 'N';
$cd3 = isset($_POST["cd3"]) ? trim($_POST["cd3"]) : 'OU';
$grupo = isset($_POST["grupo"]) ? trim($_POST["grupo"]) : 'ine';
$apvac = isset($_POST["apvac"]) ? trim($_POST["apvac"]) : 0;
$idin = isset($_POST["idin"]) ? trim($_POST["idin"]) : 0;
$idfi = isset($_POST["idfi"]) ? trim($_POST["idfi"]) : 1;
$imbios = isset($_POST["imbios"]) ? trim($_POST["imbios"]) : '33';
$mcabecalho = isset($_POST["mcabecalho"]) ? trim($_POST["mcabecalho"]) : 1;
$cfa = isset($_POST["cfa"]) ? trim($_POST["cfa"]) : 0;
$texto = "<?php
\$dti = ".$dti.";
\$dtf = ".$dtf.";
\$gpa = ".$gpa.";
\$apvac = ".$apvac.";
\$paginacao = ".$paginacao.";
\$idin = ".$idin.";
\$idfi = ".$idfi.";
\$imbios = '".$imbios."';
\$cd3 = '".$cd3."';
\$ordem = '".$ordem."';
\$grupo = '".$grupo."';
\$mcabecalho = ".$mcabecalho.";
\$cfa = ".$cfa.";
\$des = ".$des.";
?>\r\n";
$file = "config/c_rel_v_".$_SESSION['key'].".php";
if (file_exists($file)){unlink($file);}
$fconfig = fopen($file,'w');
fwrite($fconfig, $texto);
fclose($fconfig);
?>