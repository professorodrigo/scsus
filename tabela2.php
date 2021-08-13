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

$ap = isset($_GET["ap"]) ? trim($_GET["ap"]) : '';
$db = new SQLite3('db/scsus.db');

?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Tabela <?php echo strtoupper($ap);?></h1>
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

$run_colunas = $db->query("SELECT name FROM PRAGMA_TABLE_INFO('".$ap."');");
echo "<thead><tr>";
$contc = 0;
$cabecalho = '';
while($array = $run_colunas->fetchArray(SQLITE3_ASSOC)){
	$cabecalho .= "<th>".$array['name']."</th>";
	$contc++;
}
echo $cabecalho;
echo "</tr></thead>";
echo "<tbody>";
$result = $db->query("SELECT * FROM ".$ap.";");
while($array = $result->fetchArray()){
	echo "<tr>";
	for ($i=0;$i<$contc;$i++){
		echo "<td>".$array[$i]."</td>";
	}
	echo "</tr>";
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