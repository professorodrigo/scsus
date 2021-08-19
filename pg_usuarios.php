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

?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Usuários</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active">Todos os usuários</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card card-solid">
        <div class="card-body pb-0">
          <div class="row">
  
<?php

$result = $db->query("SELECT * FROM usuarios");
while($array = $result->fetchArray(SQLITE3_ASSOC)){
	$perfil1 = "fas fa-user";
	$perfil2 = "Usuário";
	if ($array['perfil'] == 'admin'){
		$perfil1 = "fas fa-user-tie";
		$perfil2 = "Administrador";
	}
	$img = "user1-128x128.png";
	if ($array['login'] == 'admin'){
		$img = "user2-160x160.png";
	}
	if ($array['login'] != $_SESSION['login'] && $array['login'] != 'admin'){
		echo "
				<div class=\"col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column\">
				  <div class=\"card bg-light d-flex flex-fill\">
					<div class=\"card-header text-muted border-bottom-0\">
					  ".$array['login']."
					</div>
					<div class=\"card-body pt-0\">
					  <div class=\"row\">
						<div class=\"col-7\">
						  <h2 class=\"lead\"><b>".$array['nome']."</b></h2>
						  <ul class=\"ml-4 mb-0 fa-ul text-muted\">
							<li class=\"small\"><span class=\"fa-li\"><i class=\"fas fa-hospital-alt\"></i></span> CNES ".$array['cnes']."</li>
							<li class=\"small\"><span class=\"fa-li\"><i class=\"fas fa-hospital-user\"></i></span> INE ".$array['ine']."</li>
							<li class=\"small\"><span class=\"fa-li\"><i class=\"fas fa-phone\"></i></span> ".$array['telefone']."</li>
							<li class=\"small\"><span class=\"fa-li\"><i class=\"far fa-envelope\"></i></span> ".$array['email']."</li>
						  </ul>
						</div>
						<div class=\"col-5 text-center\">
						  <img src=\"dist/img/".$img."\" alt=\"user-avatar\" class=\"img-circle img-fluid\">
						</div>
					  </div>
					</div>
					<div class=\"card-footer\">
					  <div class=\"text-right\">
						<i class=\"".$perfil1."\">&nbsp;&nbsp;".$perfil2."&nbsp;&nbsp;</i>
						<a href=\"#\" onclick=\"$('#main-body').load('frm_usuario.php?ulg=".$array['login']."');return false;\" class=\"btn btn-sm btn-primary\">
						  <i class=\"fas fa-user\"></i>  Editar
						</a>
					  </div>
					</div>
				  </div>
				</div>
		";
	}
}

?>

          </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          <nav aria-label="Contacts Page Navigation">
            <ul class="pagination justify-content-center m-0">
              <li class="page-item active"><a class="page-link" href="#">1</a></li>
            </ul>
          </nav>
        </div>
        <!-- /.card-footer -->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>

