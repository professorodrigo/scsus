<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   15/07/2021
//   Rodrigo Silva
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

require_once('session.php');

$ap = isset($_GET["ap"]) ? trim($_GET["ap"]) : '';
$sb = isset($_GET["sb"]) ? trim($_GET["sb"]) : 0;
$cb = isset($_GET["cb"]) ? trim($_GET["cb"]) : 0;
$vb = isset($_GET["vb"]) ? trim($_GET["vb"]) : 0;

$fhtml = $ap."_".$_SESSION['key'].".html";
$fphp = $ap.".php";
if ($sb == 1){
	$fphp .= "?sb=1&cb=".$cb."&vb=".$vb;
}

?>
        <!-- loadingModal-->
        <div class="modal fade" data-backdrop="static" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="loadingModal_label">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="loadingModal_label">
                            <span class="glyphicon glyphicon-refresh"></span>
                            Aguarde...
                        </h5>
                    </div>
                    <div class="modal-body">
                        <div class='alert' role='alert'>
                            <center>
                                <div class="loader" id="loader"></div><br>
                                <h4><b id="loadingModal_content"></b></h4>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="main-rel">
        </div>

        <script>
            function resetModal(){
                setTimeout(function() {
                    $('#loader').removeClass();
                    $('#loader').addClass('loader');
                    $('#loadingModal_label').html('<span class="glyphicon glyphicon-refresh"></span>Aguarde...');
                    $('#loadingModal').modal('hide');
                }, 2000);
            }
            function getResponse() {
                $('#loadingModal_content').html('Carregando...');
                $('#loadingModal').modal('show');
                $.post("<?php echo $fphp;?>")
                    .done(function () {
                        $('#loader').removeClass('loader');
                        $('#loader').addClass('glyphicon glyphicon-ok');
                        $('#loadingModal_label').html('Sucesso!');
                        $('#loadingModal_content').html('<br>Relatorio gerado com sucesso!');
						resetModal();
						
						$('#main-rel').load('html/<?php echo $fhtml;?>');
                    })
                    .fail(function () {
                        $('#loader').removeClass('loader');
                        $('#loader').addClass('glyphicon glyphicon-remove');
                        $('#loadingModal_label').html('Falha!');
                        $('#loadingModal_content').html('<br>Relatorio nao gerado!');
                        resetModal();
                    });
            }
            $(function () {
                 getResponse();
            });
        </script>