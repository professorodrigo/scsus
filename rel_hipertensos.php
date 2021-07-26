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
if (file_exists("config/c_rel_h_".$_SESSION['key'].".php")){
	require_once("config/c_rel_h_".$_SESSION['key'].".php");
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
$file = "html/rel_hipertensos_".$_SESSION['key'].".html";
if (file_exists($file)){unlink($file);}
$HTML = fopen($file,'w');
// -----------------------------------------------------------------------
$file = "csv/hipertensos_T_".$_SESSION['key'].".csv";
if (file_exists($file)){unlink($file);}
$FT = fopen($file,'w');
$texto = "Seq;CPF;CNS;Nome;DtNascimento;Mae;Idade;MarcGestante;MarcHipertenso;MarcDiabetico;Indicador6;Sexo;CNES;INE;MA;NumConsS1;NumConsS2;NumProcS1;NumProcS2\r\n";
fwrite($FT, $texto);
// -----------------------------------------------------------------------
$file = "csv/hipertensos_C_".$_SESSION['key'].".csv";
if (file_exists($file)){unlink($file);}
$FC = fopen($file,'w');
$texto = "Seq;CPF;CNS;DtProced;CNES;INE;CBO;Semestre;CID;CIAP;CNSProf;NomeProf;ProfAlerta\r\n";
fwrite($FC, $texto);
// -----------------------------------------------------------------------
$file = "csv/hipertensos_P_".$_SESSION['key'].".csv";
if (file_exists($file)){unlink($file);}
$FP = fopen($file,'w');
$texto = "Seq;CPF;CNS;DtProced;DtCons;Tabela;CNSProf;NomeProf;CNES;INE;CBO;Semestre;Procedimento(A/S);ProfAlerta\r\n";
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
$per_12m = datasomameses($dtf,12,'-');
if ($m12 == 1){
	$per_12m = datasomameses($dti,12,'-');
}
// -----------------------------------------------------------------------
$conta_geral = $pini;
$conta_geral_desconto = $cdes;
$numerador_ind6 = $num;
$contator_gl_diabetico = 0;
$contator_gl_gestante = 0;
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
            <h1>Hipertensos</h1>
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
CREATE TEMPORARY TABLE tmp_hipertensos (
	sequencia serial PRIMARY KEY,
	cns varchar(15),
	cpf varchar(11),
	cns_alternativo varchar(15),
	cpf_alternativo varchar(11),
	data_nascimento bigint,
	g_co_dim_tempo bigint,
	cidadao_ativo int,
	data_falecimento bigint,
	cidadao_nome varchar(500),
	cidadao_mae varchar(500),
	cidadao_faleceu int,
	cidadao_sexo varchar(24),
	cind_gestante int,
	cind_hipertenso int,
	cind_diabetico int,
	cind_sexo varchar(24),
	cind_micro_area varchar(5),
	cns_resp varchar(15),
	cpf_resp varchar(11),
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
	SELECT DISTINCT ON (t3.nu_cns, t3.nu_cpf_cidadao)
		t3.nu_cns,
		t3.nu_cpf_cidadao,
		t3.co_dim_tempo,
		t3.dt_nascimento,
		t3.nu_ine,
		t3.no_equipe,
		t3.nu_cnes,
		t3.no_unidade_saude,
		tb_dim_cbo.nu_cbo,
		tb_dim_cbo.no_cbo
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
				SELECT 
					CASE WHEN nu_cns IS NULL THEN '0'
						ELSE TRIM(FROM nu_cns) END
						nu_cns,
					CASE WHEN nu_cpf_cidadao IS NULL THEN '0'
						ELSE TRIM(FROM nu_cpf_cidadao) END
						nu_cpf_cidadao,
					co_dim_tempo, 
					dt_nascimento,
					CASE WHEN co_dim_cbo_1 = 1 THEN co_dim_cbo_2
						ELSE co_dim_cbo_1 END
						co_dim_cbo,
					CASE WHEN co_dim_unidade_saude_1 = 1 THEN co_dim_unidade_saude_2
						ELSE co_dim_unidade_saude_1 END
						co_dim_unidade_saude,
					CASE WHEN co_dim_equipe_1 = 1 THEN co_dim_equipe_2
						ELSE co_dim_equipe_1 END
						co_dim_equipe
				FROM
					tb_fat_atendimento_individual
				WHERE
					(co_dim_tempo >= ".$per_12m." AND co_dim_tempo <= ".$dtf.") AND
					(
						ds_filtro_ciaps LIKE ANY (
							array[
								'%|K86|%',
								'%|K87|%',
								'%|W81|%',
								'%|ABP005|%'
							]
						) OR
						ds_filtro_cids LIKE ANY (
							array[
								'%|I10|%',
								'%|I11|%',
								'%|I110|%',
								'%|I119|%',
								'%|I12|%',
								'%|I120|%',
								'%|I129|%',
								'%|I13|%',
								'%|I130|%',
								'%|I131|%',
								'%|I132|%',
								'%|I139|%',
								'%|I15|%',
								'%|I150|%',
								'%|I151|%',
								'%|I152|%',
								'%|I158|%',
								'%|I159|%',
								'%|I270|%',
								'%|I272|%',
								'%|O10|%',
								'%|O100|%',
								'%|O101|%',
								'%|O102|%',
								'%|O103|%',
								'%|O104|%',
								'%|O109|%'
							]
						)
					)
				ORDER BY co_dim_tempo
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
		tb_dim_cbo
	ON tb_dim_cbo.co_seq_dim_cbo = t3.co_dim_cbo
	ORDER BY t3.nu_cns, t3.nu_cpf_cidadao, t3.co_dim_tempo DESC
) AS t4
WHERE
	nu_cbo LIKE ANY (array['2251%','2252%','2253%','2231%','2235%'])
".$esq_pag;
	
$run_s1 = pg_query($cdb,$SQL_1);
$nm_sql_1 = 0;
$nm_sql_1 = pg_num_rows($run_s1);
if ($nm_sql_1 > 0){
	while ($result1 = pg_fetch_array($run_s1)){
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		//   CNS e CPF (busca)
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		$CNS = '0';
		$CPF = '0';
		$CNSA = '0';
		$CPFA = '0';
		$campo_busca = "";
		$campo_ordem = "";
		$busca_cns = false;
		$busca_cpf = false;
		$busca = false;
		if (strlen(trim($result1['nu_cns'])) == 15){
			$busca_cns = true;
			$CNS = trim($result1['nu_cns']);
		}
		if (strlen(trim($result1['nu_cpf_cidadao'])) == 11){
			$busca_cpf = true;
			$CPF = trim($result1['nu_cpf_cidadao']);
		}
		if ($busca_cns && $busca_cpf){
			$campo_busca = "(nu_cns = '".$CNS."' OR nu_cpf = '".$CPF."')";
			$campo_ordem = "nu_cns, nu_cpf";
			$busca = true;
		} else {
			if ($busca_cns){
				$campo_busca = "nu_cns = '".$CNS."'";
				$campo_ordem = "nu_cpf DESC";
				$busca = true;
			}
			if ($busca_cpf){
				$campo_busca = "nu_cpf = '".$CPF."'";
				$campo_ordem = "nu_cns DESC";
				$busca = true;
			}
		}
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		//   Pega os dados
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		if ($busca){
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			//   TB_CIDADAO
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			$cidadaos = "
			SELECT
				st_ativo, 
				CASE WHEN nu_cns IS NULL THEN '0'
					ELSE TRIM(FROM nu_cns) END
					nu_cns,
				CASE WHEN nu_cpf IS NULL THEN '0'
					ELSE TRIM(FROM nu_cpf) END
					nu_cpf,
				no_cidadao, 
				dt_nascimento, 
				no_mae, 
				dt_obito, 
				st_faleceu, 
				nu_micro_area, 
				st_fora_area, 
				CASE WHEN nu_cns_responsavel IS NULL THEN '0'
					ELSE TRIM(FROM nu_cns_responsavel) END
					nu_cns_responsavel,
				CASE WHEN nu_cpf_responsavel IS NULL THEN '0'
					ELSE TRIM(FROM nu_cpf_responsavel) END
					nu_cpf_responsavel,
				no_sexo
			FROM
				tb_cidadao
			WHERE
				st_ativo = 1 AND 
				".$campo_busca."
			ORDER BY
				".$campo_ordem."
			LIMIT 1
			";
			$cidadao_ativo = 1;
			$cidadao_cpf = '0';
			$cidadao_cns = '0';
			$cidadao_nome = "# HIPERTENSO NÃO ENCONTRADO #";
			$cidadao_mae = "# SEM NOME DA MAE #";
			$cidadao_nascimento = $result1['dt_nascimento'];
			$cidadao_obito = NULL;
			$cidadao_faleceu = 0;
			$cidadao_cns_res = '0';
			$cidadao_cpf_res = '0';
			$cidadao_ma = '00';
			$cidadao_sexo = "FEMININO";
			$num_cidadaos = 0;
			$run_s2 = pg_query($cdb,$cidadaos);
			$num_cidadaos = pg_num_rows($run_s2);
			if ($num_cidadaos > 0){
				$cidadao_ativo = pg_fetch_result($run_s2,0,'st_ativo');
				$cidadao_cpf = trim(pg_fetch_result($run_s2,0,'nu_cpf'));
				$cidadao_cns = trim(pg_fetch_result($run_s2,0,'nu_cns'));
				$cidadao_nome = trim(pg_fetch_result($run_s2,0,'no_cidadao'));
				if (strlen(trim(pg_fetch_result($run_s2,0,'no_mae'))) > 0){
					$cidadao_mae = trim(pg_fetch_result($run_s2,0,'no_mae'));
				}
				$cidadao_nascimento = pg_fetch_result($run_s2,0,'dt_nascimento');
				$cidadao_obito = pg_fetch_result($run_s2,0,'dt_obito');
				$cidadao_faleceu = pg_fetch_result($run_s2,0,'st_faleceu');
				$cidadao_cns_res = trim(pg_fetch_result($run_s2,0,'nu_cns_responsavel'));
				$cidadao_cpf_res = trim(pg_fetch_result($run_s2,0,'nu_cpf_responsavel'));
				$cidadao_ma = pg_fetch_result($run_s2,0,'nu_micro_area');
				if (strlen($cidadao_ma) == 0 || $cidadao_ma == '0' || $cidadao_ma == NULL){
					$cidadao_ma = '00';
				}
				if (strlen($cidadao_ma) == 1){
					$cidadao_ma = '0'.$cidadao_ma;
				}
				$cidadao_sexo = pg_fetch_result($run_s2,0,'no_sexo');
				if (!$busca_cns){
					if (strlen($cidadao_cns) == 15){
						$CNS = $cidadao_cns;
						$busca_cns = true;
					}
				}
				if (!$busca_cpf){
					if (strlen($cidadao_cpf) == 11){
						$CPF = $cidadao_cpf;
						$busca_cpf = true;
					}
				}
			}
			if ($busca_cns && $busca_cpf){
				$campo_busca = "(nu_cns = '".$CNS."' OR nu_cpf_cidadao = '".$CPF."')";
			} else {
				if ($busca_cns){
					$campo_busca = "nu_cns = '".$CNS."'";
				}
				if ($busca_cpf){
					$campo_busca = "nu_cpf_cidadao = '".$CPF."'";
				}
			}
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			//   TB_FAT_CAD_INDIVIDUAL
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			$cindividual = "
				SELECT
					TRIM(FROM t3.nu_cns) as nu_cns,
					TRIM(FROM t3.nu_cpf_cidadao) as nu_cpf_cidadao,
					t3.dt_obito,
					t3.nu_micro_area,
					t3.nu_cns_responsavel,
					t3.nu_cpf_responsavel,
					t3.st_ficha_inativa,
					t3.nu_ine,
					t3.no_equipe,
					t3.nu_cnes,
					t3.no_unidade_saude,
					t3.st_gestante,
					t3.st_hipertensao_arterial,
					t3.st_diabete,
					upper(tb_dim_sexo.ds_sexo) AS ds_sexo,
					int '0' as teste1,
					text '0' as teste2
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
							SELECT
								CASE WHEN nu_cns IS NULL THEN '0'
									ELSE nu_cns END
									nu_cns,
								CASE WHEN nu_cpf_cidadao IS NULL THEN '0'
									ELSE nu_cpf_cidadao END
									nu_cpf_cidadao,
								co_dim_unidade_saude,
								co_dim_equipe,
								co_dim_sexo,
								dt_obito,
								CASE WHEN nu_cns_responsavel IS NULL THEN '0'
									ELSE TRIM(FROM nu_cns_responsavel) END
									nu_cns_responsavel,
								CASE WHEN nu_cpf_responsavel IS NULL THEN '0'
									ELSE TRIM(FROM nu_cpf_responsavel) END
									nu_cpf_responsavel,
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
							WHERE 
								".$campo_busca."
							ORDER BY co_dim_tempo DESC
							LIMIT 1
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
			";
			$cind_cpf = '0';
			$cind_cns = '0';
			$cind_gestante = 0;
			$cind_hipertenso = 0;
			$cind_diabetico = 0;
			$cind_obito = NULL;
			$cind_cns_res = '0';
			$cind_cpf_res = '0';
			$cind_micro_area = '00';
			$cind_inativo = 0;
			$cnes = "0000000";
			$nome_unidade = "SEM UNIDADE SAUDE";
			$ine = "0000000000";
			$nome_equipe = "SEM EQUIPE";
			$nome_sexo = "FEMININO";
			$num_cind = 0;
			$run_s3 = pg_query($cdb,$cindividual);
			$num_cind = pg_num_rows($run_s3);
			if ($num_cind > 0){
				$cind_cpf = trim(pg_fetch_result($run_s3,0,'nu_cpf_cidadao'));
				$cind_cns = trim(pg_fetch_result($run_s3,0,'nu_cns'));
				$cind_gestante = pg_fetch_result($run_s3,0,'st_gestante');
				$cind_hipertenso = pg_fetch_result($run_s3,0,'st_hipertensao_arterial');
				$cind_diabetico = pg_fetch_result($run_s3,0,'st_diabete');
				$cind_obito = pg_fetch_result($run_s3,0,'dt_obito');
				$cind_cns_res = trim(pg_fetch_result($run_s3,0,'nu_cns_responsavel'));
				$cind_cpf_res =trim( pg_fetch_result($run_s3,0,'nu_cpf_responsavel'));
				$cind_micro_area = pg_fetch_result($run_s3,0,'nu_micro_area');

				if (strlen($cind_micro_area) == 0 || $cind_micro_area == '0' || $cind_micro_area == NULL){
					$cind_micro_area = '00';
				}
				if (strlen($cind_micro_area) == 1){
					$cind_micro_area = '0'.$cind_micro_area;
				}
				if ($cind_micro_area == '00' && $cidadao_ma != '00'){
					$cind_micro_area = $cidadao_ma;
				}
				$cind_inativo = pg_fetch_result($run_s3,0,'st_ficha_inativa');
				$cnes = pg_fetch_result($run_s3,0,'nu_cnes');
				$nome_unidade = pg_fetch_result($run_s3,0,'no_unidade_saude');
				$ine = pg_fetch_result($run_s3,0,'nu_ine');
				$nome_equipe = pg_fetch_result($run_s3,0,'no_equipe');
				$nome_sexo = pg_fetch_result($run_s3,0,'ds_sexo');
				if (!$busca_cns){
					if (strlen($cind_cns) == 15){
						$CNS = trim($cind_cns);
						$busca_cns = true;
					}
				}
				if (!$busca_cpf){
					if (strlen($cind_cpf) == 11){
						$CPF = $cind_cpf;
						$busca_cpf = true;
					}
				}
			}
			if (strlen($cind_cns_res) < 15){
				$cind_cns_res = $cidadao_cns_res;
			}
			if (strlen($cind_cpf_res) < 11){
				$cind_cpf_res = $cidadao_cpf_res;
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
						no_cidadao = '".$cidadao_nome."' AND 
						dt_nascimento = '".$cidadao_nascimento."' AND 
						no_mae = '".$cidadao_mae."'
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
				$data_nascimento = dataint($result1['dt_nascimento']); // ou dataint($cidadao_nascimento)
				$data_falecimento = "0";
				if (strlen(trim($cidadao_obito)) > 5){
					$data_falecimento = dataint($cidadao_obito);
				} else {
					if (strlen(trim($cind_obito)) > 5){
						$data_falecimento = dataint($cind_obito);
					}
				}
				if ($cind_micro_area == '-' || $cind_micro_area == '--' || $cind_micro_area == '0-'){
					$cind_micro_area = '00';
				}
				if ($ine == '-'){
					$ine = '0000000000';
				}
				$nome_cidadao = $cidadao_nome;
				$nome_mae_cidadao = $cidadao_mae;
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
				INSERT INTO tmp_hipertensos(
					cns,
					cpf,
					cns_alternativo,
					cpf_alternativo,
					data_nascimento,
					g_co_dim_tempo,
					cidadao_ativo,
					data_falecimento,
					cidadao_nome,
					cidadao_mae,
					cidadao_faleceu,
					cidadao_sexo,
					cind_gestante,
					cind_hipertenso,
					cind_diabetico,
					cind_sexo,
					cind_micro_area,
					cns_resp,
					cpf_resp,
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
					".$result1['co_dim_tempo'].",
					".$cidadao_ativo.",
					".$data_falecimento.",
					'".$nome_cidadao."',
					'".$nome_mae_cidadao."',
					".$cidadao_faleceu.",
					'".$cidadao_sexo."',
					".$cind_gestante.",
					".$cind_hipertenso.",
					".$cind_diabetico.",
					'".$nome_sexo."',
					'".$cind_micro_area."',
					'".$cind_cns_res."',
					'".$cind_cpf_res."',
					".$cind_inativo.",
					'".$cnes."',
					'".$nome_unidade."',
					'".$ine."',
					'".$nome_equipe."',
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
						if ($cnes == $vlbusca){
							$gravar = true;
						}
					}
					if ($cpbusca == 'E'){
						$vlbusca = zesq($vlbusca,10);
						if ($ine == $vlbusca){
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
						if ($cind_micro_area == $vlbusca){
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
				if ($cidadao_faleceu != 0){
					$gravar = false;
				}
				if ($cind_inativo != 0){
					$gravar = false;
				}
				if ($cidadao_ativo != 1){
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
							if ($cind_micro_area != '00' && $cind_micro_area != 'FA'){
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
}

// ===========================================================================================================
$rel_cabecalho_2 = "
<table width=\"1009\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"relatorio-tabela\">
  <!--DWLayoutTable-->
  <tr> 
    <td width=\"440\" height=\"32\" valign=\"top\" class=\"relatorio-titulo\">Relatório de Hipertensos</td>
    <td width=\"392\" valign=\"top\" class=\"relatorio-periodo\">Período entre ".dtshow($dti)." e ".dtshow($dtf)."</td>
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
	tmp_hipertensos
ORDER BY ".$ordena_grupo." ".$ordenar;
$run_hi = pg_query($cdb,$SQL_2);
$nm_sql_2 = 0;
$nm_sql_2 = pg_num_rows($run_hi);
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   SQL_2
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
if ($nm_sql_2 > 0){
	while ($result2 = pg_fetch_array($run_hi)){
		$conta_geral++;
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		//   Controle de duplicados
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		$duplicados_consultas = array();
		$conta_duplicados_consultas = 0;
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
		$dt_6meses1 = datasomadias($per_12m,1);
		$dt_6meses2 = semestrem($dtf);
		$idade = idadeint($result2['data_nascimento'],$dtf);
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		//   CONSULTAS
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		$array_consultas = array();
		$array_consultas_data = array();
		$cont_array_consultas = 0;
		$consultas = "
			SELECT
				*
			FROM
			(
				-------------------------------------------------------
				-------------------------------------------------------
				-------------------------------------------------------
				SELECT
					t5.*,
					tb_dim_profissional.nu_cns AS cns_prof,
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
									co_dim_tempo,
									CASE WHEN co_dim_cbo_1 = 1 THEN co_dim_cbo_2
										ELSE co_dim_cbo_1 END
										co_dim_cbo,
									CASE WHEN co_dim_unidade_saude_1 = 1 THEN co_dim_unidade_saude_2
										ELSE co_dim_unidade_saude_1 END
										co_dim_unidade_saude,
									CASE WHEN co_dim_equipe_1 = 1 THEN co_dim_equipe_2
										ELSE co_dim_equipe_1 END
										co_dim_equipe,
										CASE WHEN co_dim_profissional_1 = 1 THEN co_dim_profissional_2
											ELSE co_dim_profissional_1 END
											co_dim_profissional,
									ds_filtro_ciaps,
									ds_filtro_cids
								FROM
									tb_fat_atendimento_individual
								WHERE
									".$campo_busca." AND
									(co_dim_tempo >= ".$dt_6meses1." AND co_dim_tempo <= ".$dtf.") AND
									(
										ds_filtro_ciaps LIKE ANY (
											array[
												'%|K86|%',
												'%|K87|%',
												'%|W81|%',
												'%|ABP005|%'
											]
										) OR
										ds_filtro_cids LIKE ANY (
											array[
												'%|I10|%',
												'%|I11|%',
												'%|I110|%',
												'%|I119|%',
												'%|I12|%',
												'%|I120|%',
												'%|I129|%',
												'%|I13|%',
												'%|I130|%',
												'%|I131|%',
												'%|I132|%',
												'%|I139|%',
												'%|I15|%',
												'%|I150|%',
												'%|I151|%',
												'%|I152|%',
												'%|I158|%',
												'%|I159|%',
												'%|I270|%',
												'%|I272|%',
												'%|O10|%',
												'%|O100|%',
												'%|O101|%',
												'%|O102|%',
												'%|O103|%',
												'%|O104|%',
												'%|O109|%'
											]
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
				) AS t5
				LEFT JOIN
					tb_dim_profissional
				ON tb_dim_profissional.co_seq_dim_profissional = t5.co_dim_profissional
				-------------------------------------------------------
				-------------------------------------------------------
				-------------------------------------------------------
			) AS t4
			WHERE
				nu_cbo LIKE ANY (array['2251%','2252%','2253%','2231%','2235%'])
			ORDER BY co_dim_tempo
		";
		$conta_consultas_s1 = 0;
		$conta_consultas_s2 = 0;
		$run_con = pg_query($cdb,$consultas);
		if (pg_num_rows($run_con) > 0){
			while ($exc = pg_fetch_array($run_con)){
				$junta_consultas = $exc['co_dim_tempo'].$exc['nu_cbo'];
				if (!in_array($junta_consultas, $duplicados_consultas)) {
					$duplicados_consultas[$conta_duplicados_consultas] = $junta_consultas;
					$conta_duplicados_consultas++;
					$array_consultas[$cont_array_consultas]['data'] = $exc['co_dim_tempo'];
					$array_consultas[$cont_array_consultas]['cbo'] = $exc['nu_cbo'];
					$array_consultas[$cont_array_consultas]['cnes'] = $exc['nu_cnes'];
					$array_consultas[$cont_array_consultas]['ine'] = $exc['nu_ine'];
					$array_consultas[$cont_array_consultas]['ciaps'] = str_replace("|"," ",$exc['ds_filtro_ciaps']);
					$array_consultas[$cont_array_consultas]['cids'] = str_replace("|"," ",$exc['ds_filtro_cids']);
					$array_consultas[$cont_array_consultas]['semestre'] = '0';
					$array_consultas[$cont_array_consultas]['cns_prof'] = $exc['cns_prof'];
					$array_consultas[$cont_array_consultas]['no_profissional'] = $exc['no_profissional'];
					if ($exc['co_dim_tempo'] >= $dt_6meses1 && $exc['co_dim_tempo'] < $dt_6meses2){
						$conta_consultas_s1++;
						$array_consultas[$cont_array_consultas]['semestre'] = '1';
					}
					if ($exc['co_dim_tempo'] >= $dt_6meses2 && $exc['co_dim_tempo'] <= $dtf){
						$conta_consultas_s2++;
						$array_consultas[$cont_array_consultas]['semestre'] = '2';
					}
					$array_consultas_data[$cont_array_consultas] = $exc['co_dim_tempo'];
					$cont_array_consultas++;
				}
			}
		}
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		//   PROCEDIMENTOS
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		$array_procedimentos = array();
		$cont_array_procedimentos = 0;
		$contro_cbo = "'2251%', '2252%', '2253%', '2231%', '2235%', '3222%'";
		$procedimentos = "'0301100039','ABPG033'";
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
											(co_dim_tempo >= ".$dt_6meses1." AND co_dim_tempo <= ".$dtf.") AND
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
					$array_procedimentos[$cont_array_procedimentos]['procedimento1'] = $ex1['avaliado'];
					$array_procedimentos[$cont_array_procedimentos]['procedimento2'] = $ex1['solicitado'];
					$array_procedimentos[$cont_array_procedimentos]['tabela'] = $ex1['tabela'];
					$array_procedimentos[$cont_array_procedimentos]['cns_prof'] = $ex1['cns_prof'];
					$array_procedimentos[$cont_array_procedimentos]['no_profissional'] = $ex1['no_profissional'];
					if ($ex1['co_dim_tempo'] >= $dt_6meses1 && $ex1['co_dim_tempo'] < $dt_6meses2){
						$array_procedimentos[$cont_array_procedimentos]['semestre'] = '1';
					}
					if ($ex1['co_dim_tempo'] >= $dt_6meses2 && $ex1['co_dim_tempo'] <= $dtf){
						$array_procedimentos[$cont_array_procedimentos]['semestre'] = '2';
					}
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
										(co_dim_tempo >= ".$dt_6meses1." AND co_dim_tempo <= ".$dtf.") AND
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
					if ($ex2['co_dim_tempo'] >= $dt_6meses1 && $ex2['co_dim_tempo'] < $dt_6meses2){
						$array_procedimentos[$cont_array_procedimentos]['semestre'] = '1';
					}
					if ($ex2['co_dim_tempo'] >= $dt_6meses2 && $ex2['co_dim_tempo'] <= $dtf){
						$array_procedimentos[$cont_array_procedimentos]['semestre'] = '2';
					}
					$cont_array_procedimentos++;
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
			$inconsistencias .= " Hipertenso sem cadastro familiar.<br>";
		}

		if (substr($result2['cidadao_nome'],0,1) == '#'){
			$inconsistencias .= "Hipertenso não localizada em tb_cidadao.<br>";
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
		if (strlen($inconsistencias) > 0){
			$estilo_inco = "lista-status-Vermelho";
		}
		$exame_rotina = "NÃO";
		$estilo_exrot = "indicador-c1-NAO";
		$estilo_exrot_indicador = "indicador-titulo-Vermelho-B";
		$show_e_data = "";
		$semestre1 = false;
		$semestre2 = false;
		$soma_proc_s1 = 0;
		$soma_proc_s2 = 0;
		if (count($array_procedimentos) > 0){
			for ($i=0;$i<count($array_procedimentos);$i++){
				if ($array_procedimentos[$i]['semestre'] == '1'){
					$soma_proc_s1++;
				} else {
					$soma_proc_s2++;
				}
			}
			$conta_consultas_x = $conta_consultas_s1 + $conta_consultas_s2;
			if ($soma_proc_s1 > 0 && $conta_consultas_x > 0){
				$semestre1 = true;
			}
			if ($soma_proc_s2 > 0 && $conta_consultas_x > 0){
				$semestre2 = true;
			}
			if ($semestre1 && $semestre2){
				$exame_rotina = "SIM";
				$estilo_exrot = "indicador-c1-SIM";
				$estilo_exrot_indicador = "indicador-titulo-Verde-B";
				$numerador_ind6++;
				if (false !== $key = array_search($result2['ine'], array_column($total_equipe, 'ine'))) {
					$total_equipe[$key]['num'] = $total_equipe[$key]['num'] + 1;
				}
				if (false !== $key = array_search($result2['cnes'], array_column($total_unidade, 'cnes'))) {
					$total_unidade[$key]['num'] = $total_unidade[$key]['num'] + 1;
				}
			} else {
				if ($semestre1){
					$estilo_exrot_indicador = "indicador-titulo-Amarelo-B";
				}
				if ($semestre2){
					$estilo_exrot_indicador = "indicador-titulo-Amarelo-B";
				}
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
			<td colspan=\"2\" valign=\"top\" class=\"lista-sub-dados-BB\">Sexo</td>
			<td colspan=\"2\" valign=\"top\" class=\"lista-dado2-centro-B\">".$result2['cidadao_sexo']."</td>
			<td colspan=\"2\" valign=\"top\" class=\"lista-sub-dados-B\">Declarado Diab&eacute;tico</td>
			<td width=\"69\" valign=\"top\" class=\"lista-dado2-centro-B\">".$marcado_diabetico."</td>
			<td width=\"84\" valign=\"top\" class=\"lista-sub-dados-B\">".$show_gp_nm2_inverso."</td>
			<td valign=\"top\" class=\"lista-dado2-centro-B\">".$show_gp_id2_inverso."</td>
		  </tr>
		  <tr> 
			<td height=\"19\"></td>
			<td></td>
			<td colspan=\"2\" valign=\"top\" class=\"lista-sub-dados-BB\">.</td>
			<td colspan=\"2\" valign=\"top\" class=\"lista-dado2-centro-B\">".$result2['cds']."</td>
			<td colspan=\"2\" valign=\"top\" class=\"lista-sub-dados-B\">Declarado Hipertenso</td>
			<td valign=\"top\" class=\"lista-dado2-centro-B\">".$marcado_hipertenso."</td>
			<td valign=\"top\" class=\"lista-sub-dados-B\">Idade</td>
			<td valign=\"top\" class=\"lista-dado2-centro-B\">".$idade."</td>
		  </tr>
		  <tr> 
			<td height=\"16\"></td>
			<td></td>
			<td colspan=\"2\" rowspan=\"2\" valign=\"top\" class=\"".$estilo_exrot_indicador."\">Indicador 6 [ ".$exame_rotina." ]</td>
			<td colspan=\"3\" rowspan=\"2\" valign=\"top\" class=\"indicador-c1-B\">Verificação de pressão arterial</td>
			<td colspan=\"4\" rowspan=\"2\" valign=\"top\" class=\"indicador-c1-centro-X-B\">.</td>
			<td colspan=\"2\" valign=\"top\" class=\"indicador-c1\">Semestre 1: Cons. ".$conta_consultas_s1." | Proc. ".$soma_proc_s1."</td>
		  </tr>
		  <tr> 
			<td height=\"16\"></td>
			<td></td>
			<td colspan=\"2\" valign=\"top\" class=\"indicador-cx-B\">Semestre 2: Cons. ".$conta_consultas_s2." | Proc. ".$soma_proc_s2."</td>
		  </tr>
		  ";
		fwrite($HTML, $rel_dados_unitario);
		$texto = zesq($conta_geral,5).";".mcpf($rCPF).";".mcns($rCNS).";".str_replace("Ã","A",$result2['cidadao_nome']).";".dtshow($result2['data_nascimento']).";".str_replace("Ã","A",$result2['cidadao_mae']).";".$idade.";".str_replace("Ã","A",$marcado_gestante).";".str_replace("Ã","A",$marcado_hipertenso).";".str_replace("Ã","A",$marcado_diabetico).";".str_replace("Ã","A",$exame_rotina).";".$result2['cidadao_sexo'].";".$result2['cnes'].";".$result2['ine'].";".$ma_familiar."/".$result2['cind_micro_area'].";".$conta_consultas_s1.";".$conta_consultas_s2.";".$soma_proc_s1.";".$soma_proc_s2."\r\n";
		fwrite($FT, $texto);
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
					  <td width=\"169\" valign=\"top\" class=\"procedimentos-cabeca\">CID / CIAP</td>
					  <td width=\"75\" valign=\"top\" class=\"procedimentos-cabeca\">Data</td>
					  <td width=\"213\" valign=\"top\" class=\"procedimentos-cabeca\">CNS Prof.</td>
					  <td width=\"43\" valign=\"top\" class=\"procedimentos-cabeca\">CBO</td>
					  <td width=\"78\" valign=\"top\" class=\"procedimentos-cabeca\">INE</td>
					  <td width=\"56\" valign=\"top\" class=\"procedimentos-cabeca\">CNES</td>
					  <td width=\"62\" valign=\"top\" class=\"procedimentos-cabeca\">Semes.</td>
					</tr>
			";
			$soma_consultas = 0;
			for ($i=0;$i<count($array_consultas);$i++){
				$soma_consultas++;

				$profissionalOK = 'procedimentos-dado';
				$profissionalAlerta = 'NAO';
				if (strlen($array_consultas[$i]['ine']) == 10){
					if (file_exists('xml/cnes.xml')){
						if (!cnes('xml/cnes.xml',$array_consultas[$i]['cns_prof'],$array_consultas[$i]['cbo'],$array_consultas[$i]['ine'],'I')){
							$profissionalOK = 'procedimentos-dado-amarelo';
							$profissionalAlerta = 'SIM';
						}
					}
				}
				$texto = $soma_consultas.";".mcpf($rCPF).";".mcns($rCNS).";".dtshow($array_consultas[$i]['data']).";".$array_consultas[$i]['cnes'].";".$array_consultas[$i]['ine'].";".$array_consultas[$i]['cbo'].";".$array_consultas[$i]['semestre'].";".$array_consultas[$i]['cids'].";".$array_consultas[$i]['ciaps'].";".$array_consultas[$i]['cns_prof'].";".$array_consultas[$i]['no_profissional'].";".$profissionalAlerta."\r\n";
				fwrite($FC, $texto);
				$rel_dados_sub_tabela .= "
						<tr> 
						  <td height=\"18\" valign=\"top\" class=\"procedimentos-dado\">".$soma_consultas."</td>
						  <td valign=\"top\" class=\"procedimentos-dado\">".$array_consultas[$i]['cids']." / ".$array_consultas[$i]['ciaps']."</td>
						  <td valign=\"top\" class=\"procedimentos-dado\">".dtshow($array_consultas[$i]['data'])."</td>
						  <td valign=\"top\" class=\"".$profissionalOK."\">".$array_consultas[$i]['cns_prof']."</td>
						  <td valign=\"top\" class=\"procedimentos-dado\">".$array_consultas[$i]['cbo']."</td>
						  <td valign=\"top\" class=\"procedimentos-dado\">".$array_consultas[$i]['ine']."</td>
						  <td valign=\"top\" class=\"procedimentos-dado\">".$array_consultas[$i]['cnes']."</td>
						  <td valign=\"top\" class=\"procedimentos-dado\">".$array_consultas[$i]['semestre']."</td>
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
					  <td valign=\"top\" class=\"procedimentos-dado\">".$soma_consultas."</td>
					</tr>
				  </table>
				  </td>
			  </tr>
			";
			fwrite($HTML, $rel_dados_sub_tabela);
		}
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
					  <td width=\"62\" valign=\"top\" class=\"procedimentos-cabeca\">Semes.</td>
					</tr>
			";
			$soma_procedimentos = 0;
			for ($i=0;$i<count($array_procedimentos);$i++){
				$soma_procedimentos++;
				
				$consproced = '';
				$consproced_csv = 'N';
				if (in_array($array_procedimentos[$i]['data'], $array_consultas_data)) {
					$consproced = '*';
					$consproced_csv = 'S';
				}

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
				$texto = $soma_procedimentos.";".mcpf($rCPF).";".mcns($rCNS).";".dtshow($array_procedimentos[$i]['data']).";".$consproced_csv.";".$array_procedimentos[$i]['tabela'].";".$array_procedimentos[$i]['cns_prof'].";".$array_procedimentos[$i]['no_profissional'].";".$array_procedimentos[$i]['cnes'].";".$array_procedimentos[$i]['ine'].";".$array_procedimentos[$i]['cbo'].";".$array_procedimentos[$i]['semestre'].";".$array_procedimentos[$i]['procedimento1']."/".$array_procedimentos[$i]['procedimento2'].";".$profissionalAlerta."\r\n";
				fwrite($FP, $texto);
				$rel_dados_sub_tabela .= "
						<tr> 
						  <td height=\"18\" valign=\"top\" class=\"procedimentos-dado\">".$soma_procedimentos."</td>
						  <td valign=\"top\" class=\"procedimentos-dado\">".$array_procedimentos[$i]['procedimento1']." / ".$array_procedimentos[$i]['procedimento2']."</td>
						  <td valign=\"top\" class=\"procedimentos-dado\">".dtshow($array_procedimentos[$i]['data'])." ".$consproced."</td>
						  <td valign=\"top\" class=\"".$profissionalOK."\">".$array_procedimentos[$i]['cns_prof']."</td>
						  <td valign=\"top\" class=\"procedimentos-dado\">".$array_procedimentos[$i]['cbo']."</td>
						  <td valign=\"top\" class=\"procedimentos-dado\">".$array_procedimentos[$i]['ine']."</td>
						  <td valign=\"top\" class=\"procedimentos-dado\">".$array_procedimentos[$i]['cnes']."</td>
						  <td valign=\"top\" class=\"procedimentos-dado\">".$array_procedimentos[$i]['semestre']."</td>
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
	$num = $numerador_ind6;
	$cdes = $conta_geral_desconto;
	if ($nm_sql_2 > 0){
		$link_pag = "<a href=\"rel_hipertensos.php?pini=".$pini."&num=".$num."&cdes=".$cdes."\"><img src=\"plugins/scsus/temas/".$_SESSION['tema']."/img/pagina.png\"></a>";
	}
}
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   Resultado final
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$show_desconto = "";
if ($conta_geral_desconto > 0){
	$show_desconto = " (descontar ".$conta_geral_desconto." hipertenso(s) por conta de inconsistências)";
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
	Total de ".$total_geral." hipertensos | 
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
	$file = "resumo/r_ind6_".$_SESSION['key'].".php";
	if (file_exists($file)){unlink($file);}
	$FIN = fopen($file,'w');
	$porc_ind = 0;
	if ($denominador_final > 0){
		$porc_ind = (100 * $numerador_ind6) / $denominador_final;
	}
	$porc_ind_est = 0;
	$deno_esti = "";
	if ($des > 0){
		$porc_ind_est = (100 * $numerador_ind6) / $des;
		$deno_esti = "(Estimado: ".$des.")";
	}
	$txind = "<?php
	\$den_ind_6 = ".$denominador_final.";
	\$den_est_6 = ".$des.";
	\$num_6 = ".$numerador_ind6.";
	\$num_prc_6 = ".$porc_ind.";
	\$num_prc_est_6 = ".$porc_ind_est.";
	\$quadri_6 = \"".qdata($dti)."\";
	\$versao_6 = \"".$sobre['versao']."\";
	\$banco_6 = \"".$dbdb."\";
	\$datahora_6 = \"".date('d/m/Y H:i:s')."\";
	\$dti_6 = ".$dti.";
	\$dtf_6 = ".$dtf.";
	\$incons_6 = ".$conta_geral_desconto.";
	\$c_gest_6 = ".$contator_gl_gestante.";
	\$c_diabe_6 = ".$contator_gl_diabetico.";
	";
	for ($e=0;$e<count($total_equipe);$e++){
		$txind .= "\r\$equipe_6[".$e."]['ine'] = \"".$total_equipe[$e]['ine']."\"; \$equipe_6[".$e."]['nome'] = \"".$total_equipe[$e]['nome']."\"; \$equipe_6[".$e."]['den'] = \"".$total_equipe[$e]['den']."\"; \$equipe_6[".$e."]['num'] = \"".$total_equipe[$e]['num']."\";";
	}
	for ($e=0;$e<count($total_unidade);$e++){
		$txind .= "\r\$unidade_6[".$e."]['cnes'] = \"".$total_unidade[$e]['cnes']."\"; \$unidade_6[".$e."]['nome'] = \"".$total_unidade[$e]['nome']."\"; \$unidade_6[".$e."]['den'] = \"".$total_unidade[$e]['den']."\"; \$unidade_6[".$e."]['num'] = \"".$total_unidade[$e]['num']."\";";
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
		<td width=\"260\" valign=\"top\" class=\"resumo-sub-titulo\">Indicador 6</td>
		<td width=\"120\">&nbsp;</td>
	  </tr>
	  <tr> 
		<td height=\"30\" valign=\"top\" class=\"resumo-numerador\">Numerador&nbsp;&nbsp;</td>
		<td valign=\"top\" class=\"resumo-v-numerador\">".$numerador_ind6." [ ".ceil($porc_ind)."% ]</td>
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
fclose($FC);
fclose($FP);

?>