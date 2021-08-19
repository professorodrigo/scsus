<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   06/08/2021
//   Rodrigo Silva
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

require_once('session.php');
require_once('functions.php');

$ulg = isset($_GET["ulg"]) ? trim($_GET["ulg"]) : '';

$db = new SQLite3('db/scsus.db');
$rows = $db->query("SELECT COUNT(*) as count FROM usuarios WHERE login = '".$ulg."'");
$row = $rows->fetchArray();
if ($row['count'] > 0){
	$result = $db->query("SELECT * FROM usuarios WHERE login = '".$ulg."'");
	while($array = $result->fetchArray(SQLITE3_ASSOC)){
		$db_login = $array['login'];
		$db_nome = $array['nome'];
		$db_telefone = $array['telefone'];
		$db_senha = $array['senha'];
		$db_logado = $array['logado'];
		$db_bloqueado = $array['bloqueado'];
		$db_tent = $array['tent'];
		$db_cnes = $array['cnes'];
		$db_ine = $array['ine'];
		$db_tema = $array['tema'];
		$db_email = $array['email'];
		$db_perfil = $array['perfil'];
		$db_dtulogin = dtshow($array['dtulogin']);
		$db_hrulogin = hrshow($array['hrulogin']);
		$db_ctlogin = $array['ctlogin'];
		$db_usenha = $array['usenha'];
		$db_tptsenha = $array['tptsenha'];
		$db_tmpsenha = $array['tmpsenha'];
	}
} else {
	$db_login = '';
	$db_nome = '';
	$db_telefone = '';
	$db_senha = '';
	$db_logado = '';
	$db_bloqueado = '';
	$db_tent = '';
	$db_cnes = '';
	$db_ine = '';
	$db_tema = '';
	$db_email = '';
	$db_perfil = '';
	$db_dtulogin = '';
	$db_hrulogin = '';
	$db_ctlogin = '';
	$db_usenha = '';
	$db_tptsenha = '';
	$db_tmpsenha = '';
}

$img = "user1-128x128.png";
$disable = '';
if ($db_login == 'admin'){
	$img = "user2-160x160.png";
	$disable = 'disabled';
}

/*
success = verde
info = azul
warning = amarelo
danger = vermelho
*/
$show_bloqueado = "<span class=\"badge badge-success\">Não</span>";
if ($db_bloqueado != 0){
	$show_bloqueado = "<span class=\"badge badge-danger\">Sim</span>";
}
$show_logado = "<span class=\"badge badge-warning\">Não</span>";
if ($db_logado != '0'){
	$show_logado = "<span class=\"badge badge-success\">Sim</span>";
}


