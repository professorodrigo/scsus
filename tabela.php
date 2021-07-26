<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   15/07/2021
//   Rodrigo Silva
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

require_once('session.php');

$ap = isset($_GET["ap"]) ? trim($_GET["ap"]) : '';
$fcsv0 = $ap."_".$_SESSION['key'].".csv";
$fcsv = "csv/".$fcsv0;

?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Tabela CSV</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active">
                <a href="csv.php?rf=<?php echo $fcsv0;?>" class="nav-link">
                  Download
                </a>
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
                <h3 class="card-title">Dados do CSV gerado no relatório</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
<?php

$arquivo_rel = fopen($fcsv,'r');
$n_linha = 1;
$thead = '';
while(!feof($arquivo_rel)) {
	$tr = '';
	$line = fgets($arquivo_rel);
	if (strlen($line) > 0){
		$val_arr = explode(";",$line);
		if ($n_linha == 1){
			echo "<thead>";
			$tr = "<tr>";
			for($i=0;$i<count($val_arr);$i++){
				$tr .= "<th>".trim($val_arr[$i])."</th>";
			}
			$tr .= "</tr>";
			$thead = $tr;
		} else {
			$tr = "<tr>";
			for($i=0;$i<count($val_arr);$i++){
				$tr .= "<td>".trim($val_arr[$i])."</td>";
			}
			$tr .= "</tr>";
		}
	}
	echo $tr;
	if ($n_linha == 1){
		echo "</thead><tbody>";
	}
	$n_linha++;
}
echo "</tbody><tfoot>".$thead."</tfoot>";
fclose($arquivo_rel);

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