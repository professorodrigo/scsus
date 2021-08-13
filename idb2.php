<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   06/08/2021
//   Rodrigo Silva
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

$db = new SQLite3('db/scsus.db');

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

/********************************************************************************************
   DADOS
********************************************************************************************/
$estrutura = "
	CREATE TABLE if not exists dados (
		id varchar (11) NOT NULL, 
		cbnome varchar (250),
		cbend1 varchar (250),
		cbend2 varchar (250),
		cbend3 varchar (250),
		cbend4 varchar (250),
		cbcont1 varchar (250),
		cbcont2 varchar (250),
		PRIMARY KEY (id)
	);
";
$run_estrutura = $db->query($estrutura);
$rows = $db->query("SELECT COUNT(*) as count FROM dados WHERE id = '".$_SESSION['key']."'");
$row = $rows->fetchArray();
if ($row['count'] <= 0){
	$dados = "
		INSERT INTO dados (
			id, 
			cbnome, 
			cbend1, 
			cbend2, 
			cbend3, 
			cbend4, 
			cbcont1, 
			cbcont2
		) VALUES (
			'".$_SESSION['key']."',
			'Secretaria Municipal de Saúde de Teste', 
			'Avenida Sete de Setembro, número 29387 - Sala 2', 
			'Bairro Matarazzo Caprinio', 
			'CEP 98732-980', 
			'São Matheus do Oeste Mineiro - MG', 
			'+55 (47) 23432-9384 | +55 (47) 12384-3234', 
			'contatosaude@saomatheus.gov.br | www.saomatheuspref.gov.br'
		);
	";
	$run_dados = $db->query($dados);
}
/********************************************************************************************
   DIABETICOS
********************************************************************************************/
$estrutura = "
	CREATE TABLE if not exists diabeticos (
		id VARCHAR (11) PRIMARY KEY NOT NULL, 
		dti BIGINT DEFAULT (20210101), 
		dtf BIGINT DEFAULT (20210430), 
		gpa INT DEFAULT (0), 
		m12 INT DEFAULT (0), 
		cfa INT DEFAULT (0), 
		des INT DEFAULT (0), 
		paginacao INT DEFAULT (0), 
		mcabecalho INT DEFAULT (1), 
		grupo VARCHAR (10) DEFAULT ine, 
		ordem CHAR (2) DEFAULT N
	);
";
$run_estrutura = $db->query($estrutura);
$rows = $db->query("SELECT COUNT(*) as count FROM diabeticos WHERE id = '".$_SESSION['key']."'");
$row = $rows->fetchArray();
if ($row['count'] <= 0){
	$dados = "
		INSERT INTO diabeticos (
			id, 
			dti, 
			dtf, 
			gpa, 
			m12, 
			cfa, 
			des, 
			paginacao, 
			mcabecalho, 
			grupo, 
			ordem
		) VALUES (
			'".$_SESSION['key']."',
			".$dti.", 
			".$dtf.", 
			0, 
			0, 
			0, 
			0, 
			0, 
			1, 
			'ine', 
			'N'
		);
	";
	$run_dados = $db->query($dados);
}
/********************************************************************************************
   VACINAS
********************************************************************************************/
$estrutura = "
	CREATE TABLE if not exists vacinas (
		id VARCHAR (11) PRIMARY KEY NOT NULL, 
		dti BIGINT DEFAULT (20210101), 
		dtf BIGINT DEFAULT (20210430), 
		gpa INT DEFAULT (0),
		idin INT DEFAULT (0), 
		idfi INT DEFAULT (150),
		tpb INT DEFAULT (12),
		apvac INT DEFAULT (0), 
		imbios VARCHAR (150),
		cd3 CHAR (2) DEFAULT OU,
		d3c INT DEFAULT (0),
		ridade INT DEFAULT (0),
		dt3anos CHAR (2) DEFAULT U,
		cfa INT DEFAULT (0), 
		des INT DEFAULT (0), 
		paginacao INT DEFAULT (0), 
		mcabecalho INT DEFAULT (1), 
		grupo VARCHAR (10) DEFAULT ine, 
		ordem CHAR (2) DEFAULT N
	);
";
$run_estrutura = $db->query($estrutura);
$rows = $db->query("SELECT COUNT(*) as count FROM vacinas WHERE id = '".$_SESSION['key']."'");
$row = $rows->fetchArray();
if ($row['count'] <= 0){
	$dados = "
		INSERT INTO vacinas (
			id, 
			dti, 
			dtf, 
			gpa, 
			idin,
			idfi,
			tpb,
			apvac,
			imbios,
			cd3,
			d3c,
			ridade,
			dt3anos,
			cfa, 
			des, 
			paginacao, 
			mcabecalho, 
			grupo, 
			ordem
		) VALUES (
			'".$_SESSION['key']."',
			".$dti.", 
			".$dtf.", 
			0, 
			0, 
			150,
			12,
			0,
			'',
			'OU',
			0,
			0,
			'U',
			0, 
			0, 
			0, 
			1, 
			'ine', 
			'N'
		);
	";
	$run_dados = $db->query($dados);
}
/********************************************************************************************
   GESTANTES
********************************************************************************************/
$estrutura = "
	CREATE TABLE if not exists gestantes (
		id VARCHAR (11) PRIMARY KEY NOT NULL, 
		dti BIGINT DEFAULT (20210101), 
		dtf BIGINT DEFAULT (20210430), 
		gpa INT DEFAULT (0),
		des INT DEFAULT (0),
		dpp INT DEFAULT (294),
		dum CHAR (2) DEFAULT A,
		mconsultas INT DEFAULT (1),	
		tbodonto INT DEFAULT (0),
		paginacao INT DEFAULT (0), 
		mcabecalho INT DEFAULT (1), 
		grupo VARCHAR (10) DEFAULT ine, 
		ordem CHAR (2) DEFAULT N
	);
