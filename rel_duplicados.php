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
if (file_exists("config/c_rel_du_".$_SESSION['key'].".php")){
	require_once("config/c_rel_du_".$_SESSION['key'].".php");
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
$file = "html/rel_duplicados_".$_SESSION['key'].".html";
if (file_exists($file)){unlink($file);}
$HTML = fopen($file,'w');
// -----------------------------------------------------------------------
$file = "csv/duplicados_T_".$_SESSION['key'].".csv";
if (file_exists($file)){unlink($file);}
$FT = fopen($file,'w');
$texto = "Seq;CPF;CNS;Nome;DtNascimento;Mae;Pai;Idade;MarcGestante;MarcHipertensa;MarcDiabetica;SeqCid;DtAtuali;Unificado;CNES;INE;MA\r\n";
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
$num1 = isset($_GET["num1"]) ? trim($_GET["num1"]) : 0;
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

$conta_geral = $pini;
$conta_geral_desconto = $cdes;
$numerador_ind1 = $num1;
$conta_gravacao = 0;

// ===========================================================================================================
$rel_pagina_inicio = "
  <!-- Content Wrapper. Contains page content -->
  <div class=\"content-wrapper\">
    <!-- Content Header (Page header) -->
    <section class=\"content-header\">
      <div class=\"container-fluid\">
        <div class=\"row mb-2\">
          <div class=\"col-sm-6\">
            <h1>Duplicados</h1>
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
CREATE TEMPORARY TABLE tmp_duplicados (
	sequencia serial PRIMARY KEY,
	cns varchar(15),
	cpf varchar(11),
	cns_alternativo varchar(15),
	cpf_alternativo varchar(11),
	data_nascimento bigint,
	cidadao_nome varchar(500),
	cidadao_mae varchar(500),
	cidadao_pai varchar(500),
	seq_cid bigint,
	data_atuali bigint,
	unificado int,
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
		a.nu_cns,
		a.nu_cpf as nu_cpf_cidadao,
		a.no_cidadao,
		a.dt_nascimento,
		a.no_mae,
		a.no_pai,
		a.co_seq_cidadao,
		a.dt_atualizado,
		a.st_unificado
	FROM
		tb_cidadao AS a 
	INNER JOIN (
		SELECT 
			no_cidadao, 
			dt_nascimento, 
			count(*) 
		FROM 
			tb_cidadao 
		WHERE
			st_faleceu = 0 AND
			st_ativo = 1 AND
			st_unificado = 0
		GROUP BY 
			no_cidadao, 
			dt_nascimento 
		HAVING count(*) > 1
	) AS b 
	ON 
		a.no_cidadao = b.no_cidadao AND a.dt_nascimento = b.dt_nascimento
	ORDER BY a.no_cidadao
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
			$campo_busca = "(nu_cns = '".$CNS."' OR nu_cpf_cidadao = '".$CPF."')";
			$campo_ordem = "nu_cns, nu_cpf_cidadao";
			$busca = true;
		} else {
			if ($busca_cns){
				$campo_busca = "nu_cns = '".$CNS."'";
				$campo_ordem = "nu_cpf_cidadao DESC";
				$busca = true;
			}
			if ($busca_cpf){
				$campo_busca = "nu_cpf_cidadao = '".$CPF."'";
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
				if (strlen($cind_micro_area) == 1){
					$cind_micro_area = '0'.$cind_micro_area;
				}
				if (strlen($cind_micro_area) == 0 || $cind_micro_area == NULL){
					$cind_micro_area = '00';
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
			$data_nascimento = dataint($result1['dt_nascimento']); // ou dataint($cidadao_nascimento)
			$cidadao_nome = htmlspecialchars($result1['no_cidadao'], ENT_QUOTES);
			$cidadao_mae = htmlspecialchars($result1['no_mae'], ENT_QUOTES);
			$cidadao_pai = htmlspecialchars($result1['no_pai'], ENT_QUOTES);
			if ($cind_micro_area == '-' || $cind_micro_area == '--' || $cind_micro_area == '0-'){
				$cind_micro_area = '00';
			}
			if ($ine == '-'){
				$ine = '0000000000';
			}
			
			if (strlen($result1['dt_atualizado']) > 6){
				$data_atuali = dataint(substr($result1['dt_atualizado'],0,10));
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
			INSERT INTO tmp_duplicados(
				cns,
				cpf,
				cns_alternativo,
				cpf_alternativo,
				data_nascimento,
				cidadao_nome,
				cidadao_mae,
				cidadao_pai,
				seq_cid,
				data_atuali,
				unificado,
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
				nome_equipe
			) VALUES (
				'".$CNS."',
				'".$CPF."',
				'".$CNSA."',
				'".$CPFA."',
				".$data_nascimento.",
				'".$cidadao_nome."',
				'".$cidadao_mae."',
				'".$cidadao_pai."',
				".$result1['co_seq_cidadao'].",
				".$data_atuali.",
				".$result1['st_unificado'].",
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
				'".$nome_equipe."'
			)";
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			//   Usuario
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			$gravar = true;
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
			if ($gravar){
				$run_ins = pg_query($cdb,$inserir);
				$conta_gravacao++;
			}
		}
	}
}


// ===========================================================================================================
$rel_cabecalho_2 = "
<table width=\"1009\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"relatorio-tabela\">
  <!--DWLayoutTable-->
  <tr> 
    <td width=\"440\" height=\"32\" valign=\"top\" class=\"relatorio-titulo\">Relatório de Duplicados</td>
    <td width=\"392\" valign=\"top\" class=\"relatorio-periodo\">Duplicados ativos</td>
    <td width=\"177\" valign=\"top\" class=\"relatorio-pagina\"></td>
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
	tmp_duplicados
ORDER BY ".$ordenar;
$run_gf = pg_query($cdb,$SQL_2);
$nm_sql_2 = 0;
$nm_sql_2 = pg_num_rows($run_gf);
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   SQL_2
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
if ($nm_sql_2 > 0){
	while ($result2 = pg_fetch_array($run_gf)){
		$conta_geral++;
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		//   CNS e CPF
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		$rCNS = trim($result2['cns']);
		$rCPF = trim($result2['cpf']);
		
		$show_gp_inverso = "Unidade";	
		$show_gp_id_inverso = $result2['cnes'];	
		$show_gp_nm_inverso = $result2['nome_unidade']." (".$result2['ine'].")";
		$show_gp_id2_inverso = $result2['cind_micro_area'];
		$show_gp_nm2_inverso = "Micro-&Aacute;rea";
		// ===========================================================================================================
		$rel_dados_grupo = "
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
		
		$idade = idadeint($result2['data_nascimento']);
		
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
		}
		if ($result2['cind_diabetico'] == 1){
			$marcado_diabetico = "SIM";
		}
		$estilo_inco = "lista-status";
		$inconsistencias = "";
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
		}
		if ($rCNS == '0' && $rCPF == '0'){
			$inconsistencias .= "Sem CNS e sem CPF.<br>-<br>";
		}
		if ($result2['cind_inativo'] != 0){
			$inconsistencias .= "Cadastro está como inativo (Cad. Ind.).<br>";
		}
		if (strlen($inconsistencias) > 0){
			$estilo_inco = "lista-status-Vermelho";
		}
		// ===========================================================================================================
		$rel_dados_unitario = "
		  <tr> 
			<td height=\"18\"></td>
			<td valign=\"top\" class=\"lista-dado1-centro-BB\">".zesq($conta_geral,5)."</td>
			<td valign=\"top\" class=\"lista-dado1-centro-B\">".mcpf($rCPF)."</td>
			<td colspan=\"2\" valign=\"top\" class=\"lista-dado1-centro-B\">".mcns($rCNS)."</td>
			<td colspan=\"8\" valign=\"top\" class=\"lista-dado1-esquerdo-B\">".$result2['cidadao_nome']."</td>
			<td valign=\"top\" class=\"lista-dado1-centro-B\">".dtshow($result2['data_nascimento'])."</td>
			<td colspan=\"2\" rowspan=\"6\" valign=\"top\" class=\"".$estilo_inco."\">
			<p>
				".$inconsistencias."
			</p></td>
		  </tr>
		  <tr> 
			<td height=\"18\"></td>
			<td valign=\"top\" class=\"lista-dado1-centro-B\"><!--DWLayoutEmptyCell-->&nbsp;</td>
			<td colspan=\"2\" valign=\"top\" class=\"lista-sub-dados-BB\">Nome da m&atilde;e</td>
			<td colspan=\"9\" valign=\"top\" class=\"lista-dado2-centro-B\">".$result2['cidadao_mae']."</td>
			<td valign=\"top\" class=\"lista-dado2-centro-B\">".$result2['seq_cid']."</td>
		  </tr>
		  <tr> 
			<td height=\"18\"></td>
			<td valign=\"top\"><!--DWLayoutEmptyCell-->&nbsp;</td>
			<td colspan=\"2\" valign=\"top\" class=\"lista-sub-dados-BB\">Nome do pai</td>
			<td colspan=\"9\" valign=\"top\" class=\"lista-dado2-centro-B\">".$result2['cidadao_pai']."</td>
			<td valign=\"top\" class=\"lista-dado2-centro-B\"></td>
		  </tr>
		  <tr> 
			<td height=\"18\"></td>
			<td></td>
			<td colspan=\"2\" valign=\"top\" class=\"lista-sub-dados-BB\">".$show_gp_inverso."</td>
			<td colspan=\"2\" valign=\"top\" class=\"lista-dado2-centro-B\">".$show_gp_id_inverso."</td>
			<td colspan=\"8\" valign=\"top\" class=\"lista-dado2-centro-B\">".$show_gp_nm_inverso."</td>
		  </tr>
		  <tr> 
			<td height=\"18\"></td>
			<td></td>
			<td colspan=\"2\" valign=\"top\" class=\"lista-sub-dados-BB\">Declarada gestante</td>
			<td colspan=\"2\" valign=\"top\" class=\"lista-dado2-centro-B\">".$marcado_gestante."</td>
			<td colspan=\"3\" valign=\"top\" class=\"lista-sub-dados-B\">Idade</td>
			<td colspan=\"2\" valign=\"top\" class=\"lista-dado2-centro-B\">".$idade."</td>
			<td colspan=\"2\" valign=\"top\" class=\"lista-sub-dados-B\">".$show_gp_nm2_inverso."</td>
			<td valign=\"top\" class=\"lista-dado2-centro-B\">".$show_gp_id2_inverso."</td>
		  </tr>
		  <tr> 
			<td height=\"18\"></td>
			<td></td>
			<td colspan=\"2\" valign=\"top\" class=\"lista-sub-dados-BB\">Declarada Diab&eacute;tica</td>
			<td colspan=\"2\" valign=\"top\" class=\"lista-dado2-centro-B\">".$marcado_diabetico."</td>
			<td colspan=\"3\" valign=\"top\" class=\"lista-sub-dados-B\">Declarada Hipertensa</td>
			<td colspan=\"2\" valign=\"top\" class=\"lista-dado2-centro-B\">".$marcado_hipertenso."</td>
			<td colspan=\"2\" valign=\"top\" class=\"lista-sub-dados-B\">Dt. Atualização</td>
			<td valign=\"top\" class=\"lista-dado2-centro-B\">".dtshow($result2['data_atuali'])."</td>
		  </tr>";
		fwrite($HTML, $rel_dados_unitario);

		$texto = zesq($conta_geral,5).";".mcpf($rCPF).";".mcns($rCNS).";".str_replace("Ã","A",$result2['cidadao_nome']).";".dtshow($result2['data_nascimento']).";".str_replace("Ã","A",$result2['cidadao_mae']).";".str_replace("Ã","A",$result2['cidadao_pai']).";".$idade.";".str_replace("Ã","A",$marcado_gestante).";".str_replace("Ã","A",$marcado_hipertenso).";".str_replace("Ã","A",$marcado_diabetico).";".$result2['seq_cid'].";".dtshow($result2['data_atuali']).";".$result2['unificado'].";".$result2['cnes'].";".$result2['ine'].";".$result2['cind_micro_area']."\r\n";
		fwrite($FT, $texto);

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
	if ($nm_sql_2 > 0){
		$link_pag = "<a href=\"rel_duplicados.php?pini=".$pini."\"><img src=\"plugins/scsus/temas/".$_SESSION['tema']."/img/pagina.png\"></a>";
	}
}
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   Resultado final
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
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
    <td></td>
    <td width=\"64\"></td>
    <td width=\"19\"></td>
    <td width=\"62\"></td>
    <td></td>
    <td width=\"95\"></td>
    <td width=\"92\"></td>
    <td></td>
    <td></td>
    <td width=\"41\"></td>
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
	Total de ".$total_geral." duplicados | 
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
