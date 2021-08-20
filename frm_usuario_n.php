<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   06/08/2021
//   Rodrigo Silva
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

require_once('session.php');
require_once('functions.php');

?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Novo usuário</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active">Novo usuário</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
	  <form action="gv_usuario_n.php" method="post" id="formpg" name="formpg">
        <div class="row">
          <!--/.col (left) -->
          <!-- right column -->
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Dados</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
                <div class="card-body">

                  <div class="form-group">
                    <label for="login">CPF <small>(Apenas números)</small></label>
                    <input type="text" name="login" class="form-control" id="login" data-inputmask='"mask": "99999999999"' data-mask>
                  </div>
                  <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" name="nome" class="form-control" id="nome">
                  </div>
                  <div class="form-group">
                    <label>Perfil</label>
                      <select class="form-control" name="perfil" id="perfil">
						<option value="admin">Administrador</option>
						<option value="usuario" selected>Usuário</option>
						<option value="demo">Demonstração</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="text" name="email" class="form-control" id="email">
                  </div>
                  <div class="form-group">
                    <label for="telefone">Telefone</label>
                    <input type="text" name="telefone" class="form-control" id="telefone" data-inputmask='"mask": "(99) 99999-9999"' data-mask>
                  </div>
                  <div class="form-group">
                    <label for="cnes">CNES <small>(Para permitir todos os estabelecimentos preencha como 0000000)</small></label>
                    <input type="text" name="cnes" class="form-control" id="cnes" data-inputmask='"mask": "9999999"' data-mask>
                  </div>
                  <div class="form-group">
                    <label for="ine">INE <small>(Para permitir todos todas as equipes preencha como 0000000000)</small></label>
                    <input type="text" name="ine" class="form-control" id="ine" data-inputmask='"mask": "9999999999"' data-mask>
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
  $('[data-mask]').inputmask();
  $('#formpg').validate({
	submitHandler: function (form) {
		$.post('gv_usuario_n.php', $('#formpg').serialize(), function (data, textStatus) {
			alert('Gravado com sucesso!\nSenha temporária: '+data);
			swal.fire('Ok!','Gravado com sucesso!\nSenha temporária: '+data,'success');
			$('#main-body').load('pg_usuarios.php');
		});
		
		//alert( "Gravado com sucesso!" );
	},
    rules: {
      login: {
        required: true,
		number: true,
		minlength: 11,
		maxlength: 11
      },
      email: {
        required: true,
		email: true
      },
      cnes: {
        required: true,
		number: true,
		minlength: 7,
		maxlength: 7
      },
      ine: {
        required: true,
		number: true,
		minlength: 10,
		maxlength: 10
      },
      nome: {
        required: true,
		minlength: 5
      }
    },
    messages: {
      email: {
        required: "Esse campo é necessário",
		email: "Precisa ser um e-mail válido"
      },
      login: {
        required: "Esse campo é necessário",
		number: "Precisa ser um número (CPF)",
		minlength: "No mínimo 11 dígitos",
		maxlength: "No máximo 11 dígitos"
      },
      cnes: {
        required: "Esse campo é necessário",
		number: "Precisa ser um número",
		minlength: "No mínimo 7 dígitos, preencha com zeros a esquerda",
		maxlength: "No máximo 7 dígitos"
      },
      ine: {
        required: "Esse campo é necessário",
		number: "Precisa ser um número",
		minlength: "No mínimo 10 dígitos, preencha com zeros a esquerda",
		maxlength: "No máximo 10 dígitos"
      },
      nome: {
        required: "Esse campo é necessário",
		minlength: "Nome muito curto"
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