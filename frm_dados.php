<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   06/08/2021
//   Rodrigo Silva
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

require_once('session.php');

$db = new SQLite3('db/scsus.db');
$rows = $db->query("SELECT COUNT(*) as count FROM dados WHERE id = '".$_SESSION['login']."'");
$row = $rows->fetchArray();
if ($row['count'] > 0){
	$result = $db->query("SELECT * FROM dados WHERE id = '".$_SESSION['login']."'");
	while($array = $result->fetchArray(SQLITE3_ASSOC)){
		$cbnome = $array['cbnome'];
		$cbend1 = $array['cbend1'];
		$cbend2 = $array['cbend2'];
		$cbend3 = $array['cbend3'];
		$cbend4 = $array['cbend4'];
		$cbcont1 = $array['cbcont1'];
		$cbcont2 = $array['cbcont2'];
		$ibge = $array['ibge'];
		$popibge = $array['popibge'];
		$poppar = $array['poppar'];
		$popesf = $array['popesf'];
		$classmun = $array['classmun'];
	}
} else {
	$cbnome = "Secretaria Municipal de Saúde de Teste";
	$cbend1 = "Avenida Sete de Setembro, número 29387 - Sala 2";
	$cbend2 = "Bairro Matarazzo Caprinio";
	$cbend3 = "CEP 98732-980";
	$cbend4 = "São Matheus do Oeste Mineiro - MG";
	$cbcont1 = "+55 (47) 23432-9384 | +55 (47) 12384-3234";
	$cbcont2 = "contatosaude@saomatheus.gov.br | www.saomatheuspref.gov.br";
	$ibge = "0";
	$popibge = "0";
	$poppar = "0";
	$popesf = "0";
	$classmun = "classificacao";
}

?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Dados</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active">Dados</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- jquery validation -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Dados <small>- cabeçalho dos relatórios</small></h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
			  <form action="gv_dados.php" method="post" id="formdd" name="formdd">
                <div class="card-body">
                  <div class="form-group">
                    <label for="cbnome">Nome</label>
                    <input type="text" name="cbnome" class="form-control" id="cbnome" value="<?php echo $cbnome;?>">
                  </div>
                  <div class="form-group">
                    <label for="cbend1">Endereço linha 1</label>
                    <input type="text" name="cbend1" class="form-control" id="cbend1" value="<?php echo $cbend1;?>">
                  </div>
                  <div class="form-group">
                    <label for="cbend2">Endereço linha 2</label>
                    <input type="text" name="cbend2" class="form-control" id="cbend2" value="<?php echo $cbend2;?>">
                  </div>
                  <div class="form-group">
                    <label for="cbend3">Endereço linha 3</label>
                    <input type="text" name="cbend3" class="form-control" id="cbend3" value="<?php echo $cbend3;?>">
                  </div>
                  <div class="form-group">
                    <label for="cbend4">Município</label>
                    <input type="text" name="cbend4" class="form-control" id="cbend4" value="<?php echo $cbend4;?>">
                  </div>
                  <div class="form-group">
                    <label for="cbcont1">Contato linha 1</label>
                    <input type="text" name="cbcont1" class="form-control" id="cbcont1" value="<?php echo $cbcont1;?>">
                  </div>
                  <div class="form-group">
                    <label for="cbcont2">Contato linha 2</label>
                    <input type="text" name="cbcont2" class="form-control" id="cbcont2" value="<?php echo $cbcont2;?>">
                  </div>
				  
				  
                  <div class="form-group">
                    <label for="ibge">Número IBGE do município</label>
                    <input type="text" name="ibge" class="form-control" id="ibge" value="<?php echo $ibge;?>">
                  </div>
                  <div class="form-group">
                    <label for="popibge">População segundo o IBGE</label>
                    <input type="text" name="popibge" class="form-control" id="popibge" value="<?php echo $popibge;?>">
                  </div>
                  <div class="form-group">
                    <label for="poppar">População (Parâmetro)</label>
                    <input type="text" name="poppar" class="form-control" id="poppar" value="<?php echo $poppar;?>">
                  </div>
                  <div class="form-group">
                    <label for="popesf">População (Esforço)</label>
                    <input type="text" name="popesf" class="form-control" id="popesf" value="<?php echo $popesf;?>">
                  </div>
                  <div class="form-group">
                    <label>Classificação do município</label>
                      <select class="form-control" name="classmun" id="classmun">
						<?php
						if ($classmun == 'Urbano'){
							echo "
							  <option value=\"Urbano\" selected>Urbano</option>
							  <option value=\"IntermediarioAdjacente\">Intermediário Adjacente</option>
							  <option value=\"RuralRemoto\">Rural Remoto</option>
							  <option value=\"RuralAdjacente\">Rural Adjacente</option>
							";
						} else {
							if ($classmun == 'IntermediarioAdjacente'){
								echo "
								  <option value=\"Urbano\">Urbano</option>
								  <option value=\"IntermediarioAdjacente\" selected>Intermediário Adjacente</option>
								  <option value=\"RuralRemoto\">Rural Remoto</option>
								  <option value=\"RuralAdjacente\">Rural Adjacente</option>
								";
							} else {
								if ($classmun == 'RuralRemoto'){
									echo "
									  <option value=\"Urbano\">Urbano</option>
									  <option value=\"IntermediarioAdjacente\">Intermediário Adjacente</option>
									  <option value=\"RuralRemoto\" selected>Rural Remoto</option>
									  <option value=\"RuralAdjacente\">Rural Adjacente</option>
									";
								} else {
									if ($classmun == 'RuralAdjacente'){
										echo "
										  <option value=\"Urbano\">Urbano</option>
										  <option value=\"IntermediarioAdjacente\">Intermediário Adjacente</option>
										  <option value=\"RuralRemoto\">Rural Remoto</option>
										  <option value=\"RuralAdjacente\" selected>Rural Adjacente</option>
										";
									} else {
										echo "
										  <option value=\"Urbano\" selected>Urbano</option>
										  <option value=\"IntermediarioAdjacente\">Intermediário Adjacente</option>
										  <option value=\"RuralRemoto\">Rural Remoto</option>
										  <option value=\"RuralAdjacente\">Rural Adjacente</option>
										";
									}
								}
							}
						}
						?>
                    </select>
					Para mais informações entre no site: https://www.conasems.org.br/painel/previne-brasil-resultado-2020/<br>
					Escolha o estado e o município<br>
					Assim é possivel ter um panorama de dados do município como, por exemplo, a classificação.<br>
					Também em: https://www.conasems.org.br/painel/cadastroab/<br>
					Escolha o estado e o município.
                  </div>
				  
				  
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Gravar</button>
                </div>
              </form>
            </div>
            <!-- /.card -->
			<button type="button" class="btn btn-block bg-gradient-primary btn-sm" onClick="$('#main-body').load('frm_logo.php');return false;">Carregar logotipo do município</button><br>
            </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

<script>
$(function () {
  $('#formdd').validate({
	submitHandler: function (form) {
		$.post('gv_dados.php', $('#formdd').serialize(), function (data, textStatus) {
			//$('.content-wrapper').append(data);
			//$('.content-wrapper').html(data); 
		});
		//alert( "Gravado com sucesso!" );
		swal.fire('Ok!','Gravado com sucesso!','success');
	},
    rules: {
      cbnome: {
        required: true
      }
    },
    messages: {
      cbnome: {
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