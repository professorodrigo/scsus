<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   06/08/2021
//   Rodrigo Silva
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

require_once('session.php');

$ap = isset($_GET["ap"]) ? trim($_GET["ap"]) : '';
$fhtml = "html/".$ap."_".$_SESSION['login'].".html";

$fcarregar = $fhtml;
if (!file_exists($fhtml)){
	$fcarregar = "er1.php";
}


?>
<div id="main-rel"></div>
<script>
	$(function () {
		$('#main-rel').load('<?php echo $fcarregar;?>');
	});
</script>