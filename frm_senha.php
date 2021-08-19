<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   06/08/2021
//   Rodrigo Silva
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

require_once('session.php');

?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Alteração da senha</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active">Senha</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
	  <form action="#" method="post" id="formpg" name="formpg">
        <!-- /.row -->
        <div class="row">
          <!-- left column -->
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Alteração da senha</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
                <div class="card-body">
                  <div class="form-group">
                    <label for="senhaa">Senha atual</label>
                    <input type="password" name="senhaa" class="form-control" id="senhaa">
                  </div>
                  <div class="form-group">
                    <label for="senhan">Nova senha</label>
                    <input type="password" name="senhan" class="form-control" id="senhan">
                  </div>
                  <div class="form-group">
                    <label for="senhar">Repita a nova senha</label>
                    <input type="password" name="senhar" class="form-control" id="senhar">
                  </div>
				</div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Alterar</button>
                </div>
            </div>
            <!-- /.card -->
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
		$.post('gv_senha.php', $('#formpg').serialize(), function (data, textStatus) {
			if(data.substring(0,2) == 'Ok'){
				$('#main-body').load('frm_perfil.php');
				alert( "Gravado com sucesso!" );
			} else {
				alert(data);
			}
		});
	},
    rules: {
      senhaa: {
        required: true
      },
      senhan: {
        required: true,
		minlength: 8,
      },
      senhar: {
        required: true,
		minlength: 8,
      },
    },
    messages: {
      senhaa: {
        required: "Esse campo é necessário"
      },
      senhan: {
        required: "Esse campo é necessário",
		minlength: "No mínimo 8 caracteres"
      },
      senhar: {
        required: "Esse campo é necessário",
		minlength: "No mínimo 8 caracteres"
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