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
$m12 = isset($_POST["m12"]) ? trim($_POST["m12"]) : 0;
$ordem = isset($_POST["ordem"]) ? trim($_POST["ordem"]) : 'N';
$grupo = isset($_POST["grupo"]) ? trim($_POST["grupo"]) : 'ine';
$mcabecalho = isset($_POST["mcabecalho"]) ? trim($_POST["mcabecalho"]) : 1;
$cfa = isset($_POST["cfa"]) ? trim($_POST["cfa"]) : 0;
$texto = "<?php
\$dti = ".$dti.";
\$dtf = ".$dtf.";
\$gpa = ".$gpa.";
\$m12 = ".$m12.";
\$paginacao = ".$paginacao.";
\$ordem = '".$ordem."';
\$grupo = '".$grupo."';
\$mcabecalho = ".$mcabecalho.";
\$cfa = ".$cfa.";
\$des = ".$des.";
?>\r\n";
$file = "config/c_rel_h_".$_SESSION['key'].".php";
if (file_exists($file)){unlink($file);}
$fconfig = fopen($file,'w');
fwrite($fconfig, $texto);
fclose($fconfig);
?>