";
$run_estrutura = $db->query($estrutura);
$rows = $db->query("SELECT COUNT(*) as count FROM gestantes WHERE id = '".$_SESSION['key']."'");
$row = $rows->fetchArray();
if ($row['count'] <= 0){
	$dados = "
		INSERT INTO gestantes (
			id, 
			dti, 
			dtf, 
			gpa, 
			des, 
			dpp,
			dum,
			mconsultas,
			tbodonto,
			paginacao, 
			mcabecalho, 
			grupo, 
			ordem
		) VALUES (
			'".$_SESSION['key']."',
			".$dti.", 
			".$dtf.", 
			0, 
			0,
			294,
			'A',
			1,
			0,
			0,
			1, 
			'ine', 
			'N'
		);
	";
	$run_dados = $db->query($dados);
}
/********************************************************************************************
   HIPERTENSOS
********************************************************************************************/
$estrutura = "
	CREATE TABLE if not exists hipertensos (
		id VARCHAR (11) PRIMARY KEY NOT NULL, 
		dti BIGINT DEFAULT (20210101), 
		dtf BIGINT DEFAULT (20210430), 
		gpa INT DEFAULT (0), 
		m12 INT DEFAULT (0), 
		cfa INT DEFAULT (0), 
		des INT DEFAULT (0), 
		paginacao INT DEFAULT (0), 
		mcabecalho INT DEFAULT (1), 
		grupo VARCHAR (10) DEFAULT ine, 
		ordem CHAR (2) DEFAULT N
	);
";
$run_estrutura = $db->query($estrutura);
$rows = $db->query("SELECT COUNT(*) as count FROM hipertensos WHERE id = '".$_SESSION['key']."'");
$row = $rows->fetchArray();
if ($row['count'] <= 0){
	$dados = "
		INSERT INTO hipertensos (
			id, 
			dti, 
			dtf, 
			gpa, 
			m12, 
			cfa, 
			des, 
			paginacao, 
			mcabecalho, 
			grupo, 
			ordem
		) VALUES (
			'".$_SESSION['key']."',
			".$dti.", 
			".$dtf.", 
			0, 
			0, 
			0, 
			0, 
			0, 
			1, 
			'ine', 
			'N'
		);
	";
	$run_dados = $db->query($dados);
}
/********************************************************************************************
   MULHERES
********************************************************************************************/
$estrutura = "
	CREATE TABLE if not exists mulheres (
		id VARCHAR (11) PRIMARY KEY NOT NULL, 
		dti BIGINT DEFAULT (20210101), 
		dtf BIGINT DEFAULT (20210430), 
		gpa INT DEFAULT (0),
		idin INT DEFAULT (0), 
		idfi INT DEFAULT (150),
		tbusca INT DEFAULT (12),
		apvac INT DEFAULT (0), 
		proceds VARCHAR (150),
		ridade INT DEFAULT (0),
		dt3anos CHAR (2) DEFAULT U,
		cfa INT DEFAULT (0), 
		des INT DEFAULT (0), 
		paginacao INT DEFAULT (0), 
		mcabecalho INT DEFAULT (1), 
		grupo VARCHAR (10) DEFAULT ine, 
		ordem CHAR (2) DEFAULT N,
		moh CHAR (1) DEFAULT A
	);
";
$run_estrutura = $db->query($estrutura);
$rows = $db->query("SELECT COUNT(*) as count FROM mulheres WHERE id = '".$_SESSION['key']."'");
$row = $rows->fetchArray();
if ($row['count'] <= 0){
	$dados = "
		INSERT INTO mulheres (
			id, 
			dti, 
			dtf, 
			gpa, 
			idin,
			idfi,
			tbusca,
			apvac,
			proceds,
			ridade,
			dt3anos,
			cfa, 
			des, 
			paginacao, 
			mcabecalho, 
			grupo, 
			ordem,
			moh
		) VALUES (
			'".$_SESSION['key']."',
			".$dti.", 
			".$dtf.", 
			0, 
			0, 
			150,
			12,
			0,
			'',
			0,
			'U',
			0, 
			0, 
			0, 
			1, 
			'ine', 
			'N',
			'A'
		);
	";
	$run_dados = $db->query($dados);
}
/********************************************************************************************
   CONFIG BANCO
********************************************************************************************/
$estrutura = "
	CREATE TABLE if not exists banco (
		id VARCHAR (11) PRIMARY KEY NOT NULL, 
		dbhost VARCHAR (50) DEFAULT localhost, 
		dbport VARCHAR (10) DEFAULT 5433,
		dbdb VARCHAR (50) DEFAULT esus,
		dbuser VARCHAR (50) DEFAULT postgres, 
		dbpass VARCHAR (50) DEFAULT esus
	);
