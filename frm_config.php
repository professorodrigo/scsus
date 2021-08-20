<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   06/08/2021
//   Rodrigo Silva
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

require_once('session.php');
require_once('functions.php');

$db = new SQLite3('db/scsus.db');
$smtp_email = '';
$smtp_usuario = '';
$smtp_senha = '';
$smtp_servidor = '';
$smtp_porta = '587';
$smtp_cript = 'tls';
$smtp_aut = 'true';
$smtp_html = 'true';
$smtp_tsen = 'false';
$result = $db->query("SELECT * FROM config WHERE id = 1");
while($array = $result->fetchArray(SQLITE3_ASSOC)){
	$smtp_email = $array['smtp_email'];
	$smtp_usuario = $array['smtp_usuario'];
	$smtp_senha = $array['smtp_senha'];
	$smtp_servidor = $array['smtp_servidor'];
	$smtp_porta = $array['smtp_porta'];
	$smtp_cript = $array['smtp_cript'];
	$smtp_html = $array['smtp_html'];
	$smtp_aut = $array['smtp_aut'];
	$smtp_tsen = $array['smtp_tsen'];
}


?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Configurações</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active">Configurações gerais</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
	  <form action="gv_config.php" method="post" id="formpg" name="formpg">
        <div class="row">
          <!--/.col (left) -->
          <!-- right column -->
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">SMTP</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
                <div class="card-body">

                  <div class="form-group">
                    <label for="smtp_email">E-mail</label>
                    <input type="text" name="smtp_email" class="form-control" id="smtp_email" value="<?php echo $smtp_email;?>">
                  </div>
                  <div class="form-group">
                    <label for="smtp_usuario">Usuário</label>
                    <input type="text" name="smtp_usuario" class="form-control" id="smtp_usuario" value="<?php echo $smtp_usuario;?>">
                  </div>
                  <div class="form-group">
                    <label for="smtp_senha">Senha</label>
                    <input type="password" name="smtp_senha" class="form-control" id="smtp_senha" value="<?php echo $smtp_senha;?>">
                  </div>
                  <div class="form-group">
                    <label for="smtp_servidor">Servidor</label>
                    <input type="text" name="smtp_servidor" class="form-control" id="smtp_servidor" value="<?php echo $smtp_servidor;?>">
                  </div>
                  <div class="form-group">
                    <label for="smtp_porta">Porta</label>
                    <input type="text" name="smtp_porta" class="form-control" id="smtp_porta" value="<?php echo $smtp_porta;?>">
                  </div>
                  <div class="form-group">
                    <label>Criptografia</label>
                      <select class="form-control" name="smtp_cript" id="smtp_cript">
						<?php
						if ($smtp_cript == 'tls'){
							echo "
							  <option value=\"tls\" selected>TLS (587)</option>
							  <option value=\"ssl\">SSL (465)</option>
							";
						} else {
							echo "
							  <option value=\"tls\">TLS (587)</option>
							  <option value=\"ssl\" selected>SSL (465)</option>
							";
						}
						?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Autenticação</label>
                      <select class="form-control" name="smtp_aut" id="smtp_aut">
						<?php
						if ($smtp_aut == 'true'){
							echo "
							  <option value=\"true\" selected>Sim</option>
							  <option value=\"false\">Não</option>
							";
						} else {
							echo "
							  <option value=\"true\">Sim</option>
							  <option value=\"false\" selected>Não</option>
							";
						}
						?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>HTML</label>
                      <select class="form-control" name="smtp_html" id="smtp_html">
						<?php
						if ($smtp_html == 'true'){
							echo "
							  <option value=\"true\" selected>Sim</option>
							  <option value=\"false\">Não</option>
							";
						} else {
							echo "
							  <option value=\"true\">Sim</option>
							  <option value=\"false\" selected>Não</option>
							";
						}
						?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Avisar por e-mail a senha</label>
                      <select class="form-control" name="smtp_tsen" id="smtp_tsen">
						<?php
						if ($smtp_tsen == 'true'){
							echo "
							  <option value=\"true\" selected>Sim</option>
							  <option value=\"false\">Não</option>
							";
						} else {
							echo "
							  <option value=\"true\">Sim</option>
							  <option value=\"false\" selected>Não</option>
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
		Você pode criar uma conta no HOTMAIL.COM (melhor que o GMAIL para essa função)<br>
		Depois configure assim:<br>
		E-mail > (emailcriado@outlook.com)<br>
		Usuário > (emailcriado@outlook.com)<br>
		Senha > (senha do e-mail)<br>
		Servidor > smtp.live.com<br>
		Porta > 587<br>
		Criptografia > TLS<br>
		Autenticação > Sim<br>
		HTML > Sim<br>
        <!-- /.row -->
		</form>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

<script>
$(function () {
  $('#formpg').validate({
	submitHandler: function (form) {
		$.post('gv_config.php', $('#formpg').serialize(), function (data, textStatus) {
			//alert('Gravado com sucesso!');
			swal.fire('Ok!','Gravado com sucesso!','success');
		});
		$('#main-body').load('frm_config.php');
	},
    rules: {
      smtp_email: {
        required: true,
		email: true
      },
      smtp_porta: {
        required: true,
		number: true
      },
      smtp_usuario: {
        required: true
      },
      smtp_servidor: {
        required: true
      },
      smtp_senha: {
        required: true
      }
    },
    messages: {
      smtp_email: {
        required: "Esse campo é necessário",
		email: "Precisa ser um e-mail válido"
      },
      smtp_porta: {
        required: "Esse campo é necessário",
		number: "Precisa ser um número"
      },
      smtp_usuario: {
        required: "Esse campo é necessário"
      },
      smtp_servidor: {
        required: "Esse campo é necessário"
      },
      smtp_senha: {
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