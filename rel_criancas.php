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
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   Includes
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

require_once('session.php');
require_once('functions.php');
require_once('sobre.php');

// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   Configuracoes do banco SQLite
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

$db = new SQLite3('db/scsus.db');
$result = $db->query("SELECT * FROM banco WHERE id = '".$_SESSION['key']."'");
while($array = $result->fetchArray(SQLITE3_ASSOC)){
	$dbhost = $array['dbhost'];
	$dbport = $array['dbport'];
	$dbdb = $array['dbdb'];
	$dbuser = $array['dbuser'];
	$dbpass = $array['dbpass'];
}
$result = $db->query("SELECT * FROM dados WHERE id = '".$_SESSION['key']."'");
while($array = $result->fetchArray(SQLITE3_ASSOC)){
	$cbnome = $array['cbnome'];
	$cbend1 = $array['cbend1'];
	$cbend2 = $array['cbend2'];
	$cbend3 = $array['cbend3'];
	$cbend4 = $array['cbend4'];
	$cbcont1 = $array['cbcont1'];
	$cbcont2 = $array['cbcont2'];
}
$result = $db->query("SELECT * FROM vacinas WHERE id = '".$_SESSION['key']."'");
while($array = $result->fetchArray(SQLITE3_ASSOC)){
	$dti = $array['dti'];
	$dtf = $array['dtf'];
	$gpa = $array['gpa'];
	$cfa = $array['cfa'];
	$paginacao = $array['paginacao'];
	$grupo = $array['grupo'];
	$ordem = $array['ordem'];
	$mcabecalho = $array['mcabecalho'];
	$idin = $array['idin'];
	$idfi = $array['idfi'];
	$imbios = $array['imbios'];
	$apvac = $array['apvac'];
	$cd3 = $array['cd3'];
	$des = $array['des'];
	$tpb = $array['tpb'];
	$d3c = $array['d3c'];
}
require_once('connect.php');

// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   Criação dos arquivos
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

// -----------------------------------------------------------------------
$file = "html/rel_criancas_".$_SESSION['key'].".html";
if (file_exists($file)){unlink($file);}
$HTML = fopen($file,'w');
// -----------------------------------------------------------------------
$file = "csv/criancas_T_".$_SESSION['key'].".csv";
if (file_exists($file)){unlink($file);}
$FT = fopen($file,'w');
$texto = "Seq;CPF;CNS;Nome;DtNascimento;Mae;Idade;MarcGestante;MarcHipertensa;MarcDiabetica;Indicador5;CNES;INE;MA\r\n";
fwrite($FT, $texto);
// -----------------------------------------------------------------------
$file = "csv/criancas_V_".$_SESSION['key'].".csv";
if (file_exists($file)){unlink($file);}
$FV = fopen($file,'w');
$texto = "Seq;CPF;CNS;DtProced;Dose;CNES;INE;CBO;Imunobiologico;Lote;Fabricante;CNSProf;NomeProf;ImunoSigla;ImunoNome;GrupoAt;RegAnt;Tabela\r\n";
fwrite($FV, $texto);

// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   Filtro
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

$super_filtro = false;
$sb = isset($_GET["sb"]) ? trim($_GET["sb"]) : 0;
if ($sb > 0){
	$paginacao = 0;
	$vlbusca = isset($_GET["vb"]) ? trim($_GET["vb"]) : 0;
	$cpbusca = isset($_GET["cb"]) ? trim($_GET["cb"]) : '';
	if ($cpbusca != ''){
		$super_filtro = true;
	}
}

// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   Paginacao
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

$pini = isset($_GET["pini"]) ? trim($_GET["pini"]) : 0;
$num = isset($_GET["num"]) ? trim($_GET["num"]) : 0;
$cdes = isset($_GET["cdes"]) ? trim($_GET["cdes"]) : 0;
$esq_pag = "";
if ($paginacao > 0){
	$esq_pag = "LIMIT ".$paginacao." OFFSET ".$pini;
}

// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   Variaveis
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

if ($gpa == 1){
	$dti = date('Ymd');
	$dtf = date('Ymd');
}
$imunos = "22,42";
// -----------------------------------------------------------------------
if ($tpb == 12){
	$dtf_m = datasomameses($dtf,12,'-');
	$a_dtf = substr($dtf,0,4);
	$m_dtf = substr($dtf,4,2);
	$d_dtf = substr($dtf,6,2);
	$a_dtf_m = substr($dtf_m,0,4);
	$m_dtf_m = substr($dtf_m,4,2);
	$d_dtf_m = substr($dtf_m,6,2);
	$dti_nas = $a_dtf_m.'-'.$m_dtf_m.'-'.$d_dtf_m;
	$dtf_nas = $a_dtf.'-'.$m_dtf.'-'.$d_dtf;
} else {
	$dtf_m = datasomameses($dtf,12,'-');
	$dtf_m_f = datasomameses($dtf,6,'-');
	$a_dtf_f = substr($dtf_m_f,0,4);
	$m_dtf_f = substr($dtf_m_f,4,2);
	$d_dtf_f = substr($dtf_m_f,6,2);
	$a_dtf_m = substr($dtf_m,0,4);
	$m_dtf_m = substr($dtf_m,4,2);
	$d_dtf_m = substr($dtf_m,6,2);
	$dti_nas = $a_dtf_m.'-'.$m_dtf_m.'-'.$d_dtf_m;
	$dtf_nas = $a_dtf_f.'-'.$m_dtf_f.'-'.$d_dtf_f;
}
// -----------------------------------------------------------------------
$conta_geral = $pini;
$conta_geral_desconto = $cdes;
$numerador_ind5 = $num;
$contator_gl_hipertenso = 0;
$contator_gl_diabetico = 0;
$conta_gravacao = 0;
$conta_polio_1 = 0;
$conta_penta_1 = 0;
$conta_polio_2 = 0;
$conta_penta_2 = 0;
$conta_polio_3 = 0;
$conta_penta_3 = 0;
// -----------------------------------------------------------------------
$duplicados = array();
$conta_duplicados = 0;
$total_equipe = array();
$conta_total_equipe = 0;
$total_unidade = array();
$conta_total_unidade = 0;

// ===========================================================================================================
$rel_pagina_inicio = "
  <!-- Content Wrapper. Contains page content -->
  <div class=\"content-wrapper\">
    <!-- Content Header (Page header) -->
    <section class=\"content-header\">
      <div class=\"container-fluid\">
        <div class=\"row mb-2\">
          <div class=\"col-sm-6\">
            <h1>Crianças</h1>
          </div>
          <div class=\"col-sm-6\">
            <ol class=\"breadcrumb float-sm-right\">
              <li class=\"breadcrumb-item\"><a href=\"#\" onclick=\"printDiv('main-body');return false;\">Imprimir</a></li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class=\"content\">
      <div class=\"container-fluid\">
        <div class=\"row\">
";
fwrite($HTML, $rel_pagina_inicio);
// ===========================================================================================================
$imglogo = "logom.jpg";
if (file_exists("plugins/scsus/temas/".$_SESSION['tema']."/img/logom_".$_SESSION['key'].".jpg")){
	$imglogo = "logom_".$_SESSION['key'].".jpg";
}
if ($mcabecalho == 1){
	// ===========================================================================================================
	$rel_cabecalho_1 = "
	<table width=\"1009\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"cabeca-tabela\">
	  <!--DWLayoutTable-->
	  <tr> 
		<td width=\"150\" rowspan=\"3\" valign=\"top\" class=\"logo\"><img src=\"plugins/scsus/temas/".$_SESSION['tema']."/img/".$imglogo."\" width=\"145\" height=\"145\"></td>
		<td height=\"42\" colspan=\"3\" valign=\"top\" class=\"cabeca-titulo\">".$cbnome."</td>
	  </tr>
	  <tr> 
		<td width=\"18\" height=\"70\" valign=\"top\" class=\"cabeca-borda-baixo\"><!--DWLayoutEmptyCell-->&nbsp;</td>
		<td colspan=\"2\" valign=\"top\" class=\"cabeca-endereco\"> <p> 
				".$cbend1."<br>
				".$cbend2."<br>
				".$cbend3."<br>
				".$cbend4."
		</p></td>
	  </tr>
	  <tr> 
		<td height=\"38\">&nbsp;</td>
		<td width=\"641\" valign=\"top\" class=\"cabeca-fone\">
			".$cbcont1."<br>
			".$cbcont2."
		</td>
		<td width=\"200\" valign=\"top\" class=\"cabeca-pagina\"><!--DWLayoutEmptyCell-->&nbsp;</td>
		</tr>
	</table>
	";
	fwrite($HTML, $rel_cabecalho_1);
	// ===========================================================================================================
}

// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   Tabela termporaria
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

$tabela_temp = "
CREATE TEMPORARY TABLE tmp_criancas (
	sequencia serial PRIMARY KEY,
	cns varchar(15),
	cpf varchar(11),
	cns_alternativo varchar(15),
	cpf_alternativo varchar(11),
	data_nascimento bigint,
	co_seq_cidadao bigint,
	cidadao_ativo int,
	data_falecimento bigint,
	cidadao_nome varchar(500),
	cidadao_mae varchar(500),
	cidadao_faleceu int,
	cidadao_sexo varchar(24),
	cns_resp varchar(15),
	cpf_resp varchar(11),
	cind_gestante int,
	cind_hipertenso int,
	cind_diabetico int,
	cind_sexo varchar(24),
	cind_micro_area varchar(3),
	cind_inativo int,
	cnes varchar(20),
	nome_unidade varchar(500),
	ine varchar(20),
	nome_equipe varchar(255),
	cds int
)
";
$run_tt = pg_query($cdb,$tabela_temp);

// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   SQL_1
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

$SQL_1 = "
SELECT 
	* 
