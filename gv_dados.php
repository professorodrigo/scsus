<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   06/08/2021
//   Rodrigo Silva
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

require_once('session.php');
require_once('functions.php');
require_once('cfg/install.php');

$db = new SQLite3('db/scsus.db');

$cbnome = isset($_POST["cbnome"]) ? trim($_POST["cbnome"]) : "Secretaria Municipal de Saúde de Teste";
$cbend1 = isset($_POST["cbend1"]) ? trim($_POST["cbend1"]) : "Avenida Sete de Setembro, número 29387 - Sala 2";
$cbend2 = isset($_POST["cbend2"]) ? trim($_POST["cbend2"]) : "Bairro Matarazzo Caprinio";
$cbend3 = isset($_POST["cbend3"]) ? trim($_POST["cbend3"]) : "CEP 98732-980";
$cbend4 = isset($_POST["cbend4"]) ? trim($_POST["cbend4"]) : "Sao Matheus do Oeste Mineiro - MG";
$cbcont1 = isset($_POST["cbcont1"]) ? trim($_POST["cbcont1"]) : "+55 (47) 23432-9384 | +55 (47) 12384-3234";
$cbcont2 = isset($_POST["cbcont2"]) ? trim($_POST["cbcont2"]) : "contatosaude@saomatheus.gov.br | www.saomatheuspref.gov.br";
$ibge = isset($_POST["ibge"]) ? trim($_POST["ibge"]) : "0";
$popibge = isset($_POST["popibge"]) ? trim($_POST["popibge"]) : "0";
$poppar = isset($_POST["poppar"]) ? trim($_POST["poppar"]) : "0";
$popesf = isset($_POST["popesf"]) ? trim($_POST["popesf"]) : "0";
$classmun = isset($_POST["classmun"]) ? trim($_POST["classmun"]) : "Urbano";


$update = "
	UPDATE dados
	   SET cbnome = '".$cbnome."',
		   cbend1 = '".$cbend1."',
		   cbend2 = '".$cbend2."',
		   cbend3 = '".$cbend3."',
		   cbend4 = '".$cbend4."',
		   cbcont1 = '".$cbcont1."',
		   cbcont2 = '".$cbcont2."',
		   ibge = ".$ibge.",
		   popibge = ".$popibge.",
		   poppar = ".$poppar.",
		   popesf = ".$popesf.",
		   classmun = '".$classmun."' 
	WHERE id = '".$_SESSION['login']."';
";
$run_estrutura = $db->query($update);
$ctrli = 1;
//include 'plugins/ctrli/index.php';
?>