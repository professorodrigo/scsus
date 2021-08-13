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
$run_dados = $db->query("DROP TABLE profissionais;");
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
$run_dados = $db->query("DROP TABLE lotacao;");
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

$xml = simplexml_load_file('xml/cnes.xml');
foreach($xml->IDENTIFICACAO->PROFISSIONAIS->DADOS_PROFISSIONAIS as $registro):
	$dados = "
		INSERT INTO profissionais (
			cpf,
			cns,		
			nome,
			dtnas,
			sexo,
			c_id,
			c_uf,
			c_registro
		) VALUES (
			'".$registro['CPF_PROF']."',
			'".$registro['CO_CNS']."',
			'".tiracento(trim($registro['NM_PROF']))."',
			".dataint($registro['DT_NASC'],'d').",
			'".$registro['SEXO']."',
			'".$registro['CONSELHO_ID']."',
			'".$registro['SG_UF_EMIS']."',
			'".$registro['NU_REGISTRO']."'
		);
	";
	$run_dados = $db->query($dados);
	foreach($registro->LOTACOES->DADOS_LOTACOES as $lota):
		$dados = "
			INSERT INTO lotacao (
				cpf,
				cns,		
				nome,
				cnes,
				ine,
				cbo
			) VALUES (
				'".$registro['CPF_PROF']."',
				'".$registro['CO_CNS']."',
				'".tiracento(trim($registro['NM_PROF']))."',
				'".trim($lota['CNES'])."',
				'".trim($lota['CO_INE'])."',
				'".trim($lota['CO_CBO'])."'
			);
		";
		$run_dados = $db->query($dados);
	endforeach;
endforeach;


/*


foreach($xml->IDENTIFICACAO->ESTABELECIMENTOS->DADOS_GERAIS_ESTABELECIMENTOS as $registro):


NM_FANTA="UNIDADE DE SAUDE DA FAMILIA EDGAR LIESENBERG"  CNPJ="83102251000104"  CNES="9726624"  TP_UNID_ID="01"  DS_TP_UNID="POSTO DE SAUDE"  TELEFONE1="(47)33877600"  TELEFONE2=" "  FAX=""  E_MAIL="saude@pomerode.sc.gov.br"


	$dados = "
		INSERT INTO profissionais (
			cpf,
			cns,		
			nome,
			dtnas,
			sexo,
			c_id,
			c_uf,
			c_registro
		) VALUES (
			'".$registro['CPF_PROF']."',
			'".$registro['CO_CNS']."',
			'".tiracento(trim($registro['NM_PROF']))."',
			".dataint($registro['DT_NASC'],'d').",
			'".$registro['SEXO']."',
			'".$registro['CONSELHO_ID']."',
			'".$registro['SG_UF_EMIS']."',
			'".$registro['NU_REGISTRO']."'
		);
	";
	$run_dados = $db->query($dados);
	foreach($registro->EQUIPES->DADOS_EQUIPES as $lota):
	
	
ID_TP_EQUIPE=""   TP_EQUIPE="71"   SG_EQUIPE="ESB"   DS_EQUIPE="EQUIPE DE SAUDE BUCAL"   CO_INE="0001999494"   CO_AREA="0001"   DS_AREA="JANE MERI SIEBERT FERNANDES"   NM_REFERENCIA="EDGAR LIESENBERG SB"   DT_DESATIVACAO=""
	
	
	
		$dados = "
			INSERT INTO lotacao (
				cpf,
				cnes,
				ine,
				cbo
			) VALUES (
				'".$registro['CPF_PROF']."',
				'".trim($lota['CNES'])."',
				'".trim($lota['CO_INE'])."',
				'".trim($lota['CO_CBO'])."'
			);
		";
		$run_dados = $db->query($dados);
	endforeach;
endforeach;

*/


?>