FROM
(
	SELECT
		TRIM(FROM t4.nu_cns) as nu_cns,
		TRIM(FROM t4.nu_cpf_cidadao) as nu_cpf_cidadao,
		CASE WHEN t5.nu_cns IS NULL THEN '0'
			ELSE TRIM(FROM t5.nu_cns) END
		nu_cns_ci,
		CASE WHEN t5.nu_cpf_cidadao IS NULL THEN '0'
			ELSE TRIM(FROM t5.nu_cpf_cidadao) END
		nu_cpf_cidadao_ci,
		t4.dt_nascimento,
		t4.no_sexo,
		t4.st_faleceu,
		t4.no_cidadao,
		t4.no_mae, 
		t4.dt_obito, 
		t4.nu_cns_responsavel_c, 
		t4.nu_cpf_responsavel_c, 
		t5.nu_cns_responsavel, 
		t5.nu_cpf_responsavel,
		t4.st_ativo,
		t4.co_seq_cidadao,
		t5.dt_obito AS dt_obito_ci,
		CASE WHEN t5.st_ficha_inativa IS NULL THEN '0'
			ELSE t5.st_ficha_inativa END
		st_ficha_inativa,
		t4.nu_micro_area_c,
		CASE WHEN t5.nu_micro_area IS NULL THEN '00'
			ELSE t5.nu_micro_area END
		nu_micro_area,
		CASE WHEN t5.nu_ine IS NULL THEN '0000000000'
			ELSE t5.nu_ine END
		nu_ine,
		CASE WHEN t5.no_equipe IS NULL THEN 'SEM EQUIPE'
			ELSE t5.no_equipe END
		no_equipe,
		CASE WHEN t5.nu_cnes IS NULL THEN '0000000'
			ELSE t5.nu_cnes END
		nu_cnes,
		CASE WHEN t5.no_unidade_saude IS NULL THEN 'SEM UNIDADE'
			ELSE t5.no_unidade_saude END
		no_unidade_saude,
		CASE WHEN t5.st_gestante IS NULL THEN 0
			ELSE t5.st_gestante END
		st_gestante,
		CASE WHEN t5.st_hipertensao_arterial IS NULL THEN 0
			ELSE t5.st_hipertensao_arterial END
		st_hipertensao_arterial,	
		CASE WHEN t5.st_diabete IS NULL THEN 0
			ELSE t5.st_diabete END
		st_diabete,
		CASE WHEN t5.ds_sexo IS NULL THEN 'FEMININO'
			ELSE t5.ds_sexo END
		ds_sexo,
		int '0' as teste1,
		text '0' as teste2
	FROM 
	(
		SELECT DISTINCT ON (no_cidadao, no_mae, dt_nascimento)
			CASE WHEN nu_cns IS NULL THEN '0'
				ELSE TRIM(nu_cns) END
			nu_cns,
			CASE WHEN nu_cpf IS NULL THEN '0'
				ELSE TRIM(nu_cpf) END
			nu_cpf_cidadao,
			dt_nascimento, 
			no_sexo, 
			CASE WHEN st_faleceu IS NULL THEN '0'
				ELSE st_faleceu END
			st_faleceu,
			no_cidadao,
			no_mae, 
			dt_obito, 
			CASE WHEN nu_cns_responsavel IS NULL THEN '0'
				ELSE TRIM(nu_cns_responsavel) END
			nu_cns_responsavel_c,
			CASE WHEN nu_cpf_responsavel IS NULL THEN '0'
				ELSE TRIM(nu_cpf_responsavel) END
			nu_cpf_responsavel_c,
			st_ativo,
			CASE WHEN nu_micro_area IS NULL THEN '00'
				ELSE nu_micro_area END
			nu_micro_area_c,
			co_seq_cidadao
		FROM 
			tb_cidadao 
		WHERE 
			(dt_nascimento >= '".$dti_nas."' AND dt_nascimento <= '".$dtf_nas."')
		ORDER BY no_cidadao, no_mae, dt_nascimento, dt_atualizado DESC
	) AS t4
	LEFT JOIN
	(
		SELECT
			t3.nu_cns,
			t3.nu_cpf_cidadao,
			t3.dt_obito,
			t3.nu_micro_area,
			t3.st_ficha_inativa,
			t3.nu_ine,
			t3.no_equipe,
			t3.nu_cnes,
			t3.no_unidade_saude,
			t3.st_gestante,
			t3.st_hipertensao_arterial,
			t3.st_diabete,
			upper(tb_dim_sexo.ds_sexo) AS ds_sexo,
			t3.nu_cns_responsavel,
			t3.nu_cpf_responsavel
		FROM
		(
			SELECT
				t2.*,
				tb_dim_unidade_saude.nu_cnes,
				tb_dim_unidade_saude.no_unidade_saude
			FROM
			(
				SELECT 
					t1.*,
					tb_dim_equipe.nu_ine,
					tb_dim_equipe.no_equipe
				FROM
				(
					SELECT DISTINCT ON (nu_cns, nu_cpf_cidadao)
						CASE WHEN nu_cns IS NULL THEN '0'
							ELSE TRIM(FROM nu_cns) END
						nu_cns,
						CASE WHEN nu_cpf_cidadao IS NULL THEN '0'
							ELSE TRIM(FROM nu_cpf_cidadao) END
						nu_cpf_cidadao,
						co_dim_unidade_saude,
						co_dim_equipe,
						co_dim_sexo,
						CASE WHEN nu_cns_responsavel IS NULL THEN '0'
							ELSE TRIM(FROM nu_cns_responsavel) END
						nu_cns_responsavel,
						CASE WHEN nu_cpf_responsavel IS NULL THEN '0'
							ELSE TRIM(FROM nu_cpf_responsavel) END
						nu_cpf_responsavel,
						dt_obito,
						CASE WHEN nu_micro_area IS NULL THEN '00'
							ELSE nu_micro_area END
							nu_micro_area,
						st_ficha_inativa,
						CASE WHEN st_gestante IS NULL THEN '0'
							ELSE st_gestante END
							st_gestante,
						CASE WHEN st_hipertensao_arterial IS NULL THEN '0'
							ELSE st_hipertensao_arterial END
							st_hipertensao_arterial,
						CASE WHEN st_diabete IS NULL THEN '0'
							ELSE st_diabete END
							st_diabete
					FROM 
						tb_fat_cad_individual 
					ORDER BY nu_cns, nu_cpf_cidadao, co_dim_tempo DESC
				) AS t1
				LEFT JOIN
					tb_dim_equipe
				ON tb_dim_equipe.co_seq_dim_equipe = t1.co_dim_equipe
			) AS t2
			LEFT JOIN
				tb_dim_unidade_saude
			ON tb_dim_unidade_saude.co_seq_dim_unidade_saude = t2.co_dim_unidade_saude
		) AS t3
		LEFT JOIN
			tb_dim_sexo
		ON tb_dim_sexo.co_seq_dim_sexo = t3.co_dim_sexo
	) AS t5
	ON
	CASE WHEN length(t5.nu_cns) = 15 THEN t5.nu_cns = t4.nu_cns ELSE CASE WHEN length(t5.nu_cpf_cidadao) = 11 THEN t5.nu_cpf_cidadao = t4.nu_cpf_cidadao ELSE false END END
	ORDER BY nu_cns, nu_cpf_cidadao, no_cidadao
) AS tf1
UNION ALL
SELECT
	* 
FROM 
(
	SELECT
		CASE WHEN nu_cns IS NULL THEN '0'
			ELSE TRIM(FROM nu_cns) END
		nu_cns,
		CASE WHEN nu_cpf_cidadao IS NULL THEN '0'
			ELSE TRIM(FROM nu_cpf_cidadao) END
		nu_cpf_cidadao,
		CASE WHEN nu_cns IS NULL THEN '0'
			ELSE TRIM(FROM nu_cns) END
		nu_cns_ci,
		CASE WHEN nu_cpf_cidadao IS NULL THEN '0'
			ELSE TRIM(FROM nu_cpf_cidadao) END
		nu_cpf_cidadao_ci,
		dt_nascimento,
		text 'FEMININO' as no_sexo,
		int '0' as st_faleceu,
		text '# CRIANCA NÃO ENCONTRADO #' as no_cidadao,
		text '# SEM NOME DA MAE #' as no_mae,
		dt_obito,
		text '00' as nu_cns_responsavel_c,
		text '00' as nu_cpf_responsavel_c,
		CASE WHEN nu_cns_responsavel IS NULL THEN '0'
			ELSE TRIM(FROM nu_cns_responsavel) END
		nu_cns_responsavel,
		CASE WHEN nu_cpf_responsavel IS NULL THEN '0'
			ELSE TRIM(FROM nu_cpf_responsavel) END
		nu_cpf_responsavel,
		int '1' as st_ativo,
		int '0' as co_seq_cidadao,
		dt_obito as dt_obito_ci,
		CASE WHEN st_ficha_inativa IS NULL THEN '0'
			ELSE st_ficha_inativa END
		st_ficha_inativa,
		text '00' as nu_micro_area_c,
		CASE WHEN nu_micro_area IS NULL THEN '00'
			ELSE nu_micro_area END
		nu_micro_area,
		CASE WHEN nu_ine IS NULL THEN '0000000000'
			ELSE nu_ine END
		nu_ine,
		CASE WHEN no_equipe IS NULL THEN 'SEM EQUIPE'
			ELSE no_equipe END
		no_equipe,
		CASE WHEN nu_cnes IS NULL THEN '0000000'
			ELSE nu_cnes END
		nu_cnes,
		CASE WHEN no_unidade_saude IS NULL THEN 'SEM UNIDADE'
			ELSE no_unidade_saude END
		no_unidade_saude,
		CASE WHEN st_gestante IS NULL THEN '0'
			ELSE st_gestante END
		st_gestante,
		CASE WHEN st_hipertensao_arterial IS NULL THEN '0'
			ELSE st_hipertensao_arterial END
		st_hipertensao_arterial,
		CASE WHEN st_diabete IS NULL THEN '0'
			ELSE st_diabete END
		st_diabete,
		ds_sexo,
		int '0' as teste1,
		text '0' as teste2
	FROM
	(
		SELECT 
			*
		FROM
		(
			SELECT 
				*
			FROM
			(
				SELECT
					t1.*,
					upper(tb_dim_sexo.ds_sexo) AS ds_sexo
				FROM
				(
					SELECT DISTINCT ON (nu_cns, nu_cpf_cidadao)
						*
					FROM 
						tb_fat_cad_individual
					WHERE
						(dt_nascimento >= '".$dti_nas."' AND dt_nascimento <= '".$dtf_nas."')
					ORDER BY nu_cns, nu_cpf_cidadao, co_dim_tempo DESC
				) AS t1
				LEFT JOIN
					tb_dim_sexo
				ON tb_dim_sexo.co_seq_dim_sexo = t1.co_dim_sexo
			) AS t4
			LEFT JOIN
				tb_dim_equipe
			ON tb_dim_equipe.co_seq_dim_equipe = t4.co_dim_equipe
		) AS t5
		LEFT JOIN
			tb_dim_unidade_saude
		ON tb_dim_unidade_saude.co_seq_dim_unidade_saude = t5.co_dim_unidade_saude
	) AS t3
	WHERE NOT EXISTS
	(
		SELECT
			nu_cns, nu_cpf
		FROM
			tb_cidadao
		WHERE
			CASE WHEN length(t3.nu_cns) = 15 THEN t3.nu_cns = tb_cidadao.nu_cns ELSE CASE WHEN length(t3.nu_cpf_cidadao) = 11 THEN t3.nu_cpf_cidadao = tb_cidadao.nu_cpf ELSE false END END
	)
	ORDER BY nu_cns, nu_cpf_cidadao, no_cidadao
) AS tf2
".$esq_pag;

