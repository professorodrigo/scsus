<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   15/07/2021
//   Rodrigo Silva
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ini_set('display_errors', 0 );
error_reporting(0);

header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Content-Type: text/html;  charset=utf-8",true);
session_start();
$_SESSION = array();
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();
include('sobre.php');

if (!file_exists("db/scsus.db")){
	$db = new SQLite3('db/scsus.db');
	$estrutura = "
		CREATE TABLE usuarios (
		  login varchar(11) NOT NULL,
		  senha char(32) NOT NULL,
		  logado varchar(14) NOT NULL DEFAULT '00000000000000',
		  bloqueado int(1) NOT NULL DEFAULT 0,
		  tent int(1) NOT NULL DEFAULT 0,
		  cnes varchar(8) NOT NULL DEFAULT '00000000',
		  ine varchar(10) NOT NULL DEFAULT '0000000000',
		  tema varchar(20) NOT NULL DEFAULT 'padrao',
		  perfil varchar(20) NOT NULL DEFAULT 'indicadores',
		  PRIMARY KEY(login)
		);
	";
	$run_estrutura = $db->query($estrutura);
	$dados = "
		INSERT INTO usuarios (login, senha, logado, bloqueado, tent, cnes, ine, tema, perfil) VALUES ('admin', '202cb962ac59075b964b07152d234b70', '00000000000000', 0, 0, '00000000', '0000000000', 'padrao', 'indicadores');
	";
	$run_dados = $db->query($dados);
	$codinstall = date('dmYHis').$sobre['versimp'].rand(0,9).rand(0,9).rand(0,9);
	$texto = "<?php
	\$codinstall = '".$codinstall."';
	\$dtinstall = '".date('Ymd')."';
	?>\r\n";
	$file = "config/install.php";
	if (file_exists($file)){unlink($file);}
	$fconfig = fopen($file,'w');
	fwrite($fconfig, $texto);
	fclose($fconfig);
	$ctrli = 0;
	//include 'plugins/ctrli/index.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>sc-SUS</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="#" class="h1"><b>sc</b>-SUS</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Informe os dados para entrar no sistema</p>

      <form method="post" action="#" id="formlogin" name="formlogin">
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Usuário" name="login" id="login">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Senha" name="senha" id="senha">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="button" id="entrar" class="btn btn-primary btn-block">Entrar</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->
<div id="mensagem"></div>
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<script>
	$(function () {
		$("#entrar").click(function() {
			var mensagem = "";
			var submit = true;
			if (document.forms["formlogin"]["login"].value == ''){
				mensagem = mensagem + "Campo 'Usuário' é necessário!<br>";
				submit = false;
			}
			if (document.forms["formlogin"]["senha"].value == ''){
				mensagem = mensagem + "Campo 'Senha' é necessário!<br>";
				submit = false;
			}
			if (!submit){
				document.getElementById("mensagem").innerHTML = mensagem;
			} else {
				document.getElementById("mensagem").innerHTML = mensagem;
				$.ajax({
					url: 'check.php',
					type: 'post',
					data: $("#formlogin").serialize(),
					success: function(response){
						if (response == 'Ok'){
							top.location.href = 'principal.php';
						} else {
							document.getElementById("mensagem").innerHTML = response;
						}
					},
				});
			}
		});
		$.backstretch("img/login-bg.jpg", {
		  speed: 500
		});
	});
</script>
</body>
</html>
