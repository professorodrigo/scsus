<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   06/08/2021
//   Rodrigo Silva
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
ini_set('max_execution_time', 0);
ini_set("memory_limit", "-1");
header("Content-type: text/html; charset=utf-8");
require_once('session.php');
require_once('functions.php');

$db = new SQLite3('db/scsus.db');
$result = $db->query("SELECT * FROM banco WHERE id = '".$_SESSION['login']."'");
while($array = $result->fetchArray(SQLITE3_ASSOC)){
	$dbhost = $array['dbhost'];
	$dbport = $array['dbport'];
	$dbdb = $array['dbdb'];
	$dbuser = $array['dbuser'];
	$dbpass = $array['dbpass'];
}
require_once('connect.php');

$nome_rel = "IMUNOBIOLÓGICOS";
$tabela = 'tb_dim_imunobiologico';
$condicao = "st_registro_valido = 1";
$campo = array();
$campo[0][0] = 'nu_identificador';	$campo[0][1] = 'Codigo';
$campo[1][0] = 'sg_imunobiologico';	$campo[1][1] = 'Sigla';
$campo[2][0] = 'no_imunobiologico';	$campo[2][1] = 'Nome';

?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Tabela <?php echo $nome_rel;?></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active">
			  </li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Dados da tabela selecionada</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
<?php

echo "<thead><tr>";
$soma_campos = '';
$cabecalho = '';
for ($i=0;$i<count($campo);$i++){
	$soma_campos .= $campo[$i][0].',';
	$cabecalho .= "<th>".$campo[$i][1]."</th>";
}
$soma_campos = substr($soma_campos,0,-1);
echo $cabecalho;
echo "</tr></thead>";
echo "<tbody>";

$run_s1 = pg_query($cdb,"SELECT ".$soma_campos." FROM ".$tabela." WHERE ".$condicao);
$nm_sql_1 = 0;
$nm_sql_1 = pg_num_rows($run_s1);
if ($nm_sql_1 > 0){
	while ($result1 = pg_fetch_array($run_s1)){
		echo "<tr>";
			for ($i=0;$i<count($campo);$i++){
				echo "<td>".$result1[$campo[$i][0]]."</td>";
			}
		echo "</tr>";
	}
}
echo "</tbody><tfoot>".$cabecalho."</tfoot>";

?>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
<!-- Page specific script -->
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>