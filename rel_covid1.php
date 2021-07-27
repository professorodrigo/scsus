<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   15/07/2021
//   Rodrigo Silva
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   Includes
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

require_once('session.php');

$mensagem = "";
if (file_exists("config/banco_".$_SESSION['key'].".php")){
	require_once("config/banco_".$_SESSION['key'].".php");
} else {
	$mensagem = "
	  <script type=\"text/javascript\">
		$(document).ready(function() {
		  var unique_id = $.gritter.add({
			title: 'ATENÇÃO',
			text: 'Antes de qualquer coisa configure o DB',
			image: 'dist/img/user01.png',
			sticky: false,
			time: 8000,
			class_name: 'my-sticky-class'
		  });
		  return false;
		});
	  </script>
	";	
}
if (file_exists("config/c_rel_v_".$_SESSION['key'].".php")){
	require_once("config/c_rel_v_".$_SESSION['key'].".php");
} else {
	$mensagem = "
	  <script type=\"text/javascript\">
		$(document).ready(function() {
		  var unique_id = $.gritter.add({
			title: 'ATENÇÃO',
			text: 'Antes de qualquer coisa configure o relatório',
			image: 'dist/img/user01.png',
			sticky: false,
			time: 8000,
			class_name: 'my-sticky-class'
		  });
		  return false;
		});
	  </script>
	";
}
echo $mensagem;
if (file_exists("config/dados_".$_SESSION['key'].".php")){
	require_once("config/dados_".$_SESSION['key'].".php");
} else {
	$mensagem = "
	  <script type=\"text/javascript\">
		$(document).ready(function() {
		  var unique_id = $.gritter.add({
			title: 'ATENÇÃO',
			text: 'Antes de qualquer coisa configure os dados do relatório',
			image: 'dist/img/user01.png',
			sticky: false,
			time: 8000,
			class_name: 'my-sticky-class'
		  });
		  return false;
		});
	  </script>
	";
}
echo $mensagem;

require_once('connect.php');
require_once('functions.php');
require_once('sobre.php');

// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   Criação dos arquivos
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

// -----------------------------------------------------------------------
$file = "html/rel_covid1_".$_SESSION['key'].".html";
if (file_exists($file)){unlink($file);}
$HTML = fopen($file,'w');
// -----------------------------------------------------------------------
$file = "csv/covid_T_".$_SESSION['key'].".csv";
if (file_exists($file)){unlink($file);}
$FT = fopen($file,'w');
$texto = "Seq;CPF;CNS;Nome;Idade;Dose;Imuno;Lote;Fabricante;DtAplicacao;CNSProf;NomeProf;ImunoNome;GrupoAt;RegAnt;Tabela\r\n";
fwrite($FT, $texto);

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

$imunos = isset($_GET["ims"]) ? trim($_GET["ims"]) : "85,86,87,88";
$vtitulo = isset($_GET["vt"]) ? trim($_GET["vt"]) : "COVID-19";
$dti = isset($_GET["dti"]) ? trim($_GET["dti"]) : 20200101;
$dtf = isset($_GET["dtf"]) ? trim($_GET["dtf"]) : date('Ymd');
$idade_inicial = isset($_GET["idi"]) ? trim($_GET["idi"]) : 0;
$idade_final = isset($_GET["idf"]) ? trim($_GET["idf"]) : 150;
// -----------------------------------------------------------------------
$a_dti = (int) substr($dti,0,4);
$a_dtix = $a_dti;
$m_dti = substr($dti,4,2);
$d_dti = substr($dti,6,2);
$a_dti = $a_dti - $idade_final;
$dti_nas = $a_dti.'-'.$m_dti.'-'.$d_dti;
// -----------------------------------------------------------------------
$a_dtf = (int) substr($dtf,0,4);
$a_dtfx = $a_dtf;
$m_dtf = substr($dtf,4,2);
$d_dtf = substr($dtf,6,2);
$a_dtf = $a_dtf - $idade_inicial;
$dtf_nas = $a_dtf.'-'.$m_dtf.'-'.$d_dtf;
// -----------------------------------------------------------------------
$conta_geral = $pini;
$conta_geral_desconto = $cdes;
$numerador_ind5 = $num;
$conta_gravacao = 0;
// -----------------------------------------------------------------------
$duplicados = array();
$conta_duplicados = 0;

