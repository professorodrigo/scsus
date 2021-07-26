<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   15/07/2021
//   Rodrigo Silva
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

require_once('session.php');
if (file_exists("config/banco_".$_SESSION['key'].".php")){
	require_once("config/banco_".$_SESSION['key'].".php");
} else {
	$dbhost = "localhost";
	$dbport = "5433";
	$dbdb = "esus";
	$dbuser = "postgres";
	$dbpass = "esus";
}
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Banco de dados</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active">Banco de dados</li>
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
                <h3 class="card-title">Banco de dados <small>- dados para conexão</small></h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
			  <form action="gv_db.php" method="post" id="formdb" name="formdb">
                <div class="card-body">
                  <div class="form-group">
                    <label for="dbhost">Servidor</label>
                    <input type="text" name="dbhost" class="form-control" id="dbhost" placeholder="localhost" value="<?php echo $dbhost;?>">
                  </div>
                  <div class="form-group">
                    <label for="dbport">Porta</label>
                    <input type="text" name="dbport" class="form-control" id="dbport" placeholder="5433" value="<?php echo $dbport;?>">
                  </div>
				  
                  <div class="form-group">
                    <label for="dbdb">Banco</label>
                    <input type="text" name="dbdb" class="form-control" id="dbdb" placeholder="esus" value="<?php echo $dbdb;?>">
                  </div>
                  <div class="form-group">
                    <label for="dbuser">Usuário</label>
                    <input type="text" name="dbuser" class="form-control" id="dbuser" placeholder="postgres" value="<?php echo $dbuser;?>">
                  </div>
                  <div class="form-group">
                    <label for="dbpass">Senha</label>
                    <input type="password" name="dbpass" class="form-control" id="dbpass" placeholder="esus" value="<?php echo $dbpass;?>">
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Gravar</button>
                </div>
              </form>
            </div>
            <!-- /.card -->
            </div>
          <!--/.col (left) -->
          <!-- right column -->
          <div class="col-md-6">

          </div>
          <!--/.col (right) -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

<script>
$(function () {
  $('#formdb').validate({
	submitHandler: function (form) {
		$.post('gv_db.php', $('#formdb').serialize(), function (data, textStatus) {
			//$('.content-wrapper').append(data);
			//$('.content-wrapper').html(data); 
		});
		$('#main-menu').load('menu.php');
		alert( "Gravado com sucesso!" );
	},
    rules: {
      dbhost: {
        required: true
      },
      dbport: {
        required: true
      },
      dbdb: {
        required: true
      },
      dbuser: {
        required: true
      },	  
      dbpass: {
        required: true,
        minlength: 4
      },
    },
    messages: {
      dbhost: {
        required: "Entre com o IP ou hostname do servidor do banco de dados (localhost)"
      },
	  
      dbport: {
        required: "Digite a porta para o banco de dados (5433)"
      },	 
      dbdb: {
        required: "Entre com o nome da base de dados (esus)"
      },
      dbuser: {
        required: "Digite o nome de usuário para acessar ao banco (postgres)"
      },
      dbpass: {
        required: "Entre com a senha para acessaro ao banco de dados (esus)",
        minlength: "A senha deve ter no mínimo 4 caracteres"
      },
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