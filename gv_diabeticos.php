<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   06/08/2021
//   Rodrigo Silva
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

require_once('session.php');
require_once('functions.php');

$db = new SQLite3('db/scsus.db');

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


$update = "
	UPDATE diabeticos
	   SET dti = '".$dti."',
		   dtf = '".$dtf."',
		   gpa = '".$gpa."',
		   m12 = '".$m12."',
		   cfa = '".$cfa."',
		   des = '".$des."',
		   paginacao = '".$paginacao."',
		   mcabecalho = '".$mcabecalho."',
		   grupo = '".$grupo."',
		   ordem = '".$ordem."'
	 WHERE id = '".$_SESSION['login']."';
";
$run_estrutura = $db->query($update);

?>