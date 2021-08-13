<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   06/08/2021
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
            <h1>Carregar arquivo CBO</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active">Carregar arquivo CBO</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
	  <form action="#" method="post"  enctype="multipart/form-data" id="formf" name="formf">
	  <input type="hidden" id="sb" name="sb" value="1">
        <!-- /.row -->
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Upload do arquivo CBO</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
                <div class="card-body">
                  <div class="form-group">
                    <label for="arquivo">Indique o arquivo</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" id="arquivo">
                        <label class="custom-file-label" for="arquivo">Selecione o arquivo ZIP</label>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="button" class="btn btn-primary" id="but_upload">Enviar</button>
                </div>
            </div>
            <!-- /.card -->
			Para baixar o arquivo ESTRUTURA CBO.ZIP entre em:<br>
			http://www.mtecbo.gov.br/cbosite/pages/home.jsf<br>
			No menu lateral da esquerda escolha a opção:<br>
			* Downloads<br>
			Clique na opção:<br>
			* Estrutura CBO (CSV) - Arquivo ZIP<br>
			Prove que você não é um robô e clique em "Download"<br>
        </div>
        <!-- /.row -->
		</form>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

<script>
$(function () {
	bsCustomFileInput.init();
	$("#but_upload").click(function() {
		var fd = new FormData();
		var files = $('#arquivo')[0].files[0];
		fd.append('arquivo', files);
	
		$.ajax({
			url: 'gv_cbo.php',
			type: 'post',
			data: fd,
			contentType: false,
			processData: false,
			success: function(response){
				if(response.substring(0,2) == 'Ok'){
					alert(response);
					//$('#main-body').load('frm_cbo.php');
					$('#main-body').load('proc.php?ap=proc_cbo');
				}
				else{
					alert(response);
				}
			},
		});
    });
});
</script>