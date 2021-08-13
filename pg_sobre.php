<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   06/08/2021
//   Rodrigo Silva
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

require_once('session.php');
require_once('sobre.php');
include('cfg/install.php');
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Sobre</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active">Sobre</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="callout callout-info">
              <h5><i class="fas fa-info"></i> <?php echo $sobre['nome'];?>:</h5>
              Script em PHP que faz a análise do banco de dados do PEC e-SUS AB e gera relatórios que auxiliam a gestão na tomada de decisões, principalmente nos indicadores do Previne Brasil. 
            </div>


            <!-- Main content -->
            <div class="invoice p-3 mb-3">
              <!-- title row -->
              <div class="row">
                <div class="col-12">
                  <h4>
                    <i class="fas fa-exclamation-circle"></i> <?php echo $sobre['nome'];?>
                    <small class="float-right">Data da última alteração: <?php echo $sobre['alteracao'];?></small>
                  </h4>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->
              <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                  Coordenação
                  <address>
                    <strong>Rodrigo Silva</strong><br>
                    +55 47 98887-2739<br>
                    professorodrigo@gmail.com<br>
					Pomerode - SC
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  Web
                  <address>
                    <button type="button" class="btn btn-block btn-outline-secondary btn-xs" onClick="javascript:window.open('<?php echo $sobre['repositorio1'];?>', '_blank');">Repositório principal</button>
                    <button type="button" class="btn btn-block btn-outline-secondary btn-xs" onClick="javascript:window.open('<?php echo $sobre['whats'];?>', '_blank');">WhatsApp</button>
                    <button type="button" class="btn btn-block btn-outline-secondary btn-xs" onClick="javascript:window.open('<?php echo $sobre['telegram'];?>', '_blank');">Telegram</button>
                    <button type="button" class="btn btn-block btn-outline-secondary btn-xs" onClick="javascript:window.open('<?php echo $sobre['canal'];?>', '_blank');">Youtube</button>
                    <button type="button" class="btn btn-block btn-outline-secondary btn-xs" onClick="javascript:window.open('<?php echo $sobre['ferramentas'];?>', '_blank');">Ferramentas</button>
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  <b>Versão: <?php echo $sobre['versao'];?></b><br>
                  <br>
				  <b>IDi:</b> <?php echo $codinstall;?><br>
                  <b>Versão do PEC:</b> <?php echo $sobre['pec'];?><br>
                  <b>Licença:</b> <?php echo $sobre['licenca'];?><br>
				  <a href="https://www.apache.org/licenses/LICENSE-2.0" target="_blank"><img src="dist/img/apache.png" width="192" height="94" border="0"></a> 
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- Table row -->
              <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table table-striped">
                    <thead>
                    <tr>
                      <th></th>
                      <th>Nome</th>
                      <th>Suporte</th>
                      <th>E-mail</th>
                      <th>Fone</th>
                    </tr>
                    </thead>
                    <tbody>
					
					<?php
					for ($i=0;$i<count($colaboradores);$i++){
						echo "
						<tr>
							<td>".($i+1)."</td>
							<td>".$colaboradores[$i]['nome']."</td>
							<td>".$colaboradores[$i]['funcao']."</td>
							<td>".$colaboradores[$i]['email']."</td>
							<td>".$colaboradores[$i]['tw']."</td>
						</tr>
						";
					}
					?>
                    </tbody>
                  </table>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <div class="row">
                <!-- accepted payments column -->
                <div class="col-6">
                  <p class="lead">Considere ajudar o projeto:</p>
                  <img src="dist/img/credit/pix.png" alt="PIX">

                  <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                    O projeto se mantém com doações. Existem diversas ferramentas pagas no mercado mas 
					estamos trabalhando por uma ferramenta livre e acessível a todos. Muitos municípios não possuem
					os recursos necessários para adquirir ferramentas caras de gestão para a atenção básica,
					mas estamos aqui, tentando lutar contra leões e precisamos de sua ajuda.
                  </p>
                </div>
                <!-- /.col -->
                <div class="col-6">
                  <p class="lead">Ajuda</p>

                  <div class="table-responsive">
                    <table class="table">
                      <tr>
                        <th style="width:50%">Como instalar o XAMMP?</th>
                        <td><a href="https://www.youtube.com/watch?v=x8FuCe9Przo" target="_blank"><img src="dist/img/youtube-iconm.png" alt="Youtube"></a></td>
                      </tr>
                      <tr>
                        <th style="width:50%">Como instalar o sc-SUS?</th>
                        <td><a href="https://www.youtube.com/watch?v=l0ovAxf5itg" target="_blank"><img src="dist/img/youtube-iconm.png" alt="Youtube"></a></td>
                      </tr>
                      <tr>
                        <th style="width:50%">Instalando o sc-SUS separado o PEC (1)</th>
                        <td><a href="https://www.youtube.com/watch?v=RHbe2Q5XlhQ" target="_blank"><img src="dist/img/youtube-iconm.png" alt="Youtube"></a></td>
                      </tr>
                      <tr>
                        <th style="width:50%">Instalando o sc-SUS separado o PEC (2)</th>
                        <td><a href="https://www.youtube.com/watch?v=49KjZ_MXrWk" target="_blank"><img src="dist/img/youtube-iconm.png" alt="Youtube"></a></td>
                      </tr>
                      <tr>
                        <th style="width:50%">Configurando o PHP.ini</th>
                        <td><a href="https://www.youtube.com/watch?v=x8FuCe9Przo&t=936s" target="_blank"><img src="dist/img/youtube-iconm.png" alt="Youtube"></a></td>
                      </tr>
                      <tr>
                        <th style="width:50%">Trocando a porta do Apache</th>
                        <td><a href="https://www.youtube.com/watch?v=-SXSB9vevf0" target="_blank"><img src="dist/img/youtube-iconm.png" alt="Youtube"></a></td>
                      </tr>
                      <tr>
                        <th style="width:50%">Instalação do SQLite</th>
                        <td><a href="https://www.youtube.com/watch?v=GHaI9wuvfT0" target="_blank"><img src="dist/img/youtube-iconm.png" alt="Youtube"></a></td>
                      </tr>
                      <tr>
                        <th style="width:50%">Como atualizar o sc-SUS?</th>
                        <td><a href="#" target="_blank"><img src="dist/img/youtube-iconm.png" alt="Youtube"></a></td>
                      </tr>
                    </table>
                  </div>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- this row will not appear when printing -->
              <div class="row no-print">
 
              </div>
            </div>
            <!-- /.invoice -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