// ===========================================================================================================
$rel_pagina_inicio = "
  <!-- Content Wrapper. Contains page content -->
  <div class=\"content-wrapper\">
    <!-- Content Header (Page header) -->
    <section class=\"content-header\">
      <div class=\"container-fluid\">
        <div class=\"row mb-2\">
          <div class=\"col-sm-6\">
            <h1>COVID-19</h1>
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
	<table width=\"1088\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"cabeca-tabela\">
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
CREATE TEMPORARY TABLE tmp_vacinados (
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
	nome_equipe varchar(255)
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
		text '# USUARIO NÃO ENCONTRADO #' as no_cidadao,
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
			$cidadao_mae = "# SEM NOME DA MAE #";
			if (strlen(trim($result1['no_mae'])) > 0){
				$cidadao_mae = trim($result1['no_mae']);
			}
			$cidadao_nome = htmlspecialchars(trim($result1['no_cidadao']), ENT_QUOTES);
			$cidadao_mae = htmlspecialchars($cidadao_mae, ENT_QUOTES);
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
			INSERT INTO tmp_vacinados(
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
				nome_equipe
			) VALUES (
				'".$CNS."',
				'".$CPF."',
				'".$CNSA."',
				'".$CPFA."',
				".$data_nascimento.",
				".$result1['co_seq_cidadao'].",
				".$result1['st_ativo'].",
				".$data_falecimento.",
				'".$cidadao_nome."',
				'".$cidadao_mae."',
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
				'".$result1['no_equipe']."'
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
				//$gravar = false;
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
$rel_cabecalho_2 = "
<table width=\"1088\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"relatorio-tabela\">
  <!--DWLayoutTable-->
  <tr> 
	<td width=\"440\" height=\"32\" valign=\"top\" class=\"relatorio-titulo\">Vacina ".$vtitulo."</td>
	<td width=\"392\" valign=\"top\" class=\"relatorio-periodo\">Todos os vacinados [".$imunos."]</td>
	<td width=\"177\" valign=\"top\" class=\"relatorio-pagina\">(transparência)</td>
  </tr>
</table>
";
fwrite($HTML, $rel_cabecalho_2);
$rel_dados_inicio = "
<table width=\"1088\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
  <!--DWLayoutTable-->
";
fwrite($HTML, $rel_dados_inicio);
$Hsubtitulo = "
<table width=\"1088\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
  <tr> 
	<td width=\"55\" height=\"20\" valign=\"top\" class=\"lista-cabeca\">Seq.</td>
	<td width=\"104\" valign=\"top\" class=\"lista-cabeca\">CPF</td>
	<td width=\"126\" valign=\"top\" class=\"lista-cabeca\">CNS</td>
	<td width=\"311\" valign=\"top\" class=\"lista-cabeca\">Nome</td>
	<td width=\"54\" valign=\"top\" class=\"lista-cabeca\">Idade</td>
	<td width=\"36\" valign=\"top\" class=\"lista-cabeca\">Dose</td>
	<td width=\"215\" valign=\"top\" class=\"lista-cabeca\">Imuno</td>
	<td width=\"100\" valign=\"top\" class=\"lista-cabeca\">Aplica&ccedil;&atilde;o</td>
	<td width=\"87\" valign=\"top\" class=\"lista-cabeca\">Aplicador</td>
  </tr>
";
fwrite($HTML, $Hsubtitulo);
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
//   Pega dados da tabela temporaria
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$SQL_2 = "
SELECT
	*
FROM
	tmp_vacinados
ORDER BY ".$ordenar;
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
		//$conta_geral++;
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
		//   Arruma campo BUSCA
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		$campo_busca = "nu_cns = '000000000000000'";
		if (strlen($rCNS) == 15 && strlen($rCPF) == 11){
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
				if (strlen($rCNSA) == 15){
					$campo_busca = "(nu_cns = '".$rCNS."' OR nu_cns = '".$rCNSA."')";
				}
			}
			if (strlen($rCPF) == 11){
				$campo_busca = "nu_cpf_cidadao = '".$rCPF."'";
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

		$imunos_ar = explode(",", $imunos);
		$dose3 = false;
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
							
							//$junta_procedimentos = $ex1['co_dim_tempo'].$ex1['nu_cbo'].$ex1['nu_cnes'].$ex_imuno_ar[$m].$ex_dose_ar[$m].$ex_lote_ar[$m].$ex_fabricante_ar[$m];
							$junta_procedimentos = $ex1['nu_cbo'].$ex1['nu_cnes'].$ex_imuno_ar[$m].$ex_dose_ar[$m].$ex_lote_ar[$m].$ex_fabricante_ar[$m];
							
							if (!in_array($junta_procedimentos, $duplicados_procedimentos)) {
								$duplicados_procedimentos[$conta_duplicados_procedimentos] = $junta_procedimentos;
								$conta_duplicados_procedimentos++;
								if ($ex_dose_ar[$m] == 3){
									$dose3 = true;
								}
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
			$select_tb2 = "
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
			$run_mv = pg_query($cdb,$select_tb2);
			if (pg_num_rows($run_mv) > 0){
				while ($exmv = pg_fetch_array($run_mv)){
					$data_mais_vacina = 0;
					if (strlen(substr($exmv['dt_aplicacao'],0,10)) == 10){
						$data_mais_vacina = dataint(substr($exmv['dt_aplicacao'],0,10));
						
						//$junta_procedimentos = $data_mais_vacina.$exmv['co_cbo_2002'].$exmv['nu_cnes'].$exmv['co_imunobiologico'].$exmv['co_dose_imunobiologico'].$exmv['ds_lote'].$exmv['no_fabricante'];
						$junta_procedimentos = $exmv['co_cbo_2002'].$exmv['nu_cnes'].$exmv['co_imunobiologico'].$exmv['co_dose_imunobiologico'].$exmv['ds_lote'].$exmv['no_fabricante'];
						
						if (!in_array($junta_procedimentos, $duplicados_procedimentos)) {
							$duplicados_procedimentos[$conta_duplicados_procedimentos] = $junta_procedimentos;
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
						}
					}
				}
			}
		}
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		//   FAMILIA
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		
		


		// ===========================================================================================================
		$rel_dados_sub_tabela = "";
		
		if (count($array_procedimentos) > 0){
			usort($array_procedimentos, 'cmp');
			for ($i=0;$i<count($array_procedimentos);$i++){
				$conta_geral++;
				$zebra = "";
				if (($conta_geral%2) == 0){
					$zebra = "class=\"linha-zebrada\"";
				}
				$tbvacinacao = '';
				if ($array_procedimentos[$i]['tabela'] == 'tb_fat_vacinacao'){
					$tbvacinacao = '*';
				}
				$rel_dados_sub_tabela .= "
					<tr ".$zebra."> 
						<td height=\"18\" valign=\"top\" class=\"lista-dado1-centro\">".$conta_geral."</td>
						<td valign=\"top\" class=\"lista-dado1-centro\">".mcpf($rCPF)."</td>
						<td valign=\"top\" class=\"lista-dado1-centro\">".mcns($rCNS)."</td>
						<td valign=\"top\" class=\"lista-dado1-esquerdo\">".$result2['cidadao_nome']."</td>
						<td valign=\"top\" class=\"lista-dado1-centro\">".$idade."</td>
						<td valign=\"top\" class=\"lista-dado1-centro\">".$array_procedimentos[$i]['dose']."</td>
						<td valign=\"top\" class=\"lista-dado1-centro\">".$array_procedimentos[$i]['imsg']."<br>".$array_procedimentos[$i]['lote']." / ".$array_procedimentos[$i]['fabricante']."</td>
						<td valign=\"top\" class=\"lista-dado1-centro\">".dtshow($array_procedimentos[$i]['data'])." ".$tbvacinacao."</td>
						<td valign=\"top\" class=\"lista-dado1-centro\">".mcns($array_procedimentos[$i]['nu_cns'])."&nbsp;</td>
					</tr>
				";
				$texto = zesq($conta_geral,5).";".mcpf($rCPF).";".mcns($rCNS).";".str_replace("Ã","A",$result2['cidadao_nome']).";".$idade.";".$array_procedimentos[$i]['dose'].";[".$array_procedimentos[$i]['imsg']."] ".$array_procedimentos[$i]['imsg'].";".$array_procedimentos[$i]['lote'].";".$array_procedimentos[$i]['fabricante'].";".dtshow($array_procedimentos[$i]['data']).";".mcns($array_procedimentos[$i]['nu_cns']).";".$array_procedimentos[$i]['no_profissional'].";".$array_procedimentos[$i]['imnome'].";".$array_procedimentos[$i]['grupo'].";".$array_procedimentos[$i]['regant'].";".$array_procedimentos[$i]['tabela']."\r\n";
				fwrite($FT, $texto);
			}
		}
		fwrite($HTML, $rel_dados_sub_tabela);
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
		$link_pag = "<a href=\"rel_covid1.php?pini=".$pini."&num=".$num."&cdes=".$cdes."\"><img src=\"plugins/scsus/temas/".$_SESSION['tema']."/img/pagina.png\"></a>";
	}
}
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   Resultado final
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$show_desconto = "";
if ($conta_geral_desconto > 0){
	$show_desconto = " (descontar ".$conta_geral_desconto." vacinado(es) por conta de inconsistências)";
}
$total_geral = $conta_geral;
if ($paginacao > 0){
	$total_geral = $conta_gravacao;
}

// ===========================================================================================================
$rel_dados_final = "
</table>
";
fwrite($HTML, $rel_dados_final);
$rel_rodape = "
<table width=\"1088\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"rodape-tabela\">
  <!--DWLayoutTable-->
  <tr> 
    <td width=\"1088\" height=\"25\" valign=\"top\" class=\"rodape-texto\">
	".$sobre['nome']." | 
	".$sobre['versao']." | 
	".date('d/m/Y')." | 
	Total de ".$total_geral." vacinados | 
	Ordenado por ".$show_ordem." | ".$dbdb." |
	".$link_pag."
	</td>
  </tr>
</table>
";
fwrite($HTML, $rel_rodape);
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

?>
