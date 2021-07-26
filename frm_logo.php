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
            <h1>Carregar arquivo de imagem</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active">Carregar arquivo de imagem</li>
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
                <h3 class="card-title">Upload do logotipo do município</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
                <div class="card-body">
                  <div class="form-group">
                    <label for="arquivo">Indique o arquivo</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" id="arquivo">
                        <label class="custom-file-label" for="arquivo">Selecione o arquivo de imagem jpg</label>
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
			<?php
			
				if (file_exists("plugins/scsus/temas/".$_SESSION['tema']."/img/logom_".$_SESSION['key'].".jpg")){
					echo "<img src=\"plugins/scsus/temas/".$_SESSION['tema']."/img/logom_".$_SESSION['key'].".jpg\" width=\"145\" height=\"145\" border=\"0\">";
				}
			
			?>
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
			url: 'gv_logo.php',
			type: 'post',
			data: fd,
			contentType: false,
			processData: false,
			success: function(response){
				if(response.substring(0,2) == 'Ok'){
					alert(response);
					$('#main-body').load('frm_logo.php');
				}
				else{
					alert(response);
				}
			},
		});
    });
});
</script>