";
$run_estrutura = $db->query($estrutura);
$rows = $db->query("SELECT COUNT(*) as count FROM banco WHERE id = '".$_SESSION['key']."'");
$row = $rows->fetchArray();
if ($row['count'] <= 0){
	$dados = "
		INSERT INTO banco (
			id, 
			dbhost, 
			dbport, 
			dbdb, 
			dbuser,
			dbpass
		) VALUES (
			'".$_SESSION['key']."',
			'localhost', 
			'5433', 
			'esus', 
			'postgres', 
			'esus'
		);
	";
	$run_dados = $db->query($dados);
	$mensagem = "
	  <script type=\"text/javascript\">
		$(document).ready(function() {
		  var unique_id = $.gritter.add({
			title: 'ATENÇÃO',
			text: 'Antes de qualquer coisa configure o DB',
			image: 'dist/img/user01.png',
			sticky: true,
			//time: 8000,
			class_name: 'my-sticky-class'
		  });
		  return false;
		});
	  </script>
	";
}
/********************************************************************************************
   DUPLICADOS
********************************************************************************************/
$estrutura = "
	CREATE TABLE if not exists duplicados (
		id VARCHAR (11) PRIMARY KEY NOT NULL, 
		gpa INT DEFAULT (0),  
		cfa INT DEFAULT (0), 
		paginacao INT DEFAULT (0), 
		mcabecalho INT DEFAULT (1), 
		grupo VARCHAR (10) DEFAULT ine, 
		ordem CHAR (2) DEFAULT N
	);
";
$run_estrutura = $db->query($estrutura);
$rows = $db->query("SELECT COUNT(*) as count FROM duplicados WHERE id = '".$_SESSION['key']."'");
$row = $rows->fetchArray();
if ($row['count'] <= 0){
	$dados = "
		INSERT INTO duplicados (
			id, 
			gpa, 
			cfa,  
			paginacao, 
			mcabecalho, 
			grupo, 
			ordem
		) VALUES (
			'".$_SESSION['key']."',
			0, 
			0,  
			0, 
			1, 
			'ine', 
			'N'
		);
	";
	$run_dados = $db->query($dados);
}
/********************************************************************************************
   PROCEDIMENTOS
********************************************************************************************/
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
/********************************************************************************************
   CIDS
********************************************************************************************/
$estrutura = "
	CREATE TABLE if not exists cids (
		cid VARCHAR (4) PRIMARY KEY NOT NULL, 
		nome VARCHAR (100), 
		sexo CHAR (1),		
		agravo CHAR (1)
	);
";
$run_estrutura = $db->query($estrutura);
/********************************************************************************************
   OCUPACOES
********************************************************************************************/
$estrutura = "
	CREATE TABLE if not exists ocupacoes (
		ocupacao VARCHAR (6) PRIMARY KEY NOT NULL, 
		nome VARCHAR (150)
	);
";
$run_estrutura = $db->query($estrutura);
/********************************************************************************************
   REL PROCEDIMENTOS E OCUPACOES
********************************************************************************************/
$estrutura = "
	CREATE TABLE if not exists rl_proc_ocupacao (
		procedimento VARCHAR (10), 
		ocupacao VARCHAR (6)
	);
";
$run_estrutura = $db->query($estrutura);
/********************************************************************************************
   REL PROCEDIMENTOS E CIDS
********************************************************************************************/
$estrutura = "
	CREATE TABLE if not exists rl_proc_cid (
		procedimento VARCHAR (10), 
		cid VARCHAR (4)
	);
";
$run_estrutura = $db->query($estrutura);
/********************************************************************************************
   CBOS
********************************************************************************************/
$estrutura = "
	CREATE TABLE if not exists cbos (
		cbo CHAR (7),
		desc VARCHAR (250)
	);
";
$run_estrutura = $db->query($estrutura);
/********************************************************************************************
   XML
********************************************************************************************/
$estrutura = "
	CREATE TABLE if not exists profissionais (
		cpf CHAR (11) PRIMARY KEY NOT NULL,
		cns CHAR (15),		
		nome VARCHAR (200),
		dtnas BIGINT DEFAULT (30001231),
		sexo CHAR (1),
		c_id CHAR (3),
		c_uf CHAR (2),
		c_registro CHAR (10)	
	);
";
$run_estrutura = $db->query($estrutura);
$estrutura = "
	CREATE TABLE if not exists lotacao (
		cpf VARCHAR (11),
		cns CHAR (15),		
		nome VARCHAR (200),
		cnes VARCHAR (8),
		ine VARCHAR (10),
		cbo VARCHAR (6)
	);
";
$run_estrutura = $db->query($estrutura);
?>