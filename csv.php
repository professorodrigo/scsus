<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   15/07/2021
//   Rodrigo Silva
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

require_once('session.php');

$rf = isset($_GET["rf"]) ? trim($_GET["rf"]) : '0';

if ($rf != '0'){
	header("Content-Type: application/csv");
	header("Content-Disposition: attachment; filename=".$rf);
	header("Pragma: no-cache");
	
	if (file_exists("csv/".$rf)){
		readfile("csv/".$rf);
	}
}
?>
