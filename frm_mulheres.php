<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   15/07/2021
//   Rodrigo Silva
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

require_once('session.php');
require_once('functions.php');
if (file_exists("config/c_rel_m_".$_SESSION['key'].".php")){
	require_once("config/c_rel_m_".$_SESSION['key'].".php");
} else {
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
	$gpa = 0;
	$cfa = 0;
	$paginacao = 0;
	$grupo = 'ine';
	$ordem = 'N';
	$mcabecalho = 1;
	$dt3anos = 'U';
	$ridade = 0;
	$proceds = '0204030188';
	$idin = 0;
	$idfi = 1;
	$apvac = 0;
	$tbusca = 12;
	$des = 0;
}

$lridade = array();
$lridade[0][0]  = 0;  $lridade[0][1]  = "de 25 até 64";
$lridade[1][0]  = 1;  $lridade[1][1]  = "de 25 até 30";
$lridade[2][0]  = 2;  $lridade[2][1]  = "de 31 até 40";
$lridade[3][0]  = 3;  $lridade[3][1]  = "de 41 até 50";
$lridade[4][0]  = 4;  $lridade[4][1]  = "de 51 até 60";
$lridade[5][0]  = 5;  $lridade[5][1]  = "de 61 até 64";
$lridade[6][0]  = 6;  $lridade[6][1]  = "de 25 até 40";
$lridade[7][0]  = 7;  $lridade[7][1]  = "de 40 até 60";
$lridade[8][0]  = 8;  $lridade[8][1]  = "de 50 até 64";
$lridade[9][0]  = 9;  $lridade[9][1]  = "de 25 até 35";
$lridade[10][0] = 25; $lridade[10][1] = "25";
$lridade[11][0] = 26; $lridade[11][1] = "26";
$lridade[12][0] = 27; $lridade[12][1] = "27";
$lridade[13][0] = 28; $lridade[13][1] = "28";
$lridade[14][0] = 29; $lridade[14][1] = "29";
$lridade[15][0] = 30; $lridade[15][1] = "30";
$lridade[16][0] = 31; $lridade[16][1] = "31";
$lridade[17][0] = 32; $lridade[17][1] = "32";
$lridade[18][0] = 33; $lridade[18][1] = "33";
$lridade[19][0] = 34; $lridade[19][1] = "34";
$lridade[20][0] = 35; $lridade[20][1] = "35";
$lridade[21][0] = 36; $lridade[21][1] = "36";
$lridade[22][0] = 37; $lridade[22][1] = "37";
$lridade[23][0] = 38; $lridade[23][1] = "38";
$lridade[24][0] = 39; $lridade[24][1] = "39";
$lridade[25][0] = 40; $lridade[25][1] = "40";
$lridade[26][0] = 41; $lridade[26][1] = "41";
$lridade[27][0] = 42; $lridade[27][1] = "42";
$lridade[28][0] = 43; $lridade[28][1] = "43";
$lridade[29][0] = 44; $lridade[29][1] = "44";
$lridade[30][0] = 45; $lridade[30][1] = "45";
$lridade[31][0] = 46; $lridade[31][1] = "46";
$lridade[32][0] = 47; $lridade[32][1] = "47";
$lridade[33][0] = 48; $lridade[33][1] = "48";
$lridade[34][0] = 49; $lridade[34][1] = "49";
$lridade[35][0] = 50; $lridade[35][1] = "50";
$lridade[36][0] = 51; $lridade[36][1] = "51";
$lridade[37][0] = 52; $lridade[37][1] = "52";
$lridade[38][0] = 53; $lridade[38][1] = "53";
$lridade[39][0] = 54; $lridade[39][1] = "54";
$lridade[40][0] = 55; $lridade[40][1] = "55";
$lridade[41][0] = 56; $lridade[41][1] = "56";
$lridade[42][0] = 57; $lridade[42][1] = "57";
$lridade[43][0] = 58; $lridade[43][1] = "58";
$lridade[44][0] = 59; $lridade[44][1] = "59";
$lridade[45][0] = 60; $lridade[45][1] = "60";
$lridade[46][0] = 61; $lridade[46][1] = "61";
$lridade[47][0] = 62; $lridade[47][1] = "62";
$lridade[48][0] = 63; $lridade[48][1] = "63";
$lridade[49][0] = 64; $lridade[49][1] = "64";