$run_s1 = pg_query($cdb,$SQL_1);
$nm_sql_1 = 0;
$nm_sql_1 = pg_num_rows($run_s1);
if ($nm_sql_1 > 0){
	while ($result1 = pg_fetch_array($run_s1)){
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		//   CNS e CPF
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		$CNS = '0';
		$CPF = '0';
		$CNSA = '0';
		$CPFA = '0';
		if (strlen(trim($result1['nu_cns'])) == 15){
			$CNS = trim($result1['nu_cns']);
		} else {
			if (strlen(trim($result1['nu_cns_ci'])) == 15){
				$CNS = trim($result1['nu_cns_ci']);
			}
		}
		if (strlen(trim($result1['nu_cpf_cidadao'])) == 11){
			$CPF = trim($result1['nu_cpf_cidadao']);
		} else {
			if (strlen(trim($result1['nu_cpf_cidadao_ci'])) == 11){
				$CPF = trim($result1['nu_cpf_cidadao_ci']);
			}
		}
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		//   Duplicados
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		$duplicado = false;
		if (strlen($CNS) == 15){
			if (false !== $key = array_search($CNS, array_column($duplicados, 'cns'))) {
				$duplicado = true;
				if (strlen($CPF) == 11){
					if (strlen($duplicados[$key]['cpf']) != 11){
						$duplicados[$key]['cpf'] = $CPF;
					}
				}
			}
		}
		if (strlen($CPF) == 11){
			if (false !== $key = array_search($CPF, array_column($duplicados, 'cpf'))) {
				$duplicado = true;
				if (strlen($CNS) == 15){
					if (strlen($duplicados[$key]['cns']) != 15){
						$duplicados[$key]['cns'] = $CNS;
					}
				}
			}
		}
		if (!$duplicado){
			$duplicados[$conta_duplicados]['cns'] = $CNS;
			$duplicados[$conta_duplicados]['cpf'] = $CPF;
			$conta_duplicados++;
		}
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		//   Pega os dados
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		if (!$duplicado){
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			//   Completa CNS ou CPF
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			$pega_cca = "
				SELECT 
					nu_cns,
					nu_cpf
				FROM 
					tb_cidadao 
				WHERE 
					no_cidadao = '".$result1['no_cidadao']."' AND 
					dt_nascimento = '".$result1['dt_nascimento']."' AND 
					no_mae = '".$result1['no_mae']."'
			";
			$run_cca = pg_query($cdb,$pega_cca);
			if (pg_num_rows($run_cca) > 0){
				while ($lcca = pg_fetch_array($run_cca)){
					if (strlen($lcca['nu_cns']) == 15){
						if (strlen($CNS) != 15){
							$CNS = trim($lcca['nu_cns']);
						} else {
							if ($CNS != $lcca['nu_cns']){
								$CNSA = trim($lcca['nu_cns']);
							}
						}
					}
					if (strlen($lcca['nu_cpf']) == 11){
						if (strlen($CPF) != 11){
							$CPF = trim($lcca['nu_cpf']);
						} else {
							if ($CPF != $lcca['nu_cpf']){
								$CPFA = trim($lcca['nu_cpf']);
							}
						}
					}
					
				}
			}
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			//   Tratamento dos dados
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			$data_nascimento = dataint($result1['dt_nascimento']);
			$data_falecimento = "0";
			if (strlen(trim($result1['dt_obito'])) > 5){
				$data_falecimento = dataint($result1['dt_obito']);
			} else {
				if (strlen(trim($result1['dt_obito_ci'])) > 5){
					$data_falecimento = dataint($result1['dt_obito_ci']);
				}
			}
			$micro_area = $result1['nu_micro_area'];
			if (strlen($micro_area) == 1){
				$micro_area = '0'.$micro_area;
			}
			if (strlen($micro_area) == 0){
				$micro_area = '00';
			}
			$micro_area_c = $result1['nu_micro_area_c'];
			if (strlen($micro_area_c) == 1){
				$micro_area_c = '0'.$micro_area_c;
			}
			if (strlen($micro_area_c) == 0){
				$micro_area_c = '00';
			}
			$cns_resp = '0';
			$cpf_resp = '0';
			if (strlen($result1['nu_cns_responsavel']) == 15){
				$cns_resp = $result1['nu_cns_responsavel'];
			} else {
				$cns_resp = $result1['nu_cns_responsavel_c'];
			}
			if (strlen($result1['nu_cpf_responsavel']) == 11){
				$cpf_resp = $result1['nu_cpf_responsavel'];
			} else {
				$cpf_resp = $result1['nu_cpf_responsavel_c'];
			}
			if ($micro_area == '-' || $micro_area == '--' || $micro_area == '0-'){
				$micro_area = '00';
			}
			if ($micro_area_c == '-' || $micro_area_c == '--' || $micro_area_c == '0-'){
				$micro_area_c = '00';
			}
			if ($micro_area == '00'){
				if ($micro_area_c != '00'){
					$micro_area = $micro_area_c;
				}
			}
			$ine = $result1['nu_ine'];
			if ($ine == '-'){
				$ine = '0000000000';
			}
			$nome_cidadao = $result1['no_cidadao'];
			$nome_mae_cidadao = $result1['no_mae'];
			$cds = 0;
			$nome_cidadao = htmlspecialchars(trim($nome_cidadao), ENT_QUOTES);
			$nome_mae_cidadao = htmlspecialchars(trim($nome_mae_cidadao), ENT_QUOTES);
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			//   Valida CNS / CPF
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			if (!validaCNS($CNS)){
				$CNS = '0';
			}
			if (!validaCPF($CPF)){
				$CPF = '0';
			}
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			//   Prepara o inserir
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			$inserir = "
			INSERT INTO tmp_criancas(
				cns,
				cpf,
				cns_alternativo,
				cpf_alternativo,
				data_nascimento,
				co_seq_cidadao,
				cidadao_ativo,
				data_falecimento,
				cidadao_nome,
				cidadao_mae,
				cidadao_faleceu,
				cidadao_sexo,
				cns_resp,
				cpf_resp,
				cind_gestante,
				cind_hipertenso,
				cind_diabetico,
				cind_sexo,
				cind_micro_area,
				cind_inativo,
				cnes,
				nome_unidade,
				ine,
				nome_equipe,
				cds
			) VALUES (
				'".$CNS."',
				'".$CPF."',
				'".$CNSA."',
				'".$CPFA."',
				".$data_nascimento.",
				".$result1['co_seq_cidadao'].",
				".$result1['st_ativo'].",
				".$data_falecimento.",
				'".$nome_cidadao."',
				'".$nome_mae_cidadao."',
				".$result1['st_faleceu'].",
				'".$result1['no_sexo']."',
				'".$cns_resp."',
				'".$cpf_resp."',
				".$result1['st_gestante'].",
				".$result1['st_hipertensao_arterial'].",
				".$result1['st_diabete'].",
				'".$result1['ds_sexo']."',
				'".$micro_area."',
				".$result1['st_ficha_inativa'].",
				'".$result1['nu_cnes']."',
				'".$result1['no_unidade_saude']."',
				'".$ine."',
				'".$result1['no_equipe']."',
				".$cds."
			)";
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			//   Filtro
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			$gravar = false;
			if ($super_filtro){
				if ($cpbusca == 'U'){
					$vlbusca = zesq($vlbusca,7);
					if ($result1['nu_cnes'] == $vlbusca){
						$gravar = true;
					}
				}
				if ($cpbusca == 'E'){
					$vlbusca = zesq($vlbusca,10);
					if ($result1['nu_ine'] == $vlbusca){
						$gravar = true;
					}
				}
				if ($cpbusca == 'M'){
					$vlbusca = strtoupper($vlbusca);
					if (strlen($vlbusca) == 1){
						$vlbusca = '0'.$vlbusca;
					}
					if (strlen($vlbusca) == 0){
						$vlbusca = '00';
					}
					if ($micro_area == $vlbusca){
						$gravar = true;
					}
				}
				if ($cpbusca == 'C'){
					$vlbusca = zesq($vlbusca,15);
					if ($CNS == $vlbusca){
						$gravar = true;
					}
				}
				if ($cpbusca == 'P'){
					$vlbusca = zesq($vlbusca,11);
					if ($CPF == $vlbusca){
						$gravar = true;
					}
				}
			} else {
				$gravar = true;
			}
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			//   Usuario
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			if ($_SESSION['ine'] != 0){
				if ($ine != $_SESSION['ine']){
					$gravar = false;
				}
			}
			if ($_SESSION['cnes'] != 0){
				if ($cnes != $_SESSION['cnes']){
					$gravar = false;
				}
			}
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			//   Outras validacoes
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			if ($result1['st_faleceu'] != 0){
				$gravar = false;
			}
			if ($result1['st_ficha_inativa'] != 0){
				$gravar = false;
			}
			if ($result1['st_ativo'] != 1){
				$gravar = false;
			}
			if ($CNS == '0' && $CPF == '0'){
				$gravar = false;
			}
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			//   Gravar
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			if ($gravar){
				if ($cfa == 0){
					$run_ins = pg_query($cdb,$inserir);
					$conta_gravacao++;
				} else {
					if ($cpbusca != 'M'){
						if ($micro_area != '00' && $micro_area != 'FA'){
							$run_ins = pg_query($cdb,$inserir);
							$conta_gravacao++;
						}
					} else {
						$run_ins = pg_query($cdb,$inserir);
						$conta_gravacao++;
					}
				}
			}
		}
	}
}

