<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   15/07/2021
//   Rodrigo Silva
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

require_once('session.php');
$sw_db = "?";
if (file_exists("config/banco_".$_SESSION['key'].".php")){
	require_once("config/banco_".$_SESSION['key'].".php");
	$sw_db = $dbdb;
}
?>
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" onclick="$('#main-body').load('dash_ind.php');return false;" class="nav-link">
                  <i class="fas fa-chart-bar nav-icon"></i>
                  <p>Indicadores [Geral]</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" onclick="$('#main-body').load('dash_equ.php');return false;" class="nav-link">
                  <i class="fas fa-chart-bar nav-icon"></i>
                  <p>Indicadores [Equipe]</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" onclick="$('#main-body').load('dash_uni.php');return false;" class="nav-link">
                  <i class="fas fa-chart-bar nav-icon"></i>
                  <p>Indicadores [Unidades]</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-baby-carriage"></i>
              <p>
                Gestantes
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" onclick="$('#main-body').load('prep.php?ap=rel_gestantes');return false;" class="nav-link">
                  <i class="fas fa-chart-line nav-icon text-warning"></i>
                  <p>Indicadores 1, 2 e 3 <small>[gerar]</small></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" onclick="$('#main-body').load('ver.php?ap=rel_gestantes');return false;" class="nav-link">
                  <i class="fas fa-fighter-jet nav-icon text-info"></i>
                  <p>Indicadores 1, 2 e 3</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" onclick="$('#main-body').load('frm_filtro.php?ap=rel_gestantes');return false;" class="nav-link">
                  <i class="fas fa-filter nav-icon"></i>
                  <p>Filtro</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" onclick="$('#main-body').load('frm_gestantes.php');return false;" class="nav-link">
                  <i class="fas fa-wrench nav-icon text-info"></i>
                  <p>Configurações</p>
                </a>
              </li>
			  <li class="nav-item">
				<a href="#" class="nav-link">
				  <i class="nav-icon fas fa-file-csv"></i>
				  <p>
					CSV
					<i class="right fas fa-angle-left"></i>
				  </p>
				</a>
				<ul class="nav nav-treeview">
				  <li class="nav-item">
					<a href="#" onclick="$('#main-body').load('verc.php?ap=gestantes_T');return false;" class="nav-link">
					  <i class="fas fa-file-csv nav-icon"></i>
					  <p><small>Gestantes</small></p>
					</a>
				  </li>
				  <li class="nav-item">
					<a href="#" onclick="$('#main-body').load('verc.php?ap=gestantes_C');return false;" class="nav-link">
					  <i class="fas fa-file-csv nav-icon"></i>
					  <p><small>Consultas</small></p>
					</a>
				  </li>
				  <li class="nav-item">
					<a href="#" onclick="$('#main-body').load('verc.php?ap=gestantes_P');return false;" class="nav-link">
					  <i class="fas fa-file-csv nav-icon"></i>
					  <p><small>Procedimentos</small></p>
					</a>
				  </li>
				</ul>
			  </li> 
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-female"></i>
              <p>
                Mulheres
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" onclick="$('#main-body').load('prep.php?ap=rel_mulheres');return false;" class="nav-link">
                  <i class="fas fa-chart-line nav-icon text-warning"></i>
                  <p>Indicador 4 <small>[gerar]</small></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" onclick="$('#main-body').load('ver.php?ap=rel_mulheres');return false;" class="nav-link">
                  <i class="fas fa-fighter-jet nav-icon text-info"></i>
                  <p>Indicador 4</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" onclick="$('#main-body').load('frm_filtro.php?ap=rel_mulheres');return false;" class="nav-link">
                  <i class="fas fa-filter nav-icon"></i>
                  <p>Filtro</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" onclick="$('#main-body').load('frm_mulheres.php');return false;" class="nav-link">
                  <i class="fas fa-wrench nav-icon text-info"></i>
                  <p>Configurações</p>
                </a>
              </li>
			  <li class="nav-item">
				<a href="#" class="nav-link">
				  <i class="nav-icon fas fa-file-csv"></i>
				  <p>
					CSV
					<i class="right fas fa-angle-left"></i>
				  </p>
				</a>
				<ul class="nav nav-treeview">
				  <li class="nav-item">
					<a href="#" onclick="$('#main-body').load('verc.php?ap=mulheres_T');return false;" class="nav-link">
					  <i class="fas fa-file-csv nav-icon"></i>
					  <p><small>Mulheres</small></p>
					</a>
				  </li>
				  <li class="nav-item">
					<a href="#" onclick="$('#main-body').load('verc.php?ap=mulheres_P');return false;" class="nav-link">
					  <i class="fas fa-file-csv nav-icon"></i>
					  <p><small>Procedimentos</small></p>
					</a>
				  </li>
				</ul>
			  </li> 
			  <li class="nav-item">
				<a href="#" class="nav-link">
				  <i class="fas fa-stethoscope nav-icon text-danger"></i>
				  <p>
					Exames
					<i class="right fas fa-angle-left"></i>
				  </p>
				</a>
				<ul class="nav nav-treeview">
				  <li class="nav-item">
					<a href="#" onclick="$('#main-body').load('prep.php?ap=rel_mexames');return false;" class="nav-link">
					  <i class="fas fa-stethoscope nav-icon  text-warning"></i>
					  <p>Exames <small>[gerar]</small></p>
					</a>
				  </li>
				  <li class="nav-item">
					<a href="#" onclick="$('#main-body').load('ver.php?ap=rel_mexames');return false;" class="nav-link">
					  <i class="fas fa-fighter-jet nav-icon text-info"></i>
					  <p>Exames</p>
					</a>
				  </li>
				  <li class="nav-item">
					<a href="#" onclick="$('#main-body').load('frm_filtro.php?ap=rel_mexames');return false;" class="nav-link">
					  <i class="fas fa-filter nav-icon"></i>
					  <p>Filtro</p>
					</a>
				  </li>
				  <li class="nav-item">
					<a href="#" class="nav-link">
					  <i class="nav-icon fas fa-file-csv"></i>
					  <p>
						CSV
						<i class="right fas fa-angle-left"></i>
					  </p>
					</a>
					<ul class="nav nav-treeview">
					  <li class="nav-item">
						<a href="#" onclick="$('#main-body').load('verc.php?ap=mexames_T');return false;" class="nav-link">
						  <i class="nav-icon fas fa-file-csv"></i>
						  <p><small>Mulheres</small></p>
						</a>
					  </li>
					  <li class="nav-item">
						<a href="#" onclick="$('#main-body').load('verc.php?ap=mexames_P');return false;" class="nav-link">
						  <i class="nav-icon fas fa-file-csv"></i>
						  <p><small>Procedimentos</small></p>
						</a>
					  </li>
					</ul>
				  </li>
				</ul>
			  </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-flask"></i>
              <p>
                Vacinas
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" onclick="$('#main-body').load('prep.php?ap=rel_criancas');return false;" class="nav-link">
                  <i class="fas fa-chart-line nav-icon text-warning"></i>
                  <p>Indicador 5 <small>[gerar]</small></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" onclick="$('#main-body').load('ver.php?ap=rel_criancas');return false;" class="nav-link">
                  <i class="fas fa-fighter-jet nav-icon text-info"></i>
                  <p>Indicador 5</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" onclick="$('#main-body').load('frm_filtro.php?ap=rel_criancas');return false;" class="nav-link">
                  <i class="fas fa-filter nav-icon"></i>
                  <p>Filtro</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" onclick="$('#main-body').load('frm_vacinas.php');return false;" class="nav-link">
                  <i class="fas fa-wrench nav-icon text-info"></i>
                  <p>Configurações</p>
                </a>
              </li>
			  <li class="nav-item">
				<a href="#" class="nav-link">
				  <i class="nav-icon fas fa-file-csv"></i>
				  <p>
					CSV
					<i class="right fas fa-angle-left"></i>
				  </p>
				</a>
				<ul class="nav nav-treeview">
				  <li class="nav-item">
					<a href="#" onclick="$('#main-body').load('verc.php?ap=criancas_T');return false;" class="nav-link">
					  <i class="fas fa-file-csv nav-icon"></i>
					  <p><small>Crianças</small></p>
					</a>
				  </li>
				  <li class="nav-item">
					<a href="#" onclick="$('#main-body').load('verc.php?ap=criancas_V');return false;" class="nav-link">
					  <i class="fas fa-file-csv nav-icon"></i>
					  <p><small>Vacinas</small></p>
					</a>
				  </li>
				</ul>
			  </li> 
			  <li class="nav-item">
				<a href="#" class="nav-link">
				  <i class="fas fa-syringe nav-icon text-danger"></i>
				  <p>
					Vacinas
					<i class="right fas fa-angle-left"></i>
				  </p>
				</a>
				<ul class="nav nav-treeview">
				  <li class="nav-item">
					<a href="#" onclick="$('#main-body').load('prep.php?ap=rel_vacinas');return false;" class="nav-link">
					  <i class="fas fa-syringe nav-icon  text-warning"></i>
					  <p>Vacinas <small>[gerar]</small></p>
					</a>
				  </li>
				  <li class="nav-item">
					<a href="#" onclick="$('#main-body').load('ver.php?ap=rel_vacinas');return false;" class="nav-link">
					  <i class="fas fa-fighter-jet nav-icon text-info"></i>
					  <p>Vacinas</p>
					</a>
				  </li>
				  <li class="nav-item">
					<a href="#" onclick="$('#main-body').load('frm_filtro.php?ap=rel_vacinas');return false;" class="nav-link">
					  <i class="fas fa-filter nav-icon"></i>
					  <p>Filtro</p>
					</a>
				  </li>
				  <li class="nav-item">
					<a href="#" class="nav-link">
					  <i class="nav-icon fas fa-file-csv"></i>
					  <p>
						CSV
						<i class="right fas fa-angle-left"></i>
					  </p>
					</a>
					<ul class="nav nav-treeview">
					  <li class="nav-item">
						<a href="#" onclick="$('#main-body').load('verc.php?ap=vacinados_T');return false;" class="nav-link">
						  <i class="nav-icon fas fa-file-csv"></i>
						  <p><small>Vacinados</small></p>
						</a>
					  </li>
					  <li class="nav-item">
						<a href="#" onclick="$('#main-body').load('verc.php?ap=vacinados_V');return false;" class="nav-link">
						  <i class="nav-icon fas fa-file-csv"></i>
						  <p><small>Vacinas</small></p>
						</a>
					  </li>
					</ul>
				  </li>
				</ul>
			  </li>
			  <li class="nav-item">
				<a href="#" class="nav-link">
				  <i class="nav-icon fas fa-bacterium text-danger"></i>
				  <p>
					COVID-19
					<i class="right fas fa-angle-left"></i>
				  </p>
				</a>
				<ul class="nav nav-treeview">
				  <li class="nav-item">
					<a href="#" onclick="$('#main-body').load('prep.php?ap=rel_covid1');return false;" class="nav-link">
					  <i class="fas fa-bacterium nav-icon text-warning"></i>
					  <p><small>Transparência [gerar]</small></p>
					</a>
				  </li>
				  <li class="nav-item">
					<a href="#" onclick="$('#main-body').load('ver.php?ap=rel_covid1');return false;" class="nav-link">
					  <i class="fas fa-fighter-jet nav-icon text-info"></i>
					  <p>Transparência</p>
					</a>
				  </li>
				  <li class="nav-item">
					<a href="#" onclick="$('#main-body').load('verc.php?ap=covid_T');return false;" class="nav-link">
					  <i class="fas fa-file-csv nav-icon"></i>
					  <p><small>Transparência</small></p>
					</a>
				  </li>
				</ul>
			  </li> 
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-heartbeat"></i>
              <p>
                Hipertensos
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" onclick="$('#main-body').load('prep.php?ap=rel_hipertensos');return false;" class="nav-link">
                  <i class="fas fa-chart-line nav-icon text-warning"></i>
                  <p>Indicador 6 <small>[gerar]</small></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" onclick="$('#main-body').load('ver.php?ap=rel_hipertensos');return false;" class="nav-link">
                  <i class="fas fa-fighter-jet nav-icon text-info"></i>
                  <p>Indicador 6</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" onclick="$('#main-body').load('frm_filtro.php?ap=rel_hipertensos');return false;" class="nav-link">
                  <i class="fas fa-filter nav-icon"></i>
                  <p>Filtro</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" onclick="$('#main-body').load('frm_hipertensos.php');return false;" class="nav-link">
                  <i class="fas fa-wrench nav-icon text-info"></i>
                  <p>Configurações</p>
                </a>
              </li>
			  <li class="nav-item">
				<a href="#" class="nav-link">
				  <i class="nav-icon fas fa-file-csv"></i>
				  <p>
					CSV
					<i class="right fas fa-angle-left"></i>
				  </p>
				</a>
				<ul class="nav nav-treeview">
				  <li class="nav-item">
					<a href="#" onclick="$('#main-body').load('verc.php?ap=hipertensos_T');return false;" class="nav-link">
					  <i class="fas fa-file-csv nav-icon"></i>
					  <p><small>Hipertensos</small></p>
					</a>
				  </li>
				  <li class="nav-item">
					<a href="#" onclick="$('#main-body').load('verc.php?ap=hipertensos_C');return false;" class="nav-link">
					  <i class="fas fa-file-csv nav-icon"></i>
					  <p><small>Consultas</small></p>
					</a>
				  </li>
				  <li class="nav-item">
					<a href="#" onclick="$('#main-body').load('verc.php?ap=hipertensos_P');return false;" class="nav-link">
					  <i class="fas fa-file-csv nav-icon"></i>
					  <p><small>Procedimentos</small></p>
					</a>
				  </li>
				</ul>
			  </li>  
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tint"></i>
              <p>
                Diabéticos
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" onclick="$('#main-body').load('prep.php?ap=rel_diabeticos');return false;" class="nav-link">
                  <i class="fas fa-chart-line nav-icon text-warning"></i>
                  <p>Indicador 7 <small>[gerar]</small></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" onclick="$('#main-body').load('ver.php?ap=rel_diabeticos');return false;" class="nav-link">
                  <i class="fas fa-fighter-jet nav-icon text-info"></i>
                  <p>Indicador 7</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" onclick="$('#main-body').load('frm_filtro.php?ap=rel_diabeticos');return false;" class="nav-link">
                  <i class="fas fa-filter nav-icon"></i>
                  <p>Filtro</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" onclick="$('#main-body').load('frm_diabeticos.php');return false;" class="nav-link">
                  <i class="fas fa-wrench nav-icon text-info"></i>
                  <p>Configurações</p>
                </a>
              </li>
			  <li class="nav-item">
				<a href="#" class="nav-link">
				  <i class="nav-icon fas fa-file-csv"></i>
				  <p>
					CSV
					<i class="right fas fa-angle-left"></i>
				  </p>
				</a>
				<ul class="nav nav-treeview">
				  <li class="nav-item">
					<a href="#" onclick="$('#main-body').load('verc.php?ap=diabeticos_T');return false;" class="nav-link">
					  <i class="fas fa-file-csv nav-icon"></i>
					  <p><small>Diabéticos</small></p>
					</a>
				  </li>
				  <li class="nav-item">
					<a href="#" onclick="$('#main-body').load('verc.php?ap=diabeticos_C');return false;" class="nav-link">
					  <i class="fas fa-file-csv nav-icon"></i>
					  <p><small>Consultas</small></p>
					</a>
				  </li>
				  <li class="nav-item">
					<a href="#" onclick="$('#main-body').load('verc.php?ap=diabeticos_P');return false;" class="nav-link">
					  <i class="fas fa-file-csv nav-icon"></i>
					  <p><small>Procedimentos</small></p>
					</a>
				  </li>
				</ul>
			  </li> 
            </ul>
          </li>
          <li class="nav-header">Geral</li>
          <li class="nav-item">
            <a href="#" onclick="$('#main-body').load('frm_db.php');return false;" class="nav-link">
              <i class="nav-icon fas fa-database"></i>
              <p>
                DB
                <span class="badge badge-info right"><?php echo $sw_db;?></span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" onclick="$('#main-body').load('frm_dados.php');return false;" class="nav-link">
              <i class="nav-icon fas fa-tools"></i>
              <p>
                Configurações
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" onclick="$('#main-body').load('frm_xml.php');return false;" class="nav-link">
              <i class="nav-icon fas fa-upload"></i>
              <p>
                XML CNES
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" onclick="$('#main-body').load('pg_doe.php');return false;" class="nav-link">
              <i class="nav-icon fas fa-comment-dollar"></i>
              <p>
                Doação
				<span class="right badge badge-danger">$</span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" onclick="$('#main-body').load('pg_sobre.php');return false;" class="nav-link">
              <i class="nav-icon fas fa-grin"></i>
              <p>
                Sobre
              </p>
            </a>
          </li>
          <li class="nav-header">Outros relatórios</li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-people-arrows"></i>
              <p>
                Duplicados
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" onclick="$('#main-body').load('prep.php?ap=rel_duplicados');return false;" class="nav-link">
                  <i class="fas fa-chart-line nav-icon text-warning"></i>
                  <p>Duplicados <small>[gerar]</small></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" onclick="$('#main-body').load('ver.php?ap=rel_duplicados');return false;" class="nav-link">
                  <i class="fas fa-fighter-jet nav-icon text-info"></i>
                  <p>Duplicados</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" onclick="$('#main-body').load('frm_duplicados.php');return false;" class="nav-link">
                  <i class="fas fa-wrench nav-icon text-info"></i>
                  <p>Configurações</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" onclick="$('#main-body').load('verc.php?ap=duplicados_T');return false;" class="nav-link">
                  <i class="fas fa-file-csv nav-icon"></i>
                  <p>Duplicados</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="exit.php" class="nav-link">
              <i class="nav-icon fas fa-power-off"></i>
              <p>
                SAIR
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->