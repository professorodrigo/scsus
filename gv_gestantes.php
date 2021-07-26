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

$dpp = isset($_POST["dpp"]) ? trim($_POST["dpp"]) : 294;
$gpa = isset($_POST["gpa"]) ? trim($_POST["gpa"]) : 0;
$dum = isset($_POST["dum"]) ? trim($_POST["dum"]) : 'A';
$paginacao = isset($_POST["paginacao"]) ? trim($_POST["paginacao"]) : 0;
$des = isset($_POST["des"]) ? trim($_POST["des"]) : 0;
$ordem = isset($_POST["ordem"]) ? trim($_POST["ordem"]) : 'N';
$grupo = isset($_POST["grupo"]) ? trim($_POST["grupo"]) : 'ine';
$mcabecalho = isset($_POST["mcabecalho"]) ? trim($_POST["mcabecalho"]) : 1;
$mconsultas = isset($_POST["mconsultas"]) ? trim($_POST["mconsultas"]) : 1;
$tbodonto = isset($_POST["tbodonto"]) ? trim($_POST["tbodonto"]) : 0;
$texto = "<?php
\$dti = ".$dti.";
\$dtf = ".$dtf.";
\$dpp = ".$dpp.";
\$gpa = ".$gpa.";
\$dum = '".$dum."';
\$paginacao = ".$paginacao.";
\$ordem = '".$ordem."';
\$grupo = '".$grupo."';
\$mcabecalho = ".$mcabecalho.";
\$mconsultas = ".$mconsultas.";
\$tbodonto = ".$tbodonto.";
\$des = ".$des.";
?>\r\n";
$file = "config/c_rel_g_".$_SESSION['key'].".php";
if (file_exists($file)){unlink($file);}
$fconfig = fopen($file,'w');
fwrite($fconfig, $texto);
fclose($fconfig);
?>