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
$run_dados = $db->query("DROP TABLE procedimentos;");
$estrutura = "
	CREATE TABLE if not exists procedimentos (
		procedimento VARCHAR (10) PRIMARY KEY NOT NULL, 
		nome VARCHAR (250), 
		sexo CHAR (1),		
		qtmax INT DEFAULT (0), 
		idmi INT DEFAULT (0),
		idma INT DEFAULT (150),
		comp CHAR (6),
		bpai CHAR (1) DEFAULT N,
		bpac CHAR (1) DEFAULT N
	);
";
$run_estrutura = $db->query($estrutura);
$arquivo_rel = fopen("sigtap/tb_procedimento.txt",'r');
while(!feof($arquivo_rel)) {
	$line = fgets($arquivo_rel);
	if (strlen($line) > 0){
		//$val_arr = explode(";",$line);
		$procedimento = substr($line,0,10);
		$nome = tiracento(trim(substr($line,10,250)));
		$sexo = substr($line,261,1);		
		$qtmax = (int) substr($line,262,4); 
		$idmi = (int) substr($line,274,4);
		$idma = (int) substr($line,278,4);
		$comp = substr($line,324,6);
		$dados = "
			INSERT INTO procedimentos (
				procedimento, 
				nome,
				sexo,
				qtmax,
				idmi,
				idma,
				comp
			) VALUES (
				'".$procedimento."', 
				'".$nome."',
				'".$sexo."',	
				".$qtmax.",
				".$idmi.",
				".$idma.",
				'".$comp."'
			);
		";
		$run_dados = $db->query($dados);
		
	}
}
fclose($arquivo_rel);
//------------------------------------------------------------------
$arquivo_rel = fopen("sigtap/rl_procedimento_registro.txt",'r');
while(!feof($arquivo_rel)) {
	$line = fgets($arquivo_rel);
	if (strlen($line) > 0){
		//$val_arr = explode(";",$line);
		$procedimento = substr($line,0,10);
		$registro = substr($line,10,2);
		if ($registro == '01'){
			$run_dados = $db->query("UPDATE procedimentos SET bpac = 'S' WHERE procedimento = '".$procedimento."';");
		}
		if ($registro == '02'){
			$run_dados = $db->query("UPDATE procedimentos SET bpai = 'S' WHERE procedimento = '".$procedimento."';");
		}
	}
}
fclose($arquivo_rel);
//*****************************************************************
$run_dados = $db->query("DROP TABLE cids;");
$estrutura = "
	CREATE TABLE if not exists cids (
		cid VARCHAR (4) PRIMARY KEY NOT NULL, 
		nome VARCHAR (100), 
		sexo CHAR (1),		
		agravo CHAR (1)
	);
";
$run_estrutura = $db->query($estrutura);
$arquivo_rel = fopen("sigtap/tb_cid.txt",'r');
while(!feof($arquivo_rel)) {
	$line = fgets($arquivo_rel);
	if (strlen($line) > 0){
		$cid = substr($line,0,4);
		$nome = tiracento(trim(substr($line,4,100)));
		$sexo = substr($line,105,1);		
		$agravo = substr($line,104,1); 
		$dados = "
			INSERT INTO cids (
				cid, 
				nome,
				sexo,
				agravo
			) VALUES (
				'".$cid."', 
				'".$nome."',
				'".$sexo."',	
				'".$agravo."'
			);
		";
		$run_dados = $db->query($dados);
		
	}
}
fclose($arquivo_rel);
//*****************************************************************
$run_dados = $db->query("DROP TABLE ocupacoes;");
$estrutura = "
	CREATE TABLE if not exists ocupacoes (
		ocupacao VARCHAR (6) PRIMARY KEY NOT NULL, 
		nome VARCHAR (150)
	);
";
$run_estrutura = $db->query($estrutura);
$arquivo_rel = fopen("sigtap/tb_ocupacao.txt",'r');
while(!feof($arquivo_rel)) {
	$line = fgets($arquivo_rel);
	if (strlen($line) > 0){
		$ocupacao = substr($line,0,6);
		$nome = tiracento(trim(substr($line,6,150)));
		$dados = "
			INSERT INTO ocupacoes (
				ocupacao, 
				nome
			) VALUES (
				'".$ocupacao."', 
				'".$nome."'
			);
		";
		$run_dados = $db->query($dados);
		
	}
}
fclose($arquivo_rel);
//*****************************************************************
$run_dados = $db->query("DROP TABLE rl_proc_cid;");
$estrutura = "
	CREATE TABLE if not exists rl_proc_cid (
		procedimento VARCHAR (10), 
		cid VARCHAR (4)
	);
";
$run_estrutura = $db->query($estrutura);
$arquivo_rel = fopen("sigtap/rl_procedimento_cid.txt",'r');
while(!feof($arquivo_rel)) {
	$line = fgets($arquivo_rel);
	if (strlen($line) > 0){
		$procedimento = substr($line,0,10);
		$cid = trim(substr($line,10,4));
		$dados = "
			INSERT INTO rl_proc_cid (
				procedimento, 
				cid
			) VALUES (
				'".$procedimento."', 
				'".$cid."'
			);
		";
		$run_dados = $db->query($dados);
		
	}
}
fclose($arquivo_rel);
//*****************************************************************
$run_dados = $db->query("DROP TABLE rl_proc_ocupacao;");
$estrutura = "
	CREATE TABLE if not exists rl_proc_ocupacao (
		procedimento VARCHAR (10), 
		ocupacao VARCHAR (6)
	);
";
$run_estrutura = $db->query($estrutura);
$arquivo_rel = fopen("sigtap/rl_procedimento_ocupacao.txt",'r');
while(!feof($arquivo_rel)) {
	$line = fgets($arquivo_rel);
	if (strlen($line) > 0){
		$procedimento = substr($line,0,10);
		$ocupacao = substr($line,10,6);
		$dados = "
			INSERT INTO rl_proc_ocupacao (
				procedimento, 
				ocupacao
			) VALUES (
				'".$procedimento."', 
				'".$ocupacao."'
			);
		";
		$run_dados = $db->query($dados);
		
	}
}
fclose($arquivo_rel);

?>