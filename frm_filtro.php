<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   15/07/2021
//   Rodrigo Silva
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

require_once('session.php');

$ap = isset($_GET["ap"]) ? trim($_GET["ap"]) : '';
$fphp = $ap.".php";
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Filtro</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active">Filtro</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
	  <form action="#" method="post" id="formf" name="formf">
	  <input type="hidden" id="sb" name="sb" value="1">
        <div class="row">
          <!-- left column -->
          <div class="col-md-6">
            <!-- jquery validation -->
            <div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">Filtro de busca <small>- informe os dados que necessitam procurar</small></h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
			  
                <div class="card-body">
                  <div class="form-group">
                    <label>Buscar em</label>
                      <select class="form-control" name="cb" id="cb">
						<?php
						if ($cb == 'U'){
							echo "
							  <option value=\"U\" selected>Unidade (digite o CNES ou 0 para SEM UNIDADE)</option>
							  <option value=\"E\">Equipe (digite o INE ou 0 para SEM EQUIPE)</option>
							  <option value=\"M\">Micro-Área (Número ou FA ou 0 para SEM MA)</option>
							  <option value=\"C\">CNS (apenas números)</option>
							  <option value=\"P\">CPF (apenas números)</option>
							";
						} else {
							if ($cb == 'E'){
								echo "
								  <option value=\"U\">Unidade (digite o CNES ou 0 para SEM UNIDADE)</option>
								  <option value=\"E\" selected>Equipe (digite o INE ou 0 para SEM EQUIPE)</option>
								  <option value=\"M\">Micro-Área (Número ou FA ou 0 para SEM MA)</option>
								  <option value=\"C\">CNS (apenas números)</option>
								  <option value=\"P\">CPF (apenas números)</option>
								";
							} else {
								if ($cb == 'M'){
									echo "
									  <option value=\"U\">Unidade (digite o CNES ou 0 para SEM UNIDADE)</option>
									  <option value=\"E\">Equipe (digite o INE ou 0 para SEM EQUIPE)</option>
									  <option value=\"M\" selected>Micro-Área (Número ou FA ou 0 para SEM MA)</option>
									  <option value=\"C\">CNS (apenas números)</option>
									  <option value=\"P\">CPF (apenas números)</option>
									";
								} else {
									if ($cb == 'C'){
										echo "
										  <option value=\"U\">Unidade (digite o CNES ou 0 para SEM UNIDADE)</option>
										  <option value=\"E\">Equipe (digite o INE ou 0 para SEM EQUIPE)</option>
										  <option value=\"M\">Micro-Área (Número ou FA ou 0 para SEM MA)</option>
										  <option value=\"C\" selected>CNS (apenas números)</option>
										  <option value=\"P\">CPF (apenas números)</option>
										";
									} else {
										if ($cb == 'P'){
											echo "
											  <option value=\"U\">Unidade (digite o CNES ou 0 para SEM UNIDADE)</option>
											  <option value=\"E\">Equipe (digite o INE ou 0 para SEM EQUIPE)</option>
											  <option value=\"M\">Micro-Área (Número ou FA ou 0 para SEM MA)</option>
											  <option value=\"C\">CNS (apenas números)</option>
											  <option value=\"P\" selected>CPF (apenas números)</option>
											";
										} else {
											echo "
											  <option value=\"U\" selected>Unidade (digite o CNES ou 0 para SEM UNIDADE)</option>
											  <option value=\"E\">Equipe (digite o INE ou 0 para SEM EQUIPE)</option>
											  <option value=\"M\">Micro-Área (Número ou FA ou 0 para SEM MA)</option>
											  <option value=\"C\">CNS (apenas números)</option>
											  <option value=\"P\">CPF (apenas números)</option>
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
                    <label for="vb">Valor</label>
                    <input type="text" name="vb" class="form-control" id="vb">
                  </div>

                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Gerar</button>
                </div>
            </div>
            <!-- /.card -->
            </div>
          <!--/.col (left) -->
        </div>
        <!-- /.row -->
		</form>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

<script>
$(function () {
  $('#formf').validate({
	submitHandler: function (form) {
		$('#main-body').load('prep.php?ap=<?php echo $ap;?>&sb=1&cb='+document.forms["formf"]["cb"].value+'&vb='+document.forms["formf"]["vb"].value);
	},
    rules: {
      vb: {
        required: true
      }
    },
    messages: {
      vb: {
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