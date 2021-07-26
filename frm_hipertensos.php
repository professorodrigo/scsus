<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   15/07/2021
//   Rodrigo Silva
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

require_once('session.php');
require_once('functions.php');
if (file_exists("config/c_rel_h_".$_SESSION['key'].".php")){
	require_once("config/c_rel_h_".$_SESSION['key'].".php");
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
	$m12 = 0;
	$des = 0;
}
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Hipertensos</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active">Hipertensos</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
	  <form action="gv_hipertensos.php" method="post" id="formhi" name="formhi">
        <div class="row">
          <!-- left column -->
          <div class="col-md-6">
            <!-- jquery validation -->
            <div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">Hipertensos <small>- dados para geração dos relatórios</small></h3>
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
                    <label>12 meses de qual data?</label>
                      <select class="form-control" name="m12" id="m12">
						<?php
						if ($m12 == 0){
							echo "
							  <option value=\"0\" selected>Data final *</option>
							  <option value=\"1\">Data inicial</option>
							";
						} else {
							echo "
							  <option value=\"0\">Data final *</option>
							  <option value=\"1\" selected>Data inicial</option>
							";
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
							";
						} else {
							if ($ordem == 'C'){
								echo "
								  <option value=\"N\">Nome</option>
								  <option value=\"C\" selected>CPF</option>
								  <option value=\"S\">CNS</option>
								";
							} else {
								if ($ordem == 'S'){
									echo "
									  <option value=\"N\">Nome</option>
									  <option value=\"C\">CPF</option>
									  <option value=\"S\" selected>CNS</option>
									";
								} else {
									echo "
									  <option value=\"N\" selected>Nome</option>
									  <option value=\"C\">CPF</option>
									  <option value=\"S\">CNS</option>
									";
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
  $('#formhi').validate({
	submitHandler: function (form) {
		$.post('gv_hipertensos.php', $('#formhi').serialize(), function (data, textStatus) {
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