// ===========================================================================================================
$periodo_show = "Análise entre ".dtshow($dti)." e ".dtshow($dtf);
$rel_cabecalho_2 = "
<table width=\"1009\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"relatorio-tabela\">
  <!--DWLayoutTable-->
  <tr> 
	<td width=\"440\" height=\"32\" valign=\"top\" class=\"relatorio-titulo\">Relatório de Crianças (de ".$idade_inicial." até ".$idade_final.")</td>
	<td width=\"392\" valign=\"top\" class=\"relatorio-periodo\">".$periodo_show."</td>
	<td width=\"177\" valign=\"top\" class=\"relatorio-pagina\">".qdata($dti)."</td>
  </tr>
</table>
";
fwrite($HTML, $rel_cabecalho_2);
$rel_dados_inicio = "
<table width=\"1009\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
  <!--DWLayoutTable-->
";
fwrite($HTML, $rel_dados_inicio);
// ===========================================================================================================

// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   Ordenacao
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$ordenar = "cidadao_nome";
$show_ordem = "Nome";
if ($ordem == 'C'){
	$ordenar = "cpf";
	$show_ordem = "CPF";
} else {
	if ($ordem == 'S'){
		$ordenar = "cns";
		$show_ordem = "CNS";
	} else {
		if ($ordem == 'DC'){
			$ordenar = "data_nascimento";
			$show_ordem = "Idade decrescente";
		} else {
			if ($ordem == 'DD'){
				$ordenar = "data_nascimento DESC";
				$show_ordem = "Idade crescente";
			}
		}
	}
}
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   Grupo
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$ordena_grupo = $grupo.",";
if ($grupo == "SG"){
	$ordena_grupo = "";
}
$quebra = "";
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   Pega dados da tabela temporaria
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$SQL_2 = "
SELECT
	*
FROM
	tmp_criancas
