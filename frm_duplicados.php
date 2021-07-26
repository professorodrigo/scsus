<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   15/07/2021
//   Rodrigo Silva
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

require_once('session.php');
require_once('functions.php');
if (file_exists("config/c_rel_du_".$_SESSION['key'].".php")){
	require_once("config/c_rel_du_".$_SESSION['key'].".php");
} else {
	$gpa = 0;
	$cfa = 0;
	$paginacao = 0;
	$ordem = 'N';
	$mcabecalho = 1;
}
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Duplicados</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active">Duplicados</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
	  <form action="gv_duplicados.php" method="post" id="formd" name="formd">
        <div class="row">
          <!--/.col (left) -->
          <!-- right column -->
          <div class="col-md-12">
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
  $('#formd').validate({
	submitHandler: function (form) {
		$.post('gv_duplicados.php', $('#formd').serialize(), function (data, textStatus) {
			//$('.content-wrapper').append(data);
			//$('.content-wrapper').html(data); 
		});
		alert( "Gravado com sucesso!" );
	},
    rules: {
      cfa: {
        required: true
      }
    },
    messages: {
      cfa: {
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