?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Usuário</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active">Editar usuário</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  <img class="profile-user-img img-fluid img-circle"
                       src="dist/img/<?php echo $img;?>"
                       alt="User profile picture">
                </div>
                <h3 class="profile-username text-center"><?php echo $db_nome;?></h3>
                <p class="text-muted text-center"><?php echo $db_login;?></p>
                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Número de acessos</b> <a class="float-right"><?php echo $db_ctlogin;?></a>
                  </li>
                  <li class="list-group-item">
                    <b>Data/Horário último acesso</b> <a class="float-right"><?php echo $db_dtulogin.' '.$db_hrulogin;?></a>
                  </li>
                  <li class="list-group-item">
                    <b>Perfil</b> <a class="float-right"><?php echo $db_perfil;?></a>
                  </li>
				  
                  <li class="list-group-item">
                    <b>Logado</b> <a class="float-right"><?php echo $show_logado;?></a>
                  </li>
                  <li class="list-group-item">
                    <b>Bloqueado</b> <a class="float-right"><?php echo $show_bloqueado;?></a>
                  </li>
                  <li class="list-group-item">
                    <b>Tentativas</b> <a class="float-right"><?php echo $db_tent;?></a>
                  </li>
                </ul>
				
				<?php
				if ($_SESSION['login'] != $ulg){
					echo "<a href=\"#\" onclick=\"$('#main-body').load('gv_usuario_fn.php?ulg=".$db_login."&fn=sen');return false;\" class=\"btn btn-primary btn-block\"><b>Nova senha</b></a>";
					if ($db_bloqueado == 0){
						echo "<a href=\"#\" onclick=\"$('#main-body').load('gv_usuario_fn.php?ulg=".$db_login."&fn=bloq');return false;\" class=\"btn btn-primary btn-block\"><b>Bloquear</b></a>";
					} else {
						echo "<a href=\"#\" onclick=\"$('#main-body').load('gv_usuario_fn.php?ulg=".$db_login."&fn=dblo');return false;\" class=\"btn btn-primary btn-block\"><b>Desbloquear</b></a>";
					}
					echo "<a href=\"#\" onclick=\"$('#main-body').load('gv_usuario_fn.php?ulg=".$db_login."&fn=zt');return false;\" class=\"btn btn-primary btn-block\"><b>Zerar tentativas</b></a>";
				}
				?>
				<a href="#" onclick="$('#main-body').load('gv_usuario_fn.php?ulg=<?php echo $db_login;?>&fn=dlog');return false;" class="btn btn-primary btn-block"><b>Deslogar</b></a>
				<?php
				if ($_SESSION['login'] != $ulg && $ulg != 'admin'){
					echo "<a href=\"#\" onclick=\"if(confirm('Tem certeza?'))$('#main-body').load('gv_usuario_fn.php?ulg=".$db_login."&fn=ex');\" class=\"btn btn-danger btn-block\"><b>Excluir</b></a>";
				}
				?>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
            <!-- About Me Box -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Sobre</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <strong><i class="far fa-envelope mr-1"></i> E-mail</strong>
                <p class="text-muted">
                  <?php echo $db_email;?>
                </p>
                <hr>
                <strong><i class="fas fa-phone mr-1"></i> Telefone</strong>
                <p class="text-muted"><?php echo $db_telefone;?></p>
                <hr>
                <strong><i class="fas fa-hospital-alt mr-1"></i> CNES</strong>
                <p class="text-muted"><?php echo $db_cnes;?></p>
                <hr>
                <strong><i class="fas fa-hospital-user mr-1"></i> INE</strong>
                <p class="text-muted"><?php echo $db_ine;?></p>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <div class="card card-primary">
              <div class="card-header">
				<h3 class="card-title">Dados</h3>
              </div><!-- /.card-header -->
			  <form action="gv_usuario.php" method="post" id="formpg" name="formpg">
			  <input type="hidden" id="login" name="login" value="<?php echo $db_login;?>">
              <div class="card-body">
				
                  <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" name="nome" class="form-control" id="nome" value="<?php echo $db_nome;?>">
                  </div>
                  <div class="form-group">
                    <label>Perfil</label>
                      <select class="form-control" name="perfil" id="perfil" <?php echo $disable;?>>
						<?php
						if ($db_perfil == 'admin'){
							echo "
							  <option value=\"admin\" selected>Administrador</option>
							  <option value=\"usuario\">Usuário</option>
							";
						} else {
							echo "
							  <option value=\"admin\">Administrador</option>
							  <option value=\"usuario\" selected>Usuário</option>
							";
						}
						?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="text" name="email" class="form-control" id="email" value="<?php echo $db_email;?>">
                  </div>
                  <div class="form-group">
                    <label for="telefone">Telefone</label>
                    <input type="text" name="telefone" class="form-control" id="telefone" value="<?php echo $db_telefone;?>" data-inputmask='"mask": "(99) 99999-9999"' data-mask>
                  </div>
                  <div class="form-group">
                    <label for="cnes">CNES</label>
                    <input type="text" name="cnes" class="form-control" id="cnes" value="<?php echo $db_cnes;?>" <?php echo $disable;?> data-inputmask='"mask": "9999999"' data-mask>
                  </div>
                  <div class="form-group">
                    <label for="ine">INE</label>
                    <input type="text" name="ine" class="form-control" id="ine" value="<?php echo $db_ine;?>" <?php echo $disable;?> data-inputmask='"mask": "9999999999"' data-mask>
                  </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Gravar</button>
                </div>
				</form>
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
<script>
$(function () {
  $('[data-mask]').inputmask();
  $('#formpg').validate({
	submitHandler: function (form) {
		$.post('gv_usuario.php', $('#formpg').serialize(), function (data, textStatus) {
			//$('.content-wrapper').append(data);
			//$('.content-wrapper').html(data); 
		});
		$('#main-body').load('frm_usuario.php?ulg=<?php echo $ulg;?>');
		alert( "Gravado com sucesso!" );
		
	},
    rules: {
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
      cnes: {
        required: "Esse campo é necessário",
		number: "Precisa ser um número",
		minlength: "No mínimo 7 dígitos, preencha com zeros a esquerda",
		maxlength: "No máximo 7 dígitos"
      },
      cnes: {
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
