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


$update = "
	UPDATE gestantes
	   SET dti = '".$dti."',
		   dtf = '".$dtf."',
		   gpa = '".$gpa."',
		   des = '".$des."',
		   dpp = '".$dpp."',
		   dum = '".$dum."',
		   mconsultas = '".$mconsultas."',
		   tbodonto = '".$tbodonto."',
		   paginacao = '".$paginacao."',
		   mcabecalho = '".$mcabecalho."',
		   grupo = '".$grupo."',
		   ordem = '".$ordem."'
	 WHERE id = '".$_SESSION['login']."';
";
$run_estrutura = $db->query($update);


?>