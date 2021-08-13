<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   06/08/2021
//   Rodrigo Silva
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ini_set('max_execution_time', 0);
ini_set("memory_limit", "-1");
header("Content-type: text/html; charset=utf-8");
require_once('session.php');
require_once('functions.php');

$db = new SQLite3('db/scsus.db');

//*****************************************************************
$run_dados = $db->query("DROP TABLE cbos;");
$estrutura = "
	CREATE TABLE if not exists cbos (
		cbo CHAR (7),
		desc VARCHAR (250)
	);
";
$run_estrutura = $db->query($estrutura);
$arquivo_rel = fopen("cbo/cbo.csv",'r');
$clinha = 0;
while(!feof($arquivo_rel)) {
	$line = fgets($arquivo_rel);
	if (strlen($line) > 0){
		$val_arr = explode(";",$line);
		$cbo = trim($val_arr[0]);
		$desc = tiracento(trim($val_arr[1]));
		$dados = "
			INSERT INTO cbos (
				cbo,
				desc
			) VALUES (
				'".$cbo."',
				'".$desc."'
			);
		";
		if ($clinha != 0){
			$run_dados = $db->query($dados);
		}
		$clinha = 1;
	}
}
fclose($arquivo_rel);
?>