?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Mulheres</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active">Mulheres</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
	  <form action="gv_mulheres.php" method="post" id="formml" name="formml">
        <div class="row">
          <!-- left column -->
          <div class="col-md-6">
            <!-- jquery validation -->
            <div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">Mulheres <small>- dados para geração dos relatórios</small></h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
			  
                <div class="card-body">
                <div class="form-group">
                  <label>Data inicial e data final</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input type="text" class="form-control float-right" id="dtif" name="dtif">
                  </div>
                  <!-- /.input group -->
                </div>
                  <div class="form-group">
                    <label>Qual período considerar para busca?</label>
                      <select class="form-control" name="gpa" id="gpa">
						<?php
						if ($gpa == 0){
							echo "
							  <option value=\"0\" selected>Dada inicial e Data final informada</option>
							  <option value=\"1\">A partir de hoje (busca ativa)</option>
							";
						} else {
							echo "
							  <option value=\"0\">Dada inicial e Data final informada</option>
							  <option value=\"1\" selected>A partir de hoje (busca ativa)</option>
							";
						}
						?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Considerar fora de área</label>
                      <select class="form-control" name="cfa" id="cfa">
						<?php
						if ($cfa == 0){
							echo "
							  <option value=\"0\" selected>Sim</option>
							  <option value=\"1\">Não</option>
							";
						} else {
							echo "
							  <option value=\"0\">Sim</option>
							  <option value=\"1\" selected>Não</option>
							";
						}
						?>
                    </select>
                  </div>
				  
                  <div class="form-group">
                    <label>3 anos a contar ...</label>
                      <select class="form-control" name="dt3anos" id="dt3anos">
						<?php
						if ($dt3anos == 'U'){
							echo "
							  <option value=\"U\" selected>... da última data da análise *</option>
							  <option value=\"P\">... da primeira data da análise</option>
							  <option value=\"A\">... do aniversário do analisado</option>
							";
						} else {
							if ($dt3anos == 'P'){
								echo "
								  <option value=\"U\">... da última data da análise *</option>
								  <option value=\"P\" selected>... da primeira data da análise</option>
								  <option value=\"A\">... do aniversário do analisado</option>
								";
							} else {
								if ($dt3anos == 'A'){
									echo "
									  <option value=\"U\">... da última data da análise *</option>
									  <option value=\"P\">... da primeira data da análise</option>
									  <option value=\"A\" selected>... do aniversário do analisado</option>
									";
								} else {
									echo "
									  <option value=\"U\" selected>... da última data da análise *</option>
									  <option value=\"P\">... da primeira data da análise</option>
									  <option value=\"A\">... do aniversário do analisado</option>
									";
								}
							}
						}
						?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Idade</label>
                      <select class="form-control" name="ridade" id="ridade">
						<?php
						for ($i=0;$i<count($lridade);$i++){
							if ($lridade[$i][0] == $ridade){
								echo "<option value=\"".$lridade[$i][0]."\" selected>".$lridade[$i][1]."</option>";
							} else {
								echo "<option value=\"".$lridade[$i][0]."\">".$lridade[$i][1]."</option>";
							}
						}
						?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="des">Denominador estimado total</label>
                    <input type="text" name="des" class="form-control" id="des" value="<?php echo $des;?>">
                  </div>
                </div>
            </div>
            <!-- /.card -->
            <div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Exames</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
                <div class="card-body">
                  <div class="form-group">
                    <label for="tbusca">Tempo de busca (meses)</label>
                    <input type="text" name="tbusca" class="form-control" id="tbusca" value="<?php echo $tbusca;?>">
                  </div>
                  <div class="form-group">
                    <label for="idin">Idade inicial</label>
                    <input type="text" name="idin" class="form-control" id="idin" value="<?php echo $idin;?>">
                  </div>
                  <div class="form-group">
                    <label for="idfi">Idade final</label>
                    <input type="text" name="idfi" class="form-control" id="idfi" value="<?php echo $idfi;?>">
                  </div>
                  <div class="form-group">
                    <label for="proceds">Procedimentos</label>
                    <input type="text" name="proceds" class="form-control" id="proceds" value="<?php echo $proceds;?>">
                  </div>
                  <div class="form-group">
                    <label>Apenas com procedimento</label>
                      <select class="form-control" name="apvac" id="apvac">
						<?php
						if ($apvac == 0){
							echo "
							  <option value=\"0\" selected>Sim</option>
							  <option value=\"1\">Não</option>
							";
						} else {
							echo "
							  <option value=\"0\">Sim</option>
							  <option value=\"1\" selected>Não</option>
							";
						}
						?>
                    </select>
                  </div>
                </div>
            </div>
            <!-- /.card -->
            </div>
          <!--/.col (left) -->
          <!-- right column -->
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Geral</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
                <div class="card-body">
                  <div class="form-group">
                    <label>Paginação</label>
                      <select class="form-control" name="paginacao" id="paginacao" disabled>
						<?php
						if ($paginacao == 0){
							echo "
							  <option value=\"0\" selected>Sem paginação</option>
							  <option value=\"8\">8</option>
							  <option value=\"100\">100</option>
							  <option value=\"500\">500</option>
							  <option value=\"1000\">1000</option>
							";
						} else {
							if ($paginacao == 8){
								echo "
								  <option value=\"0\">Sem paginação</option>
								  <option value=\"8\" selected>8</option>
								  <option value=\"100\">100</option>
								  <option value=\"500\">500</option>
								  <option value=\"1000\">1000</option>
								";
							} else {
								if ($paginacao == 100){
									echo "
									  <option value=\"0\">Sem paginação</option>
									  <option value=\"8\">8</option>
									  <option value=\"100\" selected>100</option>
									  <option value=\"500\">500</option>
									  <option value=\"1000\">1000</option>
									";
								} else {
									if ($paginacao == 500){
										echo "
										  <option value=\"0\">Sem paginação</option>
										  <option value=\"8\">8</option>
										  <option value=\"100\">100</option>
										  <option value=\"500\" selected>500</option>
										  <option value=\"1000\">1000</option>
										";
									} else {
										if ($paginacao == 1000){
											echo "
											  <option value=\"0\">Sem paginação</option>
											  <option value=\"8\">8</option>
											  <option value=\"100\">100</option>
											  <option value=\"500\">500</option>
											  <option value=\"1000\" selected>1000</option>
											";
										} else {
											echo "
											  <option value=\"0\" selected>Sem paginação</option>
											  <option value=\"8\">8</option>
											  <option value=\"100\">100</option>
											  <option value=\"500\">500</option>
											  <option value=\"1000\">1000</option>
											";
										}
									}
								}
							}
						}
						?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Agrupar por</label>
					<select class="form-control" name="grupo" id="grupo">
						<?php
						if ($grupo == 'ine'){
							echo "
							  <option value=\"ine\" selected>Equipe</option>
							  <option value=\"cnes\">Unidade</option>
							  <option value=\"cind_micro_area\">Micro-Área</option>
							  <option value=\"SG\">Sem grupo</option>
							";
						} else {
							if ($grupo == 'cnes'){
								echo "
								  <option value=\"ine\">Equipe</option>
								  <option value=\"cnes\" selected>Unidade</option>
								  <option value=\"cind_micro_area\">Micro-Área</option>
								  <option value=\"SG\">Sem grupo</option>
								";
							} else {
								if ($grupo == 'cind_micro_area'){
									echo "
									  <option value=\"ine\">Equipe</option>
									  <option value=\"cnes\">Unidade</option>
									  <option value=\"cind_micro_area\" selected>Micro-Área</option>
									  <option value=\"SG\">Sem grupo</option>
									";
								} else {
									if ($grupo == 'SG'){
										echo "
										  <option value=\"ine\">Equipe</option>
										  <option value=\"cnes\">Unidade</option>
										  <option value=\"cind_micro_area\">Micro-Área</option>
										  <option value=\"SG\" selected>Sem grupo</option>
										";
									} else {
										echo "
										  <option value=\"ine\" selected>Equipe</option>
										  <option value=\"cnes\">Unidade</option>
										  <option value=\"cind_micro_area\">Micro-Área</option>
										  <option value=\"SG\">Sem grupo</option>
										";
									}
								}
							}
						}
						?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Ordenar por</label>
                      <select class="form-control" name="ordem" id="ordem">
						<?php
						if ($ordem == 'N'){
							echo "
							  <option value=\"N\" selected>Nome</option>
							  <option value=\"C\">CPF</option>
							  <option value=\"S\">CNS</option>
							  <option value=\"DC\">Idade decrescente</option>
							  <option value=\"DD\">Idade crescente</option>
							";
						} else {
							if ($ordem == 'C'){
								echo "
								  <option value=\"N\">Nome</option>
								  <option value=\"C\" selected>CPF</option>
								  <option value=\"S\">CNS</option>
								  <option value=\"DC\">Idade decrescente</option>
								  <option value=\"DD\">Idade crescente</option>
								";
							} else {
								if ($ordem == 'S'){
									echo "
									  <option value=\"N\">Nome</option>
									  <option value=\"C\">CPF</option>
									  <option value=\"S\" selected>CNS</option>
									  <option value=\"DC\">Idade decrescente</option>
									  <option value=\"DD\">Idade crescente</option>
									";
								} else {
									if ($ordem == 'DC'){
										echo "
										  <option value=\"N\">Nome</option>
										  <option value=\"C\">CPF</option>
										  <option value=\"S\">CNS</option>
										  <option value=\"DC\" selected>Idade decrescente</option>
										  <option value=\"DD\">Idade crescente</option>
										";
									} else {
										if ($ordem == 'DD'){
											echo "
											  <option value=\"N\">Nome</option>
											  <option value=\"C\">CPF</option>
											  <option value=\"S\">CNS</option>
											  <option value=\"DC\">Idade decrescente</option>
											  <option value=\"DD\" selected>Idade crescente</option>
											";
										} else {
											echo "
											  <option value=\"N\" selected>Nome</option>
											  <option value=\"C\">CPF</option>
											  <option value=\"S\">CNS</option>
											  <option value=\"DC\">Idade decrescente</option>
											  <option value=\"DD\">Idade crescente</option>
											";
										}
									}
								}
							}
						}
						?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Mostrar cabeçalho</label>
                      <select class="form-control" name="mcabecalho" id="mcabecalho">
						<?php
						if ($mcabecalho == 1){
							echo "
							  <option value=\"1\" selected>Sim</option>
							  <option value=\"0\">Não</option>
							";
						} else {
							echo "
							  <option value=\"1\">Sim</option>
							  <option value=\"0\" selected>Não</option>
							";
						}
						?>
                    </select>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Gravar</button>
                </div>
            </div>
            <!-- /.card -->
          </div>
          <!--/.col (right) -->
        </div>
        <!-- /.row -->
		</form>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

<script>
$(function () {
  $('#dtif').daterangepicker({
	  startDate: '<?php echo dtshow($dti);?>',
	  endDate: '<?php echo dtshow($dtf);?>',
      locale: {
        format: 'DD/MM/YYYY'
      }
  });
  $('#formml').validate({
	submitHandler: function (form) {
		$.post('gv_mulheres.php', $('#formml').serialize(), function (data, textStatus) {
			//$('.content-wrapper').append(data);
			//$('.content-wrapper').html(data); 
		});
		alert( "Gravado com sucesso!" );
	},
    rules: {
      dti: {
        required: true
      }
    },
    messages: {
      dti: {
        required: "Esse campo é necessário"
      }
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
      error.addClass('invalid-feedback');
      element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass('is-invalid');
    }
  });
});
</script>