ORDER BY ".$ordena_grupo." ".$ordenar;
$run_mf = pg_query($cdb,$SQL_2);
$nm_sql_2 = 0;
$nm_sql_2 = pg_num_rows($run_mf);
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   SQL_2
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
if ($nm_sql_2 > 0){
	while ($result2 = pg_fetch_array($run_mf)){
		$conta_geral++;
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		//   Controle de duplicados
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		$duplicados_procedimentos = array();
		$conta_duplicados_procedimentos = 0;
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		//   CNS e CPF
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		$rCNS = trim($result2['cns']);
		$rCPF = trim($result2['cpf']);
		$rCNSA = trim($result2['cns_alternativo']);
		$rCPFA = trim($result2['cpf_alternativo']);
		if (strlen($rCNS) != 15){
			if (false !== $key = array_search($rCPF, array_column($duplicados, 'cpf'))) {
				if (strlen($duplicados[$key]['cns']) == 15){
					$rCNS = trim($duplicados[$key]['cns']);
				}
			}
		}
		if (strlen($rCPF) != 11){
			if (false !== $key = array_search($rCNS, array_column($duplicados, 'cns'))) {
				if (strlen($duplicados[$key]['cpf']) == 11){
					$rCPF = trim($duplicados[$key]['cpf']);
				}
			}
		}
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		//   Resumo equipes
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		if (false !== $key = array_search($result2['ine'], array_column($total_equipe, 'ine'))) {
			$total_equipe[$key]['den'] = $total_equipe[$key]['den'] + 1;
		} else {
			$total_equipe[$conta_total_equipe]['ine'] = $result2['ine'];
			$total_equipe[$conta_total_equipe]['nome'] = $result2['nome_equipe'];
			$total_equipe[$conta_total_equipe]['den'] = 1;
			$total_equipe[$conta_total_equipe]['num'] = 0;
			$conta_total_equipe++;
		}
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		//   Resumo unidades
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		if (false !== $key = array_search($result2['cnes'], array_column($total_unidade, 'cnes'))) {
			$total_unidade[$key]['den'] = $total_unidade[$key]['den'] + 1;
		} else {
			$total_unidade[$conta_total_unidade]['cnes'] = $result2['cnes'];
			$total_unidade[$conta_total_unidade]['nome'] = $result2['nome_unidade'];
			$total_unidade[$conta_total_unidade]['den'] = 1;
			$total_unidade[$conta_total_unidade]['num'] = 0;
			$conta_total_unidade++;
		}
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		//   Grupo
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		if ($grupo != "SG"){
			$grupo_id = $result2[$grupo];
			$grupo_nome = "Micro-Área";
			$show_gp_inverso = "Unidade";	
			$show_gp_id_inverso = $result2['cnes'];	
			$show_gp_nm_inverso = $result2['nome_unidade'];
			$show_gp_id2_inverso = $result2['ine'];
			$show_gp_nm2_inverso = "Equipe";
			if ($grupo == "ine"){
				$grupo_nome = $result2['nome_equipe'];	
				$show_gp_id2_inverso = $result2['cind_micro_area'];
				$show_gp_nm2_inverso = "Micro-&Aacute;rea";
			}
			if ($grupo == "cnes"){
				$grupo_nome = $result2['nome_unidade'];
				$show_gp_inverso = "Equipe";	
				$show_gp_id_inverso = $result2['ine'];	
				$show_gp_nm_inverso = $result2['nome_equipe'];
				$show_gp_id2_inverso = $result2['cind_micro_area'];
				$show_gp_nm2_inverso = "Micro-&Aacute;rea";
			}
			
			if ($quebra != $grupo_id){
				// ===========================================================================================================
				$rel_dados_grupo = "
				  <tr> 
					<td height=\"19\" colspan=\"12\" valign=\"top\" class=\"lista-equipe\">[ ".$grupo_id." ] ".$grupo_nome."</td>
					<td width=\"174\" valign=\"top\" class=\"lista-equipe2\">.</td>
				  </tr>
				  <tr> 
					<td width=\"23\" height=\"20\"></td>
					<td width=\"55\" valign=\"top\" class=\"lista-cabeca\">Seq.</td>
					<td width=\"113\" valign=\"top\" class=\"lista-cabeca\">CPF</td>
					<td colspan=\"2\" valign=\"top\" class=\"lista-cabeca\">CNS</td>
					<td colspan=\"5\" valign=\"top\" class=\"lista-cabeca\">Nome</td>
					<td width=\"109\" valign=\"top\" class=\"lista-cabeca\">Dta. Nascimento</td>
					<td colspan=\"2\" valign=\"top\" class=\"lista-cabeca\">Observações</td>
				  </tr>
				";
				fwrite($HTML, $rel_dados_grupo);
				// ===========================================================================================================
				$quebra = $grupo_id;
			}
		} else {
			$show_gp_inverso = "Unidade";	
			$show_gp_id_inverso = $result2['cnes'];	
			$show_gp_nm_inverso = $result2['nome_unidade']." (".$result2['ine'].")";
			$show_gp_id2_inverso = $result2['cind_micro_area'];
			$show_gp_nm2_inverso = "Micro-&Aacute;rea";
			// ===========================================================================================================
			$rel_dados_grupo = "
				  <tr> 
					<td height=\"19\" colspan=\"12\" valign=\"top\" class=\"lista-equipe\">.</td>
					<td width=\"174\" valign=\"top\" class=\"lista-equipe2\">.</td>
				  </tr>
			  <tr> 
				<td width=\"23\" height=\"20\"></td>
				<td width=\"55\" valign=\"top\" class=\"lista-cabeca\">Seq.</td>
				<td width=\"113\" valign=\"top\" class=\"lista-cabeca\">CPF</td>
				<td colspan=\"2\" valign=\"top\" class=\"lista-cabeca\">CNS</td>
				<td colspan=\"8\" valign=\"top\" class=\"lista-cabeca\">Nome</td>
				<td width=\"109\" valign=\"top\" class=\"lista-cabeca\">Dta. Nascimento</td>
				<td colspan=\"2\" valign=\"top\" class=\"lista-cabeca\">Observações</td>
			  </tr>
			";
			fwrite($HTML, $rel_dados_grupo);
			// ===========================================================================================================
		}
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		//   Arruma campo BUSCA
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		$campo_busca = "nu_cns = '000000000000000'";
		$campo_busca_res = "nu_cns_responsavel = '000000000000000'";
		if (strlen($rCNS) == 15 && strlen($rCPF) == 11){
			$campo_busca_res = "(nu_cns_responsavel = '".$rCNS."' OR nu_cpf_responsavel = '".$rCPF."')";
			$campo_busca = "(nu_cns = '".$rCNS."' OR nu_cpf_cidadao = '".$rCPF."'";
			if (strlen($rCNSA) == 15){
				$campo_busca .= " OR nu_cns = '".$rCNSA."'";
			}
			if (strlen($rCPFA) == 11){
				$campo_busca .= " OR nu_cpf = '".$rCPFA."'";
			}
			$campo_busca .= ")";
		} else {
			if (strlen($rCNS) == 15){
				$campo_busca = "nu_cns = '".$rCNS."'";
				$campo_busca_res = "nu_cns_responsavel = '".$rCNS."'";
				if (strlen($rCNSA) == 15){
					$campo_busca = "(nu_cns = '".$rCNS."' OR nu_cns = '".$rCNSA."')";
				}
			}
			if (strlen($rCPF) == 11){
				$campo_busca = "nu_cpf_cidadao = '".$rCPF."'";
				$campo_busca_res = "nu_cpf_responsavel = '".$rCPF."'";
				if (strlen($rCPFA) == 11){
					$campo_busca = "(nu_cpf_cidadao = '".$rCPF."' OR nu_cpf_cidadao = '".$rCPFA."')";
				}
			}
		}
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		//   Variaveis da BUSCA
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		$idade = idadeint($result2['data_nascimento'],$dtf);
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		//   PROCEDIMENTOS
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		$array_procedimentos = array();
		$cont_array_procedimentos = 0;
		$contro_cbo = "'2251%', '2252%', '2253%', '2231%', '2235%', '3222%'";

		$imunos_ar = explode(",", $imunos);
		$dose3 = false;
		$dose1_1 = false;
		$dose1_2 = false;
		$dose2_1 = false;
		$dose2_2 = false;
		$dose3_1 = false;
		$dose3_2 = false;
		$imunobiologicos = "";
		$imunobiologicos2 = "";
		foreach ($imunos_ar as $ims) {
			$imunobiologicos .= "'%|".trim($ims)."|%',";
			$imunobiologicos2 .= "'".trim($ims)."',";
		}
		$imunobiologicos = substr($imunobiologicos,0,-1);
		$imunobiologicos2 = substr($imunobiologicos2,0,-1);
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		//   (BUSCA) tabela 1
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		$select_tb1 = "
			SELECT 
				t5.co_seq_fat_vacinacao,
				t5.ds_filtro_dose_imunobiologico AS dose,
				t5.ds_filtro_imunobiologico AS imuno,
				t5.ds_filtro_lote AS lote,
				t5.ds_filtro_fabricante AS fabricante,
				t5.co_dim_tempo,
				t5.nu_cbo,
				t5.nu_cnes,
				t5.nu_ine,
				t5.nu_cns,
				t5.no_profissional
			FROM
			(
				SELECT
					t4.*,
					tb_dim_profissional.nu_cns,
					tb_dim_profissional.no_profissional
				FROM
				(
					SELECT
						t3.*,
						tb_dim_equipe.nu_ine
					FROM
					(
						SELECT
							t2.*,
							tb_dim_unidade_saude.nu_cnes
						FROM
						(
							SELECT
								t1.*,
								tb_dim_cbo.nu_cbo
							FROM
							(
								SELECT 
									co_seq_fat_vacinacao,
									ds_filtro_dose_imunobiologico, 
									ds_filtro_imunobiologico,
									co_dim_unidade_saude,
									co_dim_equipe,
									co_dim_profissional, 
									co_dim_cbo, 
									co_dim_tempo, 
									ds_filtro_lote, 
									ds_filtro_fabricante
								FROM 
									tb_fat_vacinacao 
								WHERE 
									".$campo_busca." AND
									ds_filtro_imunobiologico LIKE ANY (array[".$imunobiologicos."])
							) AS t1
							LEFT JOIN
								tb_dim_cbo
							ON tb_dim_cbo.co_seq_dim_cbo = t1.co_dim_cbo
						) AS t2
						LEFT JOIN
							tb_dim_unidade_saude
						ON tb_dim_unidade_saude.co_seq_dim_unidade_saude = t2.co_dim_unidade_saude
					) AS t3
					LEFT JOIN
						tb_dim_equipe
					ON tb_dim_equipe.co_seq_dim_equipe = t3.co_dim_equipe
				) AS t4
				LEFT JOIN
					tb_dim_profissional
				ON tb_dim_profissional.co_seq_dim_profissional = t4.co_dim_profissional
			) AS t5
			WHERE
				nu_cbo LIKE ANY (array[".$contro_cbo."])
			ORDER BY co_dim_tempo
		";
		$run_st1 = pg_query($cdb,$select_tb1);
		if (pg_num_rows($run_st1) > 0){
			while ($ex1 = pg_fetch_array($run_st1)){
				$ex_imuno_ar = explode("|", $ex1['imuno']);
				$ex_dose_ar = explode("|", $ex1['dose']);
				$ex_lote_ar = explode("|", $ex1['lote']);
				$ex_fabricante_ar = explode("|", $ex1['fabricante']);
				foreach ($imunos_ar as $ims) {
					for($m=0;$m<count($ex_imuno_ar);$m++){
						if (trim($ims) == $ex_imuno_ar[$m]){

							//$junta_vacinas = $ex1['co_dim_tempo'].$ex1['nu_cbo'].$ex1['nu_cnes'].$ex_imuno_ar[$m].$ex_dose_ar[$m].$ex_lote_ar[$m].$ex_fabricante_ar[$m];
							$junta_vacinas = $ex1['nu_cbo'].$ex1['nu_cnes'].$ex_imuno_ar[$m].$ex_dose_ar[$m];

							if (!in_array($junta_vacinas, $duplicados_procedimentos)) {
								$duplicados_procedimentos[$conta_duplicados_procedimentos] = $junta_vacinas;
								$conta_duplicados_procedimentos++;
								$sql_imu = "
									SELECT
										co_seq_dim_imunobiologico,
										sg_imunobiologico, 
										no_imunobiologico
									FROM
										tb_dim_imunobiologico
									WHERE
										nu_identificador = '".$ex_imuno_ar[$m]."'
									LIMIT 1
								";
								$run_im = pg_query($cdb,$sql_imu);
								$vac_grupo = '';
								$vac_regant = 0;
								if (pg_num_rows($run_im) > 0){
									$array_procedimentos[$cont_array_procedimentos]['imsg'] = pg_fetch_result($run_im,0,'sg_imunobiologico');
									$array_procedimentos[$cont_array_procedimentos]['imnome'] = pg_fetch_result($run_im,0,'no_imunobiologico');
									/*
									$dtvac = "											
										SELECT 
											t1.st_registro_anterior,
											tb_dim_grupo_atendimento.ds_grupo_atendimento
										FROM
										(
											SELECT 
												st_registro_anterior,
												co_dim_grupo_atendimento
											FROM 
												tb_fat_vacinacao_vacina
											WHERE 
												co_fat_vacinacao = ".$ex1['co_seq_fat_vacinacao']." AND
												co_dim_tempo = ".$ex1['co_dim_tempo']." AND
												co_dim_imunobiologico = ".pg_fetch_result($run_im,0,'co_seq_dim_imunobiologico')."
										) AS t1
										LEFT JOIN
											tb_dim_grupo_atendimento
										ON tb_dim_grupo_atendimento.co_seq_dim_grupo_atendimento = t1.co_dim_grupo_atendimento
									";
									$run_dtva = pg_query($cdb,$dtvac);
									if (pg_num_rows($run_dtva) > 0){
										$vac_grupo = pg_fetch_result($run_dtva,0,'ds_grupo_atendimento');
										$vac_regant = pg_fetch_result($run_dtva,0,'st_registro_anterior');
									}
									*/
								} else {
									$array_procedimentos[$cont_array_procedimentos]['imsg'] = "NE";
									$array_procedimentos[$cont_array_procedimentos]['imnome'] = "NE";
								}
								$array_procedimentos[$cont_array_procedimentos]['data'] = $ex1['co_dim_tempo'];
								$array_procedimentos[$cont_array_procedimentos]['cbo'] = $ex1['nu_cbo'];
								$array_procedimentos[$cont_array_procedimentos]['cnes'] = $ex1['nu_cnes'];
								$array_procedimentos[$cont_array_procedimentos]['ine'] = $ex1['nu_ine'];
								$array_procedimentos[$cont_array_procedimentos]['imuno'] = $ex_imuno_ar[$m];
								$array_procedimentos[$cont_array_procedimentos]['dose'] = $ex_dose_ar[$m];
								$array_procedimentos[$cont_array_procedimentos]['lote'] = $ex_lote_ar[$m];
								$array_procedimentos[$cont_array_procedimentos]['grupo'] = $vac_grupo;
								$array_procedimentos[$cont_array_procedimentos]['regant'] = $vac_regant;
								$array_procedimentos[$cont_array_procedimentos]['fabricante'] = $ex_fabricante_ar[$m];
								$array_procedimentos[$cont_array_procedimentos]['nu_cns'] = $ex1['nu_cns'];
								$array_procedimentos[$cont_array_procedimentos]['no_profissional'] = $ex1['no_profissional'];
								$array_procedimentos[$cont_array_procedimentos]['tabela'] = 'tb_fat_vacinacao';
								$cont_array_procedimentos++;
								if ($ex_imuno_ar[$m] == '22'){
									if ($ex_dose_ar[$m] == 1){
										$conta_polio_1++;
										$dose1_1 = true;
									}
									if ($ex_dose_ar[$m] == 2){
										$conta_polio_2++;
										$dose2_1 = true;
									}
									if ($ex_dose_ar[$m] == 3){
										$conta_polio_3++;
										$dose3_1 = true;
									}
								}
								if ($ex_imuno_ar[$m] == '42'){
									if ($ex_dose_ar[$m] == 1){
										$conta_penta_1++;
										$dose1_2 = true;
									}
									if ($ex_dose_ar[$m] == 2){
										$conta_penta_2++;
										$dose2_2 = true;
									}
									if ($ex_dose_ar[$m] == 3){
										$conta_penta_3++;
										$dose3_2 = true;
									}
								}
							}
						}
					}
				}
			}
		}
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		//   (BUSCA) tabela 2
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		if ($result2['co_seq_cidadao'] > 0){
			$mais_vacinas = "
				SELECT
					t12.*,
					tb_cbo.co_cbo_2002,
					tb_cbo.no_cbo
				FROM
				(
					SELECT
						t11.*,
						tb_equipe.nu_ine,
						tb_equipe.no_equipe
					FROM
					(
						SELECT
							t10.*,
							tb_unidade_saude.nu_cnes,
							tb_unidade_saude.no_unidade_saude
						FROM
						(
							SELECT 
								t9.*,
								tb_atend.co_unidade_saude,
								tb_atend.co_equipe
							FROM
							(
								SELECT
									t8.*,
									tb_prof.nu_cpf, 
									tb_prof.nu_cns, 
									tb_prof.no_profissional
								FROM
								(
									SELECT
										t7.*,
										tb_lotacao.co_prof,
										tb_lotacao.co_cbo
									FROM
									(
										SELECT
											t6.*,
											tb_atend_prof.co_atend,
											tb_atend_prof.co_lotacao,
											tb_atend_prof.co_seq_atend_prof
										FROM
										(
											SELECT 
												t5.*,
												tb_imunobiologico_fabricante.no_fabricante
											FROM
											(
												SELECT
													t4.*,
													tb_imunobiologico_lote.ds_lote,
													tb_imunobiologico_lote.co_imunobiologico_fabricante
												FROM
												(
													SELECT 
														t3.*,
														tb_imunobiologico.sg_imunobiologico,
														tb_imunobiologico.no_imunobiologico
													FROM
													(
														-- **********************************************************
														SELECT
															*
														FROM
														(
															SELECT 
																*
															FROM 
																tb_registro_vacinacao
															WHERE 
																co_imunobiologico IN (".$imunobiologicos2.")
																--co_imunobiologico IN (85,86,87,88)
														) AS t1
														INNER JOIN
														(
															SELECT
																co_seq_vacinacao,
																co_atend_prof
															FROM
																tb_vacinacao
															WHERE 
																co_prontuario = (
																	SELECT
																		co_seq_prontuario
																	FROM 
																		tb_prontuario
																	WHERE
																		co_cidadao = ".$result2['co_seq_cidadao']."
																		--co_cidadao = 4015
																	LIMIT 1
																)
														) AS t2
														ON t1.co_vacinacao = t2.co_seq_vacinacao
														-- **********************************************************
													) AS t3
													LEFT JOIN
														tb_imunobiologico
													ON tb_imunobiologico.co_imunobiologico = t3.co_imunobiologico
												) t4
												LEFT JOIN
													tb_imunobiologico_lote
												ON tb_imunobiologico_lote.co_seq_imunobiologico_lote = t4.co_imunobiologico_lote
											) AS t5
											LEFT JOIN
												tb_imunobiologico_fabricante
											ON tb_imunobiologico_fabricante.co_seq_imunobiologico_fabrcnt = t5.co_imunobiologico_fabricante
										) AS t6
										LEFT JOIN
											tb_atend_prof
										ON tb_atend_prof.co_seq_atend_prof = t6.co_atend_prof
									) AS t7
									LEFT JOIN
										tb_lotacao
									ON tb_lotacao.co_ator_papel = t7.co_lotacao
								) AS t8
								LEFT JOIN
									tb_prof
								ON tb_prof.co_seq_prof = t8.co_prof
							) AS t9
							LEFT JOIN
								tb_atend
							ON tb_atend.co_seq_atend = t9.co_atend
						) AS t10
						LEFT JOIN
							tb_unidade_saude
						ON tb_unidade_saude.co_seq_unidade_saude = t10.co_unidade_saude
					) AS t11
					LEFT JOIN
						tb_equipe
					ON tb_equipe.co_seq_equipe = t11.co_equipe
				) AS t12
				LEFT JOIN
					tb_cbo
				ON tb_cbo.co_cbo = t12.co_cbo
			";
			$run_mv = pg_query($cdb,$mais_vacinas);
			if (pg_num_rows($run_mv) > 0){
				while ($exmv = pg_fetch_array($run_mv)){
					$data_mais_vacina = 0;
					if (strlen(substr($exmv['dt_aplicacao'],0,10)) == 10){
						$data_mais_vacina = dataint(substr($exmv['dt_aplicacao'],0,10));
						
						//$junta_vacinas = $data_mais_vacina.$exmv['co_cbo_2002'].$exmv['nu_cnes'].$exmv['co_imunobiologico'].$exmv['co_dose_imunobiologico'].$exmv['ds_lote'].$exmv['no_fabricante'];
						$junta_vacinas = $exmv['co_cbo_2002'].$exmv['nu_cnes'].$exmv['co_imunobiologico'].$exmv['co_dose_imunobiologico'];

						if (!in_array($junta_vacinas, $duplicados_procedimentos)) {
							$duplicados_procedimentos[$conta_duplicados_procedimentos] = $junta_vacinas;
							$conta_duplicados_procedimentos++;

							$array_procedimentos[$cont_array_procedimentos]['imsg'] = $exmv['sg_imunobiologico'];
							$array_procedimentos[$cont_array_procedimentos]['imnome'] = $exmv['no_imunobiologico'];
							$array_procedimentos[$cont_array_procedimentos]['data'] = $data_mais_vacina;
							$array_procedimentos[$cont_array_procedimentos]['cbo'] = $exmv['co_cbo_2002'];
							$array_procedimentos[$cont_array_procedimentos]['cnes'] = $exmv['nu_cnes'];
							$array_procedimentos[$cont_array_procedimentos]['ine'] = $exmv['nu_ine'];
							$array_procedimentos[$cont_array_procedimentos]['imuno'] = $exmv['co_imunobiologico'];
							$array_procedimentos[$cont_array_procedimentos]['dose'] = $exmv['co_dose_imunobiologico'];
							$array_procedimentos[$cont_array_procedimentos]['lote'] = $exmv['ds_lote'];
							$array_procedimentos[$cont_array_procedimentos]['grupo'] = $exmv['co_grupo_atendimento'];
							$array_procedimentos[$cont_array_procedimentos]['regant'] = $exmv['st_registro_anterior'];
							$array_procedimentos[$cont_array_procedimentos]['fabricante'] = $exmv['no_fabricante'];
							$array_procedimentos[$cont_array_procedimentos]['nu_cns'] = $exmv['nu_cns'];
							$array_procedimentos[$cont_array_procedimentos]['no_profissional'] = $exmv['no_profissional'];
							$array_procedimentos[$cont_array_procedimentos]['tabela'] = 'tb_vacinacao';
							$cont_array_procedimentos++;
							if ($exmv['co_imunobiologico'] == '22'){
								if ($exmv['co_dose_imunobiologico'] == 1){
									$conta_polio_1++;
									$dose1_1 = true;
								}
								if ($exmv['co_dose_imunobiologico'] == 2){
									$conta_polio_2++;
									$dose2_1 = true;
								}
								if ($exmv['co_dose_imunobiologico'] == 3){
									$conta_polio_3++;
									$dose3_1 = true;
								}
							}
							if ($exmv['co_imunobiologico'] == '42'){
								if ($exmv['co_dose_imunobiologico'] == 1){
									$conta_penta_1++;
									$dose1_2 = true;
								}
								if ($exmv['co_dose_imunobiologico'] == 2){
									$conta_penta_2++;
									$dose2_2 = true;
								}
								if ($exmv['co_dose_imunobiologico'] == 3){
									$conta_penta_3++;
									$dose3_2 = true;
								}
							}
						}
					}
				}
			}
		}
		if ($d3c == 0){
			if ($cd3 == 'E'){
				if ($dose3_1 && $dose3_2 && $dose2_1 && $dose2_2 && $dose1_1 && $dose1_2){
					$dose3 = true;
				}
			} else {
				if (($dose3_1 && $dose2_1 && $dose1_1) || ($dose3_2 && $dose2_2 && $dose1_2)){
					$dose3 = true;
				}
			}
		} else {
			if ($cd3 == 'E'){
				if ($dose3_1 && $dose3_2){
					$dose3 = true;
				}
			} else {
				if ($dose3_1 || $dose3_2){
					$dose3 = true;
				}
			}
		}

		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		//   FAMILIA
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		$ma_familiar = '00';
		$familia = "
			SELECT 
				nu_cns_responsavel,
				nu_micro_area,
				nu_cpf_responsavel
			FROM
				tb_fat_cad_dom_familia
			WHERE
				".$campo_busca_res."
			ORDER BY co_dim_tempo DESC
			LIMIT 1
		";
		$run_sfm = pg_query($cdb,$familia);
		if (pg_num_rows($run_sfm) <= 0){
			$campo_busca_res = "nu_cns_responsavel = '000000000000000'";
			if (strlen($result2['cns_resp']) == 15 && strlen($result2['cpf_resp']) == 11){
				$campo_busca_res = "(nu_cns_responsavel = '".$result2['cns_resp']."' OR nu_cpf_responsavel = '".$result2['cpf_resp']."')";
			} else {
				if (strlen($result2['cns_resp']) == 15){
					$campo_busca_res = "nu_cns_responsavel = '".$result2['cns_resp']."'";
				}
				if (strlen($result2['cpf_resp']) == 11){
					$campo_busca_res = "nu_cpf_responsavel = '".$result2['cpf_resp']."'";
				}
			}
			$familia = "
				SELECT 
					nu_cns_responsavel,
					nu_micro_area,
					nu_cpf_responsavel
				FROM
					tb_fat_cad_dom_familia
				WHERE
					".$campo_busca_res."
				ORDER BY co_dim_tempo DESC
				LIMIT 1
			";
			$run_sfm2 = pg_query($cdb,$familia);
			if (pg_num_rows($run_sfm2) > 0){
				$ma_familiar = pg_fetch_result($run_sfm2,0,'nu_micro_area');
				if (strlen($ma_familiar) == 0 || $ma_familiar == '0' || $ma_familiar == NULL){
					$ma_familiar = '00';
				}
				if (strlen($ma_familiar) == 1){
					$ma_familiar = '0'.$ma_familiar;
				}
			}
		} else {
			$ma_familiar = pg_fetch_result($run_sfm,0,'nu_micro_area');
			if (strlen($ma_familiar) == 0 || $ma_familiar == '0' || $ma_familiar == NULL){
				$ma_familiar = '00';
			}
			if (strlen($ma_familiar) == 1){
				$ma_familiar = '0'.$ma_familiar;
			}
		}
		if (strlen($show_gp_id2_inverso) == 2){
			$show_gp_id2_inverso = $show_gp_id2_inverso."|".$ma_familiar;
		}
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		//   MONTA RELATORIO
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		$marcado_gestante = "NÃO";
		$marcado_hipertenso = "NÃO";
		$marcado_diabetico = "NÃO";
		if ($result2['cind_gestante'] == 1){
			$marcado_gestante = "SIM";
		}
		if ($result2['cind_hipertenso'] == 1){
			$marcado_hipertenso = "SIM";
			$contator_gl_hipertenso++;
		}
		if ($result2['cind_diabetico'] == 1){
			$marcado_diabetico = "SIM";
			$contator_gl_diabetico++;
		}
		$estilo_inco = "lista-status";
		$inconsistencias = "";
		if (strlen($rCNSA) == 15){
			$inconsistencias .= "Outro CNS: ".$rCNSA."<br>";
		}
		if (strlen($rCPFA) == 11){
			$inconsistencias .= "Outro CPF: ".$rCPFA."<br>";
		}
		$doc_valido = 0;
		if (strlen($rCNS) == 15){
			if (!validaCNS($rCNS)){
				$inconsistencias .= "CNS considerado inválido.<br>";
				$doc_valido++;
			}
		}
		if (strlen($rCPF) == 11){
			if (!validaCPF($rCPF)){
				$inconsistencias .= "CPF considerado inválido.<br>";
				$doc_valido++;
			}
		}
		if ($doc_valido >= 2){
			$inconsistencias .= "-<br>";
			$conta_geral_desconto++;
		}
		if ($rCNS == '0' && $rCPF == '0'){
			$inconsistencias .= "Sem CNS e sem CPF.<br>-<br>";
			$conta_geral_desconto++;
		}
		if ($ma_familiar == '00' && $result2['cind_micro_area'] == '00'){
			$inconsistencias .= "Criança sem cadastro familiar.<br>";
		}
		if (substr($result2['cidadao_nome'],0,1) == '#'){
			$inconsistencias .= "Criança não localizada em tb_cidadao.<br>";
		}
		$inativo2 = 0;
		if ($result2['cidadao_ativo'] != 1){
			//$inconsistencias .= "Cadastro está como inativo (Cidadao).<br>";
			$inativo2++;
		}
		if ($result2['cind_inativo'] != 0){
			$inconsistencias .= "Cadastro está como inativo (Cad. Ind.).<br>";
			$inativo2++;
		}
		if ($inativo2 >= 2){
			$conta_geral_desconto++;
			$inconsistencias .= "-<br>";
		}
		$faleceu2 = 0;
		if ($result2['cidadao_faleceu'] != 0){
			$inconsistencias .= "Faleceu (Cidadao)<br>";
			$faleceu2++;
		}
		if (strlen($result2['data_falecimento']) > 5){
			$inconsistencias .= "Faleceu em ".dtshow($result2['data_falecimento'])."<br>";
			$faleceu2++;
		}
		if ($faleceu2 >= 2){
			$conta_geral_desconto++;
			$inconsistencias .= "-<br>";
		}
		$idade_meses = ceil(difdatas($result2['data_nascimento'],$dtf)/30)-1;
		if ($idade <= 0 && substr($result2['data_nascimento'],-4) > date('md')){
			$dta_sub = date('Y').substr($result2['data_nascimento'],4,4);
			$faltam_dias = difdatas($dtf,$dta_sub);
			if ($faltam_dias < 180){
				//$statusC = "lista-status1";
				$inconsistencias .= "Faltam ".ceil($faltam_dias)." dias para completar 1 ano";
			}
		}
		if (strlen($inconsistencias) > 0){
			$estilo_inco = "lista-status-Vermelho";
		}
		$vacina_citopatologico = "NÃO";
		$estilo_excito = "indicador-c1-NAO";
		$estilo_excito_indicador = "indicador-titulo-Vermelho-B";
		if ($dose3){
			$vacina_citopatologico = "SIM";
			$estilo_excito = "indicador-c1-SIM";
			$estilo_excito_indicador = "indicador-titulo-Verde-B";
			$numerador_ind5++;
			if (false !== $key = array_search($result2['ine'], array_column($total_equipe, 'ine'))) {
				$total_equipe[$key]['num'] = $total_equipe[$key]['num'] + 1;
			}
			if (false !== $key = array_search($result2['cnes'], array_column($total_unidade, 'cnes'))) {
				$total_unidade[$key]['num'] = $total_unidade[$key]['num'] + 1;
			}
		}
		// ===========================================================================================================
		$rel_dados_unitario = "
		  <tr> 
			<td height=\"18\"></td>
			<td valign=\"top\" class=\"lista-dado1-centro-BB\">".zesq($conta_geral,5)."</td>
			<td valign=\"top\" class=\"lista-dado1-centro-B\">".mcpf($rCPF)."</td>
			<td colspan=\"2\" valign=\"top\" class=\"lista-dado1-centro-B\">".mcns($rCNS)."</td>
			<td colspan=\"5\" valign=\"top\" class=\"lista-dado1-esquerdo-B\">".$result2['cidadao_nome']."</td>
			<td valign=\"top\" class=\"lista-dado1-centro-B\">".dtshow($result2['data_nascimento'])."</td>
			<td colspan=\"2\" rowspan=\"4\" valign=\"top\" class=\"".$estilo_inco."\"><p>
			".$inconsistencias."
			</p></td>
		  </tr>
		  <tr> 
			<td height=\"19\"></td>
			<td valign=\"top\" class=\"lista-dado1-centro-B\"><!--DWLayoutEmptyCell-->&nbsp;</td>
			<td colspan=\"2\" valign=\"top\" class=\"lista-sub-dados-BB\">Nome da m&atilde;e (".$result2['cds'].")</td>
			<td colspan=\"7\" valign=\"top\" class=\"lista-dado2-centro-B\">".$result2['cidadao_mae']."</td>
		  </tr>
		  <tr> 
			<td height=\"18\"></td>
			<td></td>
			<td colspan=\"2\" valign=\"top\" class=\"lista-sub-dados-BB\">".$show_gp_inverso."</td>
			<td colspan=\"2\" valign=\"top\" class=\"lista-dado2-centro-B\">".$show_gp_id_inverso."</td>
			<td colspan=\"5\" valign=\"top\" class=\"lista-dado2-centro-B\">".$show_gp_nm_inverso."</td>
		  </tr>

		  <tr> 
			<td height=\"19\"></td>
			<td></td>
			<td colspan=\"2\" valign=\"top\" class=\"lista-sub-dados-BB\">Declarada Diab&eacute;tica</td>
			<td colspan=\"2\" valign=\"top\" class=\"lista-dado2-centro-B\">".$marcado_diabetico."</td>
			<td colspan=\"2\" valign=\"top\" class=\"lista-sub-dados-B\">".$show_gp_nm2_inverso."</td>
			<td valign=\"top\" class=\"lista-dado2-centro-B\">".$show_gp_id2_inverso."</td>
			<td valign=\"top\" class=\"lista-sub-dados-B\">Idade</td>
			<td valign=\"top\" class=\"lista-dado2-centro-B\">".$idade_meses." meses</td>
		  </tr>
		  <tr> 
			<td height=\"16\"></td>
			<td></td>
			<td colspan=\"2\" rowspan=\"2\" valign=\"top\" class=\"".$estilo_excito_indicador."\">Indicador 5 [ ".$vacina_citopatologico." ]</td>
			<td colspan=\"3\" rowspan=\"2\" valign=\"top\" class=\"indicador-c1-B\">Vacinação de crianças até 1 ano (".$tpb." meses)</td>
			<td colspan=\"4\" rowspan=\"2\" valign=\"top\" class=\"indicador-c1-centro-X-B\">Imunobiológico(s): ".$imunos."</td>
			<td colspan=\"2\" valign=\"top\" class=\"indicador-c1\">.</td>
		  </tr>
		  <tr> 
			<td height=\"16\"></td>
			<td></td>
			<td colspan=\"2\" valign=\"top\" class=\"indicador-cx-B\">.</td>
		  </tr>
		  ";
		fwrite($HTML, $rel_dados_unitario);
		$texto = zesq($conta_geral,5).";".mcpf($rCPF).";".mcns($rCNS).";".str_replace("Ã","A",$result2['cidadao_nome']).";".dtshow($result2['data_nascimento']).";".str_replace("Ã","A",$result2['cidadao_mae']).";".$idade.";".str_replace("Ã","A",$marcado_gestante).";".str_replace("Ã","A",$marcado_hipertenso).";".str_replace("Ã","A",$marcado_diabetico).";".str_replace("Ã","A",$vacina_citopatologico).";".$result2['cnes'].";".$result2['ine'].";".$ma_familiar."/".$result2['cind_micro_area']."\r\n";
		fwrite($FT, $texto);
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		//   PROCEDIMENTOS (MONTA RELATORIO)
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		if (count($array_procedimentos) > 0){
			usort($array_procedimentos, 'cmp');
			$rel_dados_sub_tabela = "
			  <tr> 
				<td height=\"54\"></td>
				<td></td>
				<td></td>
				<td width=\"86\">&nbsp;</td>
				<td colspan=\"9\" valign=\"top\">
				<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"procedimentos-tabela\">
					<!--DWLayoutTable-->
					<tr> 
					  <td width=\"36\" height=\"18\"></td>
					  <td width=\"169\" valign=\"top\" class=\"procedimentos-cabeca\">Imunobiológico</td>
					  <td width=\"75\" valign=\"top\" class=\"procedimentos-cabeca\">Data</td>
					  <td width=\"213\" valign=\"top\" class=\"procedimentos-cabeca\">Lote / Fabricante / CNS Prof.</td>
					  <td width=\"43\" valign=\"top\" class=\"procedimentos-cabeca\">CBO</td>
					  <td width=\"78\" valign=\"top\" class=\"procedimentos-cabeca\">INE</td>
					  <td width=\"56\" valign=\"top\" class=\"procedimentos-cabeca\">CNES</td>
					  <td width=\"62\" valign=\"top\" class=\"procedimentos-cabeca\">Dose</td>
					</tr>
			";
			$soma_vacinas = 0;
			for ($i=0;$i<count($array_procedimentos);$i++){
				$soma_vacinas++;
				
				$tbvacinacao = '';
				if ($array_procedimentos[$i]['tabela'] == 'tb_fat_vacinacao'){
					$tbvacinacao = '*';
				}
				
				$idade_meses_dose = ceil(difdatas($result2['data_nascimento'],$array_procedimentos[$i]['data'])/30)-1;
				
				$texto = $soma_vacinas.";".mcpf($rCPF).";".mcns($rCNS).";".dtshow($array_procedimentos[$i]['data']).";".$array_procedimentos[$i]['dose'].";".$array_procedimentos[$i]['cnes'].";".$array_procedimentos[$i]['ine'].";".$array_procedimentos[$i]['cbo'].";".$array_procedimentos[$i]['imuno'].";".$array_procedimentos[$i]['lote'].";".$array_procedimentos[$i]['fabricante'].";".$array_procedimentos[$i]['nu_cns'].";".$array_procedimentos[$i]['no_profissional'].";".$array_procedimentos[$i]['imsg'].";".$array_procedimentos[$i]['imnome'].";".$array_procedimentos[$i]['grupo'].";".$array_procedimentos[$i]['regant'].";".$array_procedimentos[$i]['tabela']."\r\n";
				fwrite($FV, $texto);
				
				$rel_dados_sub_tabela .= "
						<tr> 
						  <td height=\"18\" valign=\"top\" class=\"procedimentos-dado\">".$soma_vacinas."</td>
						  <td valign=\"top\" class=\"procedimentos-dado\">[".$array_procedimentos[$i]['imuno']."] ".$array_procedimentos[$i]['imsg']."<br>".$array_procedimentos[$i]['imnome']."</td>
						  <td valign=\"top\" class=\"procedimentos-dado\">".dtshow($array_procedimentos[$i]['data'])." ".$tbvacinacao."<br>".$idade_meses_dose." meses</td>
						  <td valign=\"top\" class=\"procedimentos-dado\">".$array_procedimentos[$i]['lote']." / ".$array_procedimentos[$i]['fabricante']."<br>".$array_procedimentos[$i]['nu_cns']."</td>
						  <td valign=\"top\" class=\"procedimentos-dado\">".$array_procedimentos[$i]['cbo']."</td>
						  <td valign=\"top\" class=\"procedimentos-dado\">".$array_procedimentos[$i]['ine']."</td>
						  <td valign=\"top\" class=\"procedimentos-dado\">".$array_procedimentos[$i]['cnes']."</td>
						  <td valign=\"top\" class=\"procedimentos-dado\">".$array_procedimentos[$i]['dose']."</td>
						</tr>
				";
			}
			$rel_dados_sub_tabela .= "
				  </table>
				  </td>
			  </tr>
			";
			fwrite($HTML, $rel_dados_sub_tabela);
		}
	}
}

// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   Paginacao final
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$link_pag = "";
if ($paginacao > 0){
	$pini = $pini + $nm_sql_2;
	$num = $numerador_ind5;
	$cdes = $conta_geral_desconto;
	if ($nm_sql_2 > 0){
		$link_pag = "<a href=\"rel_criancas.php?pini=".$pini."&num=".$num."&cdes=".$cdes."\"><img src=\"plugins/scsus/temas/".$_SESSION['tema']."/img/pagina.png\"></a>";
	}
}
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   Resultado final
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$show_desconto = "";
if ($conta_geral_desconto > 0){
	$show_desconto = " (descontar ".$conta_geral_desconto." crianca(es) por conta de inconsistências)";
}
$total_geral = $conta_geral;
if ($paginacao > 0){
	$total_geral = $conta_gravacao;
}

// ===========================================================================================================
$rel_dados_final = "
  <tr> 
    <td height=\"1\"></td>
    <td></td>
    <td></td>
    <td width=\"86\"></td>
    <td width=\"64\"></td>
    <td width=\"19\"></td>
    <td width=\"106\"></td>
    <td width=\"62\"></td>
    <td></td>
    <td></td>
    <td></td>
    <td width=\"45\"></td>
    <td></td>
  </tr>
</table>
";
fwrite($HTML, $rel_dados_final);
$rel_rodape = "
<table width=\"1009\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"rodape-tabela\">
  <!--DWLayoutTable-->
  <tr> 
    <td width=\"1009\" height=\"25\" valign=\"top\" class=\"rodape-texto\">
	".$sobre['nome']." | 
	".$sobre['versao']." | 
	".date('d/m/Y')." | 
	Total de ".$total_geral." crianças | 
	Ordenado por ".$show_ordem." | ".$dbdb." |
	".$link_pag."
	</td>
  </tr>
