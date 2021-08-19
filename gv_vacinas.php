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
$ordem = isset($_POST["ordem"]) ? trim($_POST["ordem"]) : 'N';
$cd3 = isset($_POST["cd3"]) ? trim($_POST["cd3"]) : 'OU';
$grupo = isset($_POST["grupo"]) ? trim($_POST["grupo"]) : 'ine';
$apvac = isset($_POST["apvac"]) ? trim($_POST["apvac"]) : 0;
$idin = isset($_POST["idin"]) ? trim($_POST["idin"]) : 0;
$idfi = isset($_POST["idfi"]) ? trim($_POST["idfi"]) : 1;
$imbios = isset($_POST["imbios"]) ? trim($_POST["imbios"]) : '33';
$mcabecalho = isset($_POST["mcabecalho"]) ? trim($_POST["mcabecalho"]) : 1;
$cfa = isset($_POST["cfa"]) ? trim($_POST["cfa"]) : 0;
$tpb = isset($_POST["tpb"]) ? trim($_POST["tpb"]) : 12;
$d3c = isset($_POST["d3c"]) ? trim($_POST["d3c"]) : 0;


$update = "
	UPDATE vacinas
	   SET dti = '".$dti."',
		   dtf = '".$dtf."',
		   gpa = '".$gpa."',
		   idin = '".$idin."',
		   idfi = '".$idfi."',
		   tpb = '".$tpb."',
		   apvac = '".$apvac."',
		   imbios = '".$imbios."',
		   cd3 = '".$cd3."',
		   d3c = '".$d3c."',
		   ridade = '".$ridade."',
		   dt3anos = '".$dt3anos."',
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