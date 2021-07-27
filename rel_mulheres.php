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
if (file_exists("config/c_rel_m_".$_SESSION['key'].".php")){
	require_once("config/c_rel_m_".$_SESSION['key'].".php");
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
$file = "html/rel_mulheres_".$_SESSION['key'].".html";
if (file_exists($file)){unlink($file);}
$HTML = fopen($file,'w');
// -----------------------------------------------------------------------
$file = "csv/mulheres_T_".$_SESSION['key'].".csv";
if (file_exists($file)){unlink($file);}
$FT = fopen($file,'w');
$texto = "Seq;CPF;CNS;Nome;DtNascimento;Mae;Idade;MarcGestante;MarcHipertensa;MarcDiabetica;Indicador4;CNES;INE;MA\r\n";
fwrite($FT, $texto);
// -----------------------------------------------------------------------
$file = "csv/mulheres_P_".$_SESSION['key'].".csv";
if (file_exists($file)){unlink($file);}
$FP = fopen($file,'w');
$texto = "Seq;CPF;CNS;DtProced;Tabela;CNSProf;NomeProf;CNES;INE;CBO;Procedimento(A/S);ProfAlerta\r\n";
fwrite($FP, $texto);

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
	$dti = datasomadias(date('Ymd'),180,'-');
	$dtf = date('Ymd');
}
if ($ridade >= 0 && $ridade <= 9){
	switch ($ridade) {
		case 0:
			$idade_inicial = 25;
			$idade_final = 64;
			break;
		case 1:
			$idade_inicial = 25;
			$idade_final = 30;
			break;
		case 2:
			$idade_inicial = 31;
			$idade_final = 40;
			break;
		case 3:
			$idade_inicial = 41;
			$idade_final = 50;
			break;
		case 4:
			$idade_inicial = 51;
			$idade_final = 60;
			break;
		case 5:
			$idade_inicial = 61;
			$idade_final = 64;
			break;
		case 6:
			$idade_inicial = 25;
			$idade_final = 40;
			break;
		case 7:
			$idade_inicial = 40;
			$idade_final = 60;
			break;
		case 8:
			$idade_inicial = 50;
			$idade_final = 64;
			break;
		case 9:
			$idade_inicial = 25;
			$idade_final = 35;
			break;
	}
} else {
	$idade_inicial = $ridade;
	$idade_final = $ridade;
}
// -----------------------------------------------------------------------
$a_dti = (int) substr($dti,0,4);
$a_dtf = (int) substr($dtf,0,4);
$m_dtf = substr($dtf,4,2);
$d_dtf = substr($dtf,6,2);
$a_dtfx = $a_dtf - $idade_final - 1;
$a_dtix = $a_dtf - $idade_inicial;
$dti_nas = $a_dtfx.'-'.$m_dtf.'-'.$d_dtf;
$dtf_nas = $a_dtix.'-'.$m_dtf.'-'.$d_dtf;
// -----------------------------------------------------------------------
$conta_geral = $pini;
$conta_geral_desconto = $cdes;
$numerador_ind4 = $num;
$contator_gl_hipertenso = 0;
$contator_gl_gestante = 0;
$contator_gl_diabetico = 0;
$conta_gravacao = 0;
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
            <h1>Mulheres</h1>
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
CREATE TEMPORARY TABLE tmp_mulheres (
	sequencia serial PRIMARY KEY,
	cns varchar(15),
	cpf varchar(11),
	cns_alternativo varchar(15),
	cpf_alternativo varchar(11),
	data_nascimento bigint,
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
			nu_micro_area_c
		FROM 
			tb_cidadao 
		WHERE 
			no_sexo = 'FEMININO' AND
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
		text '# MULHER NÃO ENCONTRADA #' as no_cidadao,
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
				) AS t2
				WHERE
					ds_sexo = 'FEMININO'
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
			$cidadao_nome = "# MULHER NÃO ENCONTRADA #";
			$cidadao_mae = "# SEM NOME DA MAE #";
			if (strlen(trim($result1['no_cidadao'])) > 0){
				$cidadao_nome = trim($result1['no_cidadao']);
			}
			if (strlen(trim($result1['no_mae'])) > 0){
				$cidadao_mae = trim($result1['no_mae']);
			}
			$cidadao_nome = htmlspecialchars($cidadao_nome, ENT_QUOTES);
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
			INSERT INTO tmp_mulheres(
				cns,
				cpf,
				cns_alternativo,
				cpf_alternativo,
				data_nascimento,
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
	<td width=\"440\" height=\"32\" valign=\"top\" class=\"relatorio-titulo\">Relatório de Mulheres (de ".$idade_inicial." até ".$idade_final.")</td>
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
	tmp_mulheres
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
		$a_atual = $a_dtf - 3;
		$data_padrao = $dtf;
		if ($dt3anos == 'U'){
			$dt_3anos = $a_atual.$m_dtf.$d_dtf;
		}
		if ($dt3anos == 'P'){
			$a_atual = $a_dti - 3;
			$data_padrao = $dti;
			$dt_3anos = $a_atual.$m_dti.$d_dti;
		}
		if ($dt3anos == 'A'){
			$dt_3anos = $a_atual.substr($result2['data_nascimento'],4,2).substr($result2['data_nascimento'],6,2);
		}
		$idade = idadeint($result2['data_nascimento'],$data_padrao);
		if ($idade < 28){
			$descano = 0;
			if ($idade == 25){
				$descano = 0;
			}
			if ($idade == 26){
				$descano = 1;
			}
			if ($idade == 27){
				$descano = 2;
			}
			$comp_dt_an = substr($result2['data_nascimento'],4,4);
			if ($dt3anos == 'P'){
				$comp_dt_at = substr($dti,4,4);
			} else {
				$comp_dt_at = substr($dtf,4,4);
			}
			if ($comp_dt_an > $comp_dt_at){
				$descano++;
			}
			$a_atual = $a_dtf - $descano;
			$dt_3anos = $a_atual.substr($result2['data_nascimento'],4,2).substr($result2['data_nascimento'],6,2);
		}
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		//   PROCEDIMENTOS
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		$array_procedimentos = array();
		$cont_array_procedimentos = 0;
		$array_consultas = array();
		$cont_array_consultas = 0;
		$contro_cbo = "'2251%', '2252%', '2253%', '2231%', '2235%'";
		$procedimentos = "'ABPG010','0201020033'";
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		//   (BUSCA) tabela 1
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		$select_tb1 = "
			SELECT
				t6.co_dim_tempo,
				t6.nu_cbo,
				t6.nu_cnes,
				t6.nu_ine,
				t6.avaliado,
				t6.solicitado,
				t6.cns_prof,
				t6.no_profissional,
				text 'tb_fat_atd_ind_procedimentos' as tabela
			FROM
			(
				---------------------------------------------------------
				---------------------------------------------------------
				---------------------------------------------------------
				SELECT
					t7.*,
					tb_dim_profissional.nu_cns AS cns_prof,
					tb_dim_profissional.no_profissional
				FROM
				(
					SELECT
						t5.*,
						tb_dim_procedimento.co_proced AS solicitado
					FROM
					(
						SELECT
							t4.*,
							tb_dim_procedimento.co_proced AS avaliado
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
											CASE WHEN co_dim_cbo_1 = 1 THEN co_dim_cbo_2
											ELSE co_dim_cbo_1 END
											co_dim_cbo,
											CASE WHEN co_dim_unidade_saude_1 = 1 THEN co_dim_unidade_saude_2
											ELSE co_dim_unidade_saude_1 END
											co_dim_unidade_saude,
											CASE WHEN co_dim_equipe_1 = 1 THEN co_dim_equipe_2
											ELSE co_dim_equipe_1 END
											co_dim_equipe,
											co_dim_tempo,
											co_dim_procedimento_avaliado,
											co_dim_procedimento_solicitado,
											CASE WHEN co_dim_profissional_1 = 1 THEN co_dim_profissional_2
												ELSE co_dim_profissional_1 END
												co_dim_profissional
										FROM 
											tb_fat_atd_ind_procedimentos
										WHERE
											".$campo_busca." AND
											(co_dim_tempo >= ".$dt_3anos." AND co_dim_tempo <= ".$dtf.") AND
											(
												co_dim_procedimento_avaliado IN
												(
													SELECT 
														co_seq_dim_procedimento
													FROM 
														tb_dim_procedimento
													WHERE
														co_proced IN (".$procedimentos.")
												)
												OR
												co_dim_procedimento_solicitado IN
												(
													SELECT 
														co_seq_dim_procedimento
													FROM 
														tb_dim_procedimento
													WHERE
														co_proced IN (".$procedimentos.")
												)	
											)
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
							tb_dim_procedimento
						ON tb_dim_procedimento.co_seq_dim_procedimento = t4.co_dim_procedimento_avaliado
					) AS t5
					LEFT JOIN
						tb_dim_procedimento
					ON tb_dim_procedimento.co_seq_dim_procedimento = t5.co_dim_procedimento_solicitado
				) AS t7
				LEFT JOIN
					tb_dim_profissional
				ON tb_dim_profissional.co_seq_dim_profissional = t7.co_dim_profissional
				---------------------------------------------------------
				---------------------------------------------------------
				---------------------------------------------------------
			) AS t6
			WHERE
				nu_cbo LIKE ANY (array[".$contro_cbo."])
		";
		$run_st1 = pg_query($cdb,$select_tb1);
		if (pg_num_rows($run_st1) > 0){
			while ($ex1 = pg_fetch_array($run_st1)){
				$junta_exames = $ex1['co_dim_tempo'].$ex1['nu_cbo'].$ex1['nu_cnes'].$ex1['nu_ine'].$ex1['avaliado'].$ex1['solicitado'];
				if (!in_array($junta_exames, $duplicados_procedimentos)) {
					$duplicados_procedimentos[$conta_duplicados_procedimentos] = $junta_exames;
					$conta_duplicados_procedimentos++;
					$array_procedimentos[$cont_array_procedimentos]['data'] = $ex1['co_dim_tempo'];
					$array_procedimentos[$cont_array_procedimentos]['cbo'] = $ex1['nu_cbo'];
					$array_procedimentos[$cont_array_procedimentos]['cnes'] = $ex1['nu_cnes'];
					$array_procedimentos[$cont_array_procedimentos]['ine'] = $ex1['nu_ine'];
					$array_procedimentos[$cont_array_procedimentos]['cns_prof'] = $ex1['cns_prof'];
					$array_procedimentos[$cont_array_procedimentos]['no_profissional'] = $ex1['no_profissional'];
					$array_procedimentos[$cont_array_procedimentos]['procedimento1'] = $ex1['avaliado'];
					$array_procedimentos[$cont_array_procedimentos]['procedimento2'] = $ex1['solicitado'];
					$array_procedimentos[$cont_array_procedimentos]['tabela'] = $ex1['tabela'];
					$cont_array_procedimentos++;
				}
			}
		}
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		//   (BUSCA) tabela 2
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		$select_tb2 = "
			SELECT
				t6.co_dim_tempo,
				t6.nu_cbo,
				t6.nu_cnes,
				t6.nu_ine,
				t6.procedimento,
				t6.cns_prof,
				t6.no_profissional,
				text 'tb_fat_proced_atend_proced' as tabela
			FROM
			(
				---------------------------------------------------------
				---------------------------------------------------------
				---------------------------------------------------------
				SELECT
					t7.*,
					tb_dim_profissional.nu_cns AS cns_prof,
					tb_dim_profissional.no_profissional
				FROM
				(
					SELECT
						t4.*,
						tb_dim_procedimento.co_proced AS procedimento
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
										co_dim_cbo,
										co_dim_unidade_saude,
										co_dim_equipe,
										co_dim_tempo,
										co_dim_procedimento,
										co_dim_profissional
									FROM 
										tb_fat_proced_atend_proced
									WHERE
										".$campo_busca." AND
										(co_dim_tempo >= ".$dt_3anos." AND co_dim_tempo <= ".$dtf.") AND
										(
											co_dim_procedimento IN
											(
												SELECT 
													co_seq_dim_procedimento
												FROM 
													tb_dim_procedimento
												WHERE
													co_proced IN (".$procedimentos.")
											)	
										)
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
						tb_dim_procedimento
					ON tb_dim_procedimento.co_seq_dim_procedimento = t4.co_dim_procedimento
				) AS t7
				LEFT JOIN
					tb_dim_profissional
				ON tb_dim_profissional.co_seq_dim_profissional = t7.co_dim_profissional
				---------------------------------------------------------
				---------------------------------------------------------
				---------------------------------------------------------
			) AS t6
			WHERE
				nu_cbo LIKE ANY (array[".$contro_cbo."])
		";
		$run_st2 = pg_query($cdb,$select_tb2);
		if (pg_num_rows($run_st2) > 0){
			while ($ex2 = pg_fetch_array($run_st2)){
				$junta_exames = $ex2['co_dim_tempo'].$ex2['nu_cbo'].$ex2['nu_cnes'].$ex2['nu_ine'].$ex2['procedimento'].'-';
				if (!in_array($junta_exames, $duplicados_procedimentos)) {
					$duplicados_procedimentos[$conta_duplicados_procedimentos] = $junta_exames;
					$conta_duplicados_procedimentos++;
					$array_procedimentos[$cont_array_procedimentos]['data'] = $ex2['co_dim_tempo'];
					$array_procedimentos[$cont_array_procedimentos]['cbo'] = $ex2['nu_cbo'];
					$array_procedimentos[$cont_array_procedimentos]['cnes'] = $ex2['nu_cnes'];
					$array_procedimentos[$cont_array_procedimentos]['ine'] = $ex2['nu_ine'];
					$array_procedimentos[$cont_array_procedimentos]['procedimento1'] = $ex2['procedimento'];
					$array_procedimentos[$cont_array_procedimentos]['procedimento2'] = '-';
					$array_procedimentos[$cont_array_procedimentos]['tabela'] = $ex2['tabela'];
					$array_procedimentos[$cont_array_procedimentos]['cns_prof'] = $ex2['cns_prof'];
					$array_procedimentos[$cont_array_procedimentos]['no_profissional'] = $ex2['no_profissional'];
					$cont_array_procedimentos++;
				}
			}
		}
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		//   CONSULTAS
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		$consultaminima = false;
		if (count($array_procedimentos) > 0){
			for($c=0;$c<count($array_procedimentos);$c++){
				$consulta = "
					SELECT 
						ds_filtro_cids,
						ds_filtro_ciaps
					FROM 
						tb_fat_atendimento_individual 
					WHERE 
						".$campo_busca." AND 
						co_dim_tempo = ".$array_procedimentos[$c]['data']."
					LIMIT 1
				";
				$run_cons = pg_query($cdb,$consulta);
				if (pg_num_rows($run_cons) > 0){
					$consultaminima = true;
					$array_consultas[$cont_array_consultas]['data'] = $array_procedimentos[$c]['data'];
					$array_consultas[$cont_array_consultas]['cids'] = str_replace("|"," ",pg_fetch_result($run_cons,0,'ds_filtro_cids'));
					$array_consultas[$cont_array_consultas]['ciaps'] = str_replace("|"," ",pg_fetch_result($run_cons,0,'ds_filtro_ciaps'));
					$cont_array_consultas++;
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
			$contator_gl_gestante++;
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
			$inconsistencias .= "Mulher sem cadastro familiar.<br>";
		}
		if ($result2['cind_sexo'] != 'FEMININO'){
			$inconsistencias .= "Cadastro está como do sexo Masculino.<br>";
		}
		if (substr($result2['cidadao_nome'],0,1) == '#'){
			$inconsistencias .= "Mulher não localizada em tb_cidadao.<br>";
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
		if (count($array_procedimentos) > 0 && !$consultaminima){
			$inconsistencias .= "Não teve consulta, apenas procedimento<br>";
		}
		if (strlen($inconsistencias) > 0){
			$estilo_inco = "lista-status-Vermelho";
		}
		$exame_citopatologico = "NÃO";
		$estilo_excito = "indicador-c1-NAO";
		$estilo_excito_indicador = "indicador-titulo-Vermelho-B";
		if (count($array_procedimentos) > 0){
			$exame_citopatologico = "SIM";
			$estilo_excito = "indicador-c1-SIM";
			$estilo_excito_indicador = "indicador-titulo-Verde-B";
			$numerador_ind4++;
			if (false !== $key = array_search($result2['ine'], array_column($total_equipe, 'ine'))) {
				$total_equipe[$key]['num'] = $total_equipe[$key]['num'] + 1;
			}
			if (false !== $key = array_search($result2['cnes'], array_column($total_unidade, 'cnes'))) {
				$total_unidade[$key]['num'] = $total_unidade[$key]['num'] + 1;
			}
		}
		/*
		if (count($array_procedimentos) > 0 && $consultaminima){
			$exame_citopatologico = "SIM";
			$estilo_excito = "indicador-c1-SIM";
			$estilo_excito_indicador = "indicador-titulo-Verde-B";
			$numerador_ind4++;
			if (false !== $key = array_search($result2['ine'], array_column($total_equipe, 'ine'))) {
				$total_equipe[$key]['num'] = $total_equipe[$key]['num'] + 1;
			}
			if (false !== $key = array_search($result2['cnes'], array_column($total_unidade, 'cnes'))) {
				$total_unidade[$key]['num'] = $total_unidade[$key]['num'] + 1;
			}
		}
		*/
		// ===========================================================================================================
		$rel_dados_unitario = "
		  <tr> 
			<td height=\"18\"></td>
			<td valign=\"top\" class=\"lista-dado1-centro-BB\">".zesq($conta_geral,5)."</td>
			<td valign=\"top\" class=\"lista-dado1-centro-B\">".mcpf($rCPF)."</td>
			<td colspan=\"2\" valign=\"top\" class=\"lista-dado1-centro-B\">".mcns($rCNS)."</td>
			<td colspan=\"5\" valign=\"top\" class=\"lista-dado1-esquerdo-B\">".$result2['cidadao_nome']."</td>
			<td valign=\"top\" class=\"lista-dado1-centro-B\">".dtshow($result2['data_nascimento'])."</td>
			<td colspan=\"2\" rowspan=\"5\" valign=\"top\" class=\"".$estilo_inco."\"><p>
			".$inconsistencias."
			</p></td>
		  </tr>
		  <tr> 
			<td height=\"19\"></td>
			<td valign=\"top\" class=\"lista-dado1-centro-B\"><!--DWLayoutEmptyCell-->&nbsp;</td>
			<td colspan=\"2\" valign=\"top\" class=\"lista-sub-dados-BB\">Nome da m&atilde;e</td>
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
			<td height=\"18\"></td>
			<td></td>
			<td colspan=\"2\" valign=\"top\" class=\"lista-sub-dados-BB\">An&aacute;lise do proced. desde</td>
			<td colspan=\"2\" valign=\"top\" class=\"lista-dado2-centro-B\">".dtshow($dt_3anos)."</td>
			<td colspan=\"2\" valign=\"top\" class=\"lista-sub-dados-B\">Declarada Diab&eacute;tica</td>
			<td width=\"69\" valign=\"top\" class=\"lista-dado2-centro-B\">".$marcado_diabetico."</td>
			<td width=\"84\" valign=\"top\" class=\"lista-sub-dados-B\">".$show_gp_nm2_inverso."</td>
			<td valign=\"top\" class=\"lista-dado2-centro-B\">".$show_gp_id2_inverso."</td>
		  </tr>
		  <tr> 
			<td height=\"19\"></td>
			<td></td>
			<td colspan=\"2\" valign=\"top\" class=\"lista-sub-dados-BB\">.</td>
			<td colspan=\"2\" valign=\"top\" class=\"lista-dado2-centro-B\">.</td>
			<td colspan=\"2\" valign=\"top\" class=\"lista-sub-dados-B\">Declarada Hipertensa</td>
			<td valign=\"top\" class=\"lista-dado2-centro-B\">".$marcado_hipertenso."</td>
			<td valign=\"top\" class=\"lista-sub-dados-B\">Idade</td>
			<td valign=\"top\" class=\"lista-dado2-centro-B\">".$idade."</td>
		  </tr>
		  <tr> 
			<td height=\"16\"></td>
			<td></td>
			<td colspan=\"2\" rowspan=\"2\" valign=\"top\" class=\"".$estilo_excito_indicador."\">Indicador 4 [ ".$exame_citopatologico." ]</td>
			<td colspan=\"3\" rowspan=\"2\" valign=\"top\" class=\"indicador-c1-B\">Exame citopatol&oacute;gico (25 - 64 anos)</td>
			<td colspan=\"4\" rowspan=\"2\" valign=\"top\" class=\"indicador-c1-centro-X-B\">.</td>
			<td colspan=\"2\" valign=\"top\" class=\"indicador-c1\">.</td>
		  </tr>
		  <tr> 
			<td height=\"16\"></td>
			<td></td>
			<td colspan=\"2\" valign=\"top\" class=\"indicador-cx-B\">.</td>
		  </tr>
		  ";
		fwrite($HTML, $rel_dados_unitario);
		$texto = zesq($conta_geral,5).";".mcpf($rCPF).";".mcns($rCNS).";".str_replace("Ã","A",$result2['cidadao_nome']).";".dtshow($result2['data_nascimento']).";".str_replace("Ã","A",$result2['cidadao_mae']).";".$idade.";".str_replace("Ã","A",$marcado_gestante).";".str_replace("Ã","A",$marcado_hipertenso).";".str_replace("Ã","A",$marcado_diabetico).";".str_replace("Ã","A",$exame_citopatologico).";".$result2['cnes'].";".$result2['ine'].";".$ma_familiar."/".$result2['cind_micro_area']."\r\n";
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
					  <td width=\"169\" valign=\"top\" class=\"procedimentos-cabeca\">Procedimento (A/S)</td>
					  <td width=\"75\" valign=\"top\" class=\"procedimentos-cabeca\">Data</td>
					  <td width=\"213\" valign=\"top\" class=\"procedimentos-cabeca\">CNS Prof.</td>
					  <td width=\"43\" valign=\"top\" class=\"procedimentos-cabeca\">CBO</td>
					  <td width=\"78\" valign=\"top\" class=\"procedimentos-cabeca\">INE</td>
					  <td width=\"56\" valign=\"top\" class=\"procedimentos-cabeca\">CNES</td>
					  <td width=\"62\" valign=\"top\" class=\"procedimentos-cabeca\">Quant.</td>
					</tr>
			";
			$soma_procedimentos = 0;
			for ($i=0;$i<count($array_procedimentos);$i++){
				$soma_procedimentos++;

				$profissionalOK = 'procedimentos-dado';
				$profissionalAlerta = 'NAO';
				if (strlen($array_procedimentos[$i]['ine']) == 10){
					if (file_exists('xml/cnes.xml')){
						if (!cnes('xml/cnes.xml',$array_procedimentos[$i]['cns_prof'],$array_procedimentos[$i]['cbo'],$array_procedimentos[$i]['ine'],'I')){
							$profissionalOK = 'procedimentos-dado-amarelo';
							$profissionalAlerta = 'SIM';
						}
					}
				}
				$texto = $soma_procedimentos.";".mcpf($rCPF).";".mcns($rCNS).";".dtshow($array_procedimentos[$i]['data']).";".$array_procedimentos[$i]['tabela'].";".$array_procedimentos[$i]['cns_prof'].";".$array_procedimentos[$i]['no_profissional'].";".$array_procedimentos[$i]['cnes'].";".$array_procedimentos[$i]['ine'].";".$array_procedimentos[$i]['cbo'].";".$array_procedimentos[$i]['procedimento1']."/".$array_procedimentos[$i]['procedimento2'].";".$profissionalAlerta."\r\n";
				fwrite($FP, $texto);
				$rel_dados_sub_tabela .= "
						<tr> 
						  <td height=\"18\" valign=\"top\" class=\"procedimentos-dado\">".$soma_procedimentos."</td>
						  <td valign=\"top\" class=\"procedimentos-dado\">".$array_procedimentos[$i]['procedimento1']." / ".$array_procedimentos[$i]['procedimento2']."</td>
						  <td valign=\"top\" class=\"procedimentos-dado\">".dtshow($array_procedimentos[$i]['data'])."</td>
						  <td valign=\"top\" class=\"".$profissionalOK."\">".$array_procedimentos[$i]['cns_prof']."</td>
						  <td valign=\"top\" class=\"procedimentos-dado\">".$array_procedimentos[$i]['cbo']."</td>
						  <td valign=\"top\" class=\"procedimentos-dado\">".$array_procedimentos[$i]['ine']."</td>
						  <td valign=\"top\" class=\"procedimentos-dado\">".$array_procedimentos[$i]['cnes']."</td>
						  <td valign=\"top\" class=\"procedimentos-dado\">1</td>
						</tr>
				";
			}
			$rel_dados_sub_tabela .= "
					<tr> 
					  <td height=\"18\"></td>
					  <td></td>
					  <td></td>
					  <td></td>
					  <td></td>
					  <td></td>
					  <td></td>
					  <td valign=\"top\" class=\"procedimentos-dado\">".$soma_procedimentos."</td>
					</tr>
				  </table>
				  </td>
			  </tr>
			";
			fwrite($HTML, $rel_dados_sub_tabela);
		}
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		//   CONSULTAS (MONTA RELATORIO)
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		if (count($array_consultas) > 0){
			usort($array_consultas, 'cmp');
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
					  <td width=\"169\" valign=\"top\" class=\"procedimentos-cabeca\">CID</td>
					  <td width=\"75\" valign=\"top\" class=\"procedimentos-cabeca\">Data</td>
					  <td width=\"213\" valign=\"top\" class=\"procedimentos-cabeca\">CIAP</td>
					  <td width=\"43\" valign=\"top\" class=\"procedimentos-cabeca\">.</td>
					  <td width=\"78\" valign=\"top\" class=\"procedimentos-cabeca\">.</td>
					  <td width=\"56\" valign=\"top\" class=\"procedimentos-cabeca\">.</td>
					  <td width=\"62\" valign=\"top\" class=\"procedimentos-cabeca\">.</td>
					</tr>
			";
			$soma_consultas = 0;
			for ($i=0;$i<count($array_consultas);$i++){
				$soma_consultas++;
				$rel_dados_sub_tabela .= "
						<tr> 
						  <td height=\"18\" valign=\"top\" class=\"procedimentos-dado\">".$soma_consultas."</td>
						  <td valign=\"top\" class=\"procedimentos-dado\">".$array_consultas[$i]['cids']."</td>
						  <td valign=\"top\" class=\"procedimentos-dado\">".dtshow($array_consultas[$i]['data'])."</td>
						  <td valign=\"top\" class=\"procedimentos-dado\">".$array_consultas[$i]['ciaps']."</td>
						  <td valign=\"top\" class=\"procedimentos-dado\">.</td>
						  <td valign=\"top\" class=\"procedimentos-dado\">.</td>
						  <td valign=\"top\" class=\"procedimentos-dado\">.</td>
						  <td valign=\"top\" class=\"procedimentos-dado\">.</td>
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
	$num = $numerador_ind4;
	$cdes = $conta_geral_desconto;
	if ($nm_sql_2 > 0){
		$link_pag = "<a href=\"rel_mulheres.php?pini=".$pini."&num=".$num."&cdes=".$cdes."\"><img src=\"plugins/scsus/temas/".$_SESSION['tema']."/img/pagina.png\"></a>";
	}
}
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   Resultado final
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$show_desconto = "";
if ($conta_geral_desconto > 0){
	$show_desconto = " (descontar ".$conta_geral_desconto." mulher(es) por conta de inconsistências)";
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
	Total de ".$total_geral." mulheres | 
	Ordenado por ".$show_ordem." | ".$dbdb." |
	".$link_pag."
	</td>
  </tr>
</table>
";
fwrite($HTML, $rel_rodape);
if (($paginacao <= 0 && $ridade <= 0) || $nm_sql_2 == 0){
	// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	//   GRAVA RESUMO
	// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	$denominador_final = $conta_geral - $conta_geral_desconto;
	$file = "resumo/r_ind4_".$_SESSION['key'].".php";
	if (file_exists($file)){unlink($file);}
	$FIN = fopen($file,'w');
	$porc_ind = 0;
	if ($denominador_final > 0){
		$porc_ind = (100 * $numerador_ind4) / $denominador_final;
	}
	$porc_ind_est = 0;
	$deno_esti = "";
	if ($des > 0){
		$porc_ind_est = (100 * $numerador_ind4) / $des;
		$deno_esti = "(Estimado: ".$des.")";
	}
	$txind = "<?php
	\$den_ind_4 = ".$denominador_final.";
	\$den_est_4 = ".$des.";
	\$num_4 = ".$numerador_ind4.";
	\$num_prc_4 = ".$porc_ind.";
	\$num_prc_est_4 = ".$porc_ind_est.";
	\$quadri_4 = \"".qdata($dti)."\";
	\$versao_4 = \"".$sobre['versao']."\";
	\$banco_4 = \"".$dbdb."\";
	\$datahora_4 = \"".date('d/m/Y H:i:s')."\";
	\$dti_4 = ".$dti.";
	\$dtf_4 = ".$dtf.";
	\$incons_4 = ".$conta_geral_desconto.";
	\$c_gest_4 = ".$contator_gl_gestante.";
	\$c_diabe_4 = ".$contator_gl_diabetico.";
	\$c_hiper_4 = ".$contator_gl_hipertenso.";
	";
	for ($e=0;$e<count($total_equipe);$e++){
		$txind .= "\r\$equipe_4[".$e."]['ine'] = \"".$total_equipe[$e]['ine']."\"; \$equipe_4[".$e."]['nome'] = \"".$total_equipe[$e]['nome']."\"; \$equipe_4[".$e."]['den'] = \"".$total_equipe[$e]['den']."\"; \$equipe_4[".$e."]['num'] = \"".$total_equipe[$e]['num']."\";";
	}
	for ($e=0;$e<count($total_unidade);$e++){
		$txind .= "\r\$unidade_4[".$e."]['cnes'] = \"".$total_unidade[$e]['cnes']."\"; \$unidade_4[".$e."]['nome'] = \"".$total_unidade[$e]['nome']."\"; \$unidade_4[".$e."]['den'] = \"".$total_unidade[$e]['den']."\"; \$unidade_4[".$e."]['num'] = \"".$total_unidade[$e]['num']."\";";
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
		<td width=\"260\" valign=\"top\" class=\"resumo-sub-titulo\">Indicador 4</td>
		<td width=\"120\">&nbsp;</td>
	  </tr>
	  <tr> 
		<td height=\"30\" valign=\"top\" class=\"resumo-numerador\">Numerador&nbsp;&nbsp;</td>
		<td valign=\"top\" class=\"resumo-v-numerador\">".$numerador_ind4." [ ".ceil($porc_ind)."% ]</td>
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
fclose($FP);

?>