</table>
";
fwrite($HTML, $rel_rodape);
if ($paginacao <= 0 || $nm_sql_2 == 0){
	// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	//   GRAVA RESUMO
	// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	$denominador_final = $conta_geral - $conta_geral_desconto;
	$file = "resumo/r_ind5_".$_SESSION['key'].".php";
	if (file_exists($file)){unlink($file);}
	$FIN = fopen($file,'w');
	$porc_ind = 0;
	if ($denominador_final > 0){
		$porc_ind = (100 * $numerador_ind5) / $denominador_final;
	}
	$porc_ind_est = 0;
	$deno_esti = "";
	if ($des > 0){
		$porc_ind_est = (100 * $numerador_ind5) / $des;
		$deno_esti = "(Estimado: ".$des.")";
	}
	$txind = "<?php
	\$den_ind_5 = ".$denominador_final.";
	\$den_est_5 = ".$des.";
	\$num_5 = ".$numerador_ind5.";
	\$num_prc_5 = ".$porc_ind.";
	\$num_prc_est_5 = ".$porc_ind_est.";
	\$num_penta_1_5 = ".$conta_penta_1.";
	\$num_penta_2_5 = ".$conta_penta_2.";
	\$num_penta_3_5 = ".$conta_penta_3.";
	\$num_polio_1_5 = ".$conta_polio_1.";
	\$num_polio_2_5 = ".$conta_polio_2.";
	\$num_polio_3_5 = ".$conta_polio_3.";
	\$quadri_5 = \"".qdata($dti)."\";
	\$versao_5 = \"".$sobre['versao']."\";
	\$banco_5 = \"".$dbdb."\";
	\$datahora_5 = \"".date('d/m/Y H:i:s')."\";
	\$dti_5 = ".$dti.";
	\$dtf_5 = ".$dtf.";
	\$incons_5 = ".$conta_geral_desconto.";
	\$c_hiper_5 = ".$contator_gl_hipertenso.";
	\$c_diabe_5 = ".$contator_gl_diabetico.";
	";
	for ($e=0;$e<count($total_equipe);$e++){
		$txind .= "\r\$equipe_5[".$e."]['ine'] = \"".$total_equipe[$e]['ine']."\"; \$equipe_5[".$e."]['nome'] = \"".$total_equipe[$e]['nome']."\"; \$equipe_5[".$e."]['den'] = \"".$total_equipe[$e]['den']."\"; \$equipe_5[".$e."]['num'] = \"".$total_equipe[$e]['num']."\";";
	}
	for ($e=0;$e<count($total_unidade);$e++){
		$txind .= "\r\$unidade_5[".$e."]['cnes'] = \"".$total_unidade[$e]['cnes']."\"; \$unidade_5[".$e."]['nome'] = \"".$total_unidade[$e]['nome']."\"; \$unidade_5[".$e."]['den'] = \"".$total_unidade[$e]['den']."\"; \$unidade_5[".$e."]['num'] = \"".$total_unidade[$e]['num']."\";";
	}
	$txind .= "
	?>\r\n";
	fwrite($FIN, $txind);
	fclose($FIN);
	// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	$rel_indicadores = "
	</table>
	<table width=\"1009\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
	  <!--DWLayoutTable-->
	  <tr> 
		<td width=\"214\" height=\"30\">&nbsp;</td>
		<td width=\"260\" valign=\"top\" class=\"resumo-sub-titulo\">Indicador 5</td>
		<td width=\"120\">&nbsp;</td>
	  </tr>
	  <tr> 
		<td height=\"30\" valign=\"top\" class=\"resumo-numerador\">Numerador&nbsp;&nbsp;</td>
		<td valign=\"top\" class=\"resumo-v-numerador\">".$numerador_ind5." [ ".ceil($porc_ind)."% ] (22d3: ".$conta_polio_3." | 42d3: ".$conta_penta_3.")</td>
		<td></td>
	  </tr>
	  <tr> 
		<td height=\"30\" valign=\"top\" class=\"resumo-denominador\">Denominador&nbsp;&nbsp;</td>
		<td valign=\"top\" class=\"resumo-v-denominador\">".$denominador_final." ".$deno_esti."</td>
		<td></td>
	  </tr>
	</table>
	";
	fwrite($HTML, $rel_indicadores);
}
$rel_pagina_final = "
        </div>
        <!-- /.row -->
		</form>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
	
    <a id=\"back-to-top\" href=\"#\" class=\"btn btn-primary back-to-top\" role=\"button\" aria-label=\"Scroll to top\">
      <i class=\"fas fa-chevron-up\"></i>
    </a>
	
  </div>
<script>
	function printDiv(divName) {
		 var printContents = document.getElementById(divName).innerHTML;
		 var originalContents = document.body.innerHTML;
		 document.body.innerHTML = printContents;
		 window.print();
		 document.body.innerHTML = originalContents;
	}
</script>
";
fwrite($HTML, $rel_pagina_final);
// ===========================================================================================================

fclose($HTML);
fclose($FT);
fclose($FV);

?>
