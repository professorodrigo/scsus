<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   06/08/2021
//   Rodrigo Silva
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

require_once('session.php');

$ap = isset($_GET["ap"]) ? trim($_GET["ap"]) : '';
$fcsv = "csv/".$ap."_".$_SESSION['login'].".csv";

$fcarregar = "tabela.php?ap=".$ap;
if (!file_exists($fcsv)){
	$fcarregar = "er1.php";
}


?>
<div id="main-rel"></div>
<script>
	$(function () {
		$('#main-rel').load('<?php echo $fcarregar;?>');
	});
</script>