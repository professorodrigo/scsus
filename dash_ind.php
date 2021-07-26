<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   15/07/2021
//   Rodrigo Silva
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

require_once('session.php');
require_once('functions.php');
require_once("config/dados_".$_SESSION['key'].".php");

$dash_ind1 = "";
$graf_ind1 = "";
$tab_ind1 = "";
if (file_exists("resumo/r_ind1_".$_SESSION['key'].".php")){
	require_once("resumo/r_ind1_".$_SESSION['key'].".php");
	$cor1 = "bg-danger";
	$cor11 = "#DC3545"; // vermelho
	if (ceil($num_prc_1) >= 16 && ceil($num_prc_1) < 28){
		$cor1 = "bg-warning";
		$cor11 = "#FFC107"; // amarelo
	}
	if (ceil($num_prc_1) >= 28 && ceil($num_prc_1) < 40){
		$cor1 = "bg-success";
		$cor11 = "#28A745"; // verde
	}
	if (ceil($num_prc_1) > 40){
		$cor1 = "bg-info";
		$cor11 = "#17A2B8"; // azul
	}
	$dash_ind1 = "	
          <div class=\"col-md-3 col-sm-6 col-12\">
            <div class=\"info-box ".$cor1."\">
              <span class=\"info-box-icon\"><i class=\"fas fa-baby-carriage\"></i></span>
              <div class=\"info-box-content\">
                <span class=\"info-box-text\">Indicador 1</span>Proporção de gestantes com pelo menos 6 (seis) consultas pré-natal realizadas, sendo a primeira até a 20ª semana de gestação
                <span class=\"info-box-number\">".$num_1." [".ceil($num_prc_1)."%]</span>
                <div class=\"progress\">
                  <div class=\"progress-bar\" style=\"width: ".ceil($num_prc_1)."%\"></div>
                </div>
                <span class=\"progress-description\">
                  ".$den_ind_1."
                </span>
              </div>
            </div>
          </div>
	";
	$graf_ind1 = "
	  <div class=\"col-6 col-md-3 text-center\">
		<input type=\"text\" class=\"knob\" data-thickness=\"0.2\" data-angleArc=\"250\" data-angleOffset=\"-125\"
				value=\"".ceil($num_prc_1)."\" data-width=\"120\" data-height=\"120\" data-fgColor=\"".$cor11."\" readonly>
		<div class=\"knob-label\">Indicador 1</div>
	  </div>
	";
	$tab_ind1 = "
	<div class=\"col-md-3\">
		<div class=\"card\">
		<div class=\"card-header\">
			<h3 class=\"card-title\">Indicador 1</h3>
		</div>
		<div class=\"card-body p-0\">
			<table class=\"table table-striped\">
			<thead>
				<tr>
				<th style=\"width: 10px\">Valor</th>
				<th>Dado</th>
				<th style=\"width: 40px\"></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><span class=\"badge bg-success\">".$den_ind_1."</span></td>
					<td>Denominador</td>
					<td></td>
				</tr>
				<tr>
					<td><span class=\"badge bg-success\">".$den_est_1."</span></td>
					<td>Denominador estimado</td>
					<td><span class=\"badge ".$cor1."\">".ceil($num_prc_est_1)."%</span></td>
				</tr>
				<tr>
					<td><span class=\"badge bg-success\">".$num_1."</span></td>
					<td>Numerador</td>
					<td><span class=\"badge ".$cor1."\">".ceil($num_prc_1)."%</span></td>
				</tr>
				<tr>
					<td><span class=\"badge bg-success\">".$c_interr_1."</span></td>
					<td>Gestantes finalizadas no Q.</td>
					<td></td>
				</tr>
				<tr>
					<td><span class=\"badge bg-success\">".$c_interr_a_1."</span></td>
					<td colspan=\"2\">Gestantes finalizadas antes do Q.</td>
				</tr>
				<tr>
					<td><span class=\"badge bg-success\">".$c_hiper_1."</span></td>
					<td>Marcadas como hipertensas</td>
					<td><i class=\"fas fa-heartbeat nav-icon\"></i></td>
				</tr>
				<tr>
					<td><span class=\"badge bg-success\">".$c_diabe_1."</span></td>
					<td>Marcadas como diabéticas</td>
					<td><i class=\"fas fa-tint nav-icon\"></i></td>
				</tr>
				<tr>
					<td></td>
					<td>Data inicial</td>
					<td>".dtshow($dti_1)."</td>
				</tr>
				<tr>
					<td></td>
					<td>Data final</td>
					<td>".dtshow($dtf_1)."</td>
				</tr>
				<tr>
					<td></td>
					<td>Quadrimestre</td>
					<td>".$quadri_1."</td>
				</tr>
				<tr>
					<td>".$versao_1."</td>
					<td>".$datahora_1."</td>
					<td>".$banco_1."</td>
				</tr>
			</tbody>
			</table>
		</div>
		</div>
	</div>
	";
}
//****************************************************************************************************************
//****************************************************************************************************************
//****************************************************************************************************************
//****************************************************************************************************************

$dash_ind2 = "";
$graf_ind2 = "";
$tab_ind2 = "";
if (file_exists("resumo/r_ind2_".$_SESSION['key'].".php")){
	require_once("resumo/r_ind2_".$_SESSION['key'].".php");
	$cor2 = "bg-danger";
	$cor22 = "#DC3545";
	if (ceil($num_prc_2) >= 16 && ceil($num_prc_2) < 28){
		$cor2 = "bg-warning";
		$cor22 = "#FFC107";
	}
	if (ceil($num_prc_2) >= 28 && ceil($num_prc_2) < 40){
		$cor2 = "bg-success";
		$cor22 = "#28A745";
	}
	if (ceil($num_prc_2) > 40){
		$cor2 = "bg-info";
		$cor22 = "#17A2B8";
	}
	$dash_ind2 = "	
          <div class=\"col-md-3 col-sm-6 col-12\">
            <div class=\"info-box ".$cor2."\">
              <span class=\"info-box-icon\"><i class=\"fas fa-baby-carriage\"></i></span>
              <div class=\"info-box-content\">
                <span class=\"info-box-text\">Indicador 2</span>Proporção de gestantes com realização de exames para sífilis e HIV
                <span class=\"info-box-number\">".$num_2." [".ceil($num_prc_2)."%]</span>
                <div class=\"progress\">
                  <div class=\"progress-bar\" style=\"width: ".ceil($num_prc_2)."%\"></div>
                </div>
                <span class=\"progress-description\">
                  ".$den_ind_2."
                </span>
              </div>
            </div>
          </div>
	";
	$graf_ind2 = "
	  <div class=\"col-6 col-md-3 text-center\">
		<input type=\"text\" class=\"knob\" data-thickness=\"0.2\" data-angleArc=\"250\" data-angleOffset=\"-125\"
				value=\"".ceil($num_prc_2)."\" data-width=\"120\" data-height=\"120\" data-fgColor=\"".$cor22."\" readonly>
		<div class=\"knob-label\">Indicador 2</div>
	  </div>
	";
	$tab_ind2 = "
	<div class=\"col-md-3\">
		<div class=\"card\">
		<div class=\"card-header\">
			<h3 class=\"card-title\">Indicador 2</h3>
		</div>
		<div class=\"card-body p-0\">
			<table class=\"table table-striped\">
			<thead>
				<tr>
				<th style=\"width: 10px\">Valor</th>
				<th>Dado</th>
				<th style=\"width: 40px\"></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><span class=\"badge bg-success\">".$den_ind_2."</span></td>
					<td>Denominador</td>
					<td></td>
				</tr>
				<tr>
					<td><span class=\"badge bg-success\">".$den_est_2."</span></td>
					<td>Denominador estimado</td>
					<td><span class=\"badge ".$cor2."\">".ceil($num_prc_est_2)."%</span></td>
				</tr>
				<tr>
					<td><span class=\"badge bg-success\">".$num_2."</span></td>
					<td>Numerador</td>
					<td><span class=\"badge ".$cor2."\">".ceil($num_prc_2)."%</span></td>
				</tr>
				<tr>
					<td><span class=\"badge bg-success\">".$c_interr_2."</span></td>
					<td>Gestantes finalizadas no Q.</td>
					<td></td>
				</tr>
				<tr>
					<td><span class=\"badge bg-success\">".$c_interr_a_2."</span></td>
					<td colspan=\"2\">Gestantes finalizadas antes do Q.</td>
				</tr>
				<tr>
					<td><span class=\"badge bg-success\">".$c_hiper_2."</span></td>
					<td>Marcadas como hipertensas</td>
					<td><i class=\"fas fa-heartbeat nav-icon\"></i></td>
				</tr>
				<tr>
					<td><span class=\"badge bg-success\">".$c_diabe_2."</span></td>
					<td>Marcadas como diabéticas</td>
					<td><i class=\"fas fa-tint nav-icon\"></i></td>
				</tr>
				<tr>
					<td></td>
					<td>Data inicial</td>
					<td>".dtshow($dti_2)."</td>
				</tr>
				<tr>
					<td></td>
					<td>Data final</td>
					<td>".dtshow($dtf_2)."</td>
				</tr>
				<tr>
					<td></td>
					<td>Quadrimestre</td>
					<td>".$quadri_2."</td>
				</tr>
				<tr>
					<td>".$versao_2."</td>
					<td>".$datahora_2."</td>
					<td>".$banco_2."</td>
				</tr>
			</tbody>
			</table>
		</div>
		</div>
	</div>
	";
}
//****************************************************************************************************************
//****************************************************************************************************************
//****************************************************************************************************************
//****************************************************************************************************************
$dash_ind3 = "";
$graf_ind3 = "";
$tab_ind3 = "";
if (file_exists("resumo/r_ind3_".$_SESSION['key'].".php")){
	require_once("resumo/r_ind3_".$_SESSION['key'].".php");
	$cor3 = "bg-danger";
	$cor33 = "#DC3545";
	if (ceil($num_prc_3) >= 16 && ceil($num_prc_3) < 28){
		$cor3 = "bg-warning";
		$cor33 = "#FFC107";
	}
	if (ceil($num_prc_3) >= 28 && ceil($num_prc_3) < 40){
		$cor3 = "bg-success";
		$cor33 = "#28A745";
	}
	if (ceil($num_prc_3) >40){
		$cor3 = "bg-info";
		$cor33 = "#17A2B8";
	}
	$dash_ind3 = "	
          <div class=\"col-md-3 col-sm-6 col-12\">
            <div class=\"info-box ".$cor3."\">
              <span class=\"info-box-icon\"><i class=\"fas fa-baby-carriage\"></i></span>
              <div class=\"info-box-content\">
                <span class=\"info-box-text\">Indicador 3</span>Proporção de gestantes com atendimento odontológico realizado
                <span class=\"info-box-number\">".$num_3." [".ceil($num_prc_3)."%]</span>
                <div class=\"progress\">
                  <div class=\"progress-bar\" style=\"width: ".ceil($num_prc_3)."%\"></div>
                </div>
                <span class=\"progress-description\">
                  ".$den_ind_3."
                </span>
              </div>
            </div>
          </div>
	";
	$graf_ind3 = "
	  <div class=\"col-6 col-md-3 text-center\">
		<input type=\"text\" class=\"knob\" data-thickness=\"0.2\" data-angleArc=\"250\" data-angleOffset=\"-125\"
				value=\"".ceil($num_prc_3)."\" data-width=\"120\" data-height=\"120\" data-fgColor=\"".$cor33."\" readonly>
		<div class=\"knob-label\">Indicador 3</div>
	  </div>
	";
	$tab_ind3 = "
	<div class=\"col-md-3\">
		<div class=\"card\">
		<div class=\"card-header\">
			<h3 class=\"card-title\">Indicador 3</h3>
		</div>
		<div class=\"card-body p-0\">
			<table class=\"table table-striped\">
			<thead>
				<tr>
				<th style=\"width: 10px\">Valor</th>
				<th>Dado</th>
				<th style=\"width: 40px\"></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><span class=\"badge bg-success\">".$den_ind_3."</span></td>
					<td>Denominador</td>
					<td></td>
				</tr>
				<tr>
					<td><span class=\"badge bg-success\">".$den_est_3."</span></td>
					<td>Denominador estimado</td>
					<td><span class=\"badge ".$cor3."\">".ceil($num_prc_est_3)."%</span></td>
				</tr>
				<tr>
					<td><span class=\"badge bg-success\">".$num_3."</span></td>
					<td>Numerador</td>
					<td><span class=\"badge ".$cor3."\">".ceil($num_prc_3)."%</span></td>
				</tr>
				<tr>
					<td><span class=\"badge bg-success\">".$c_hiper_3."</span></td>
					<td>Marcadas como hipertensas</td>
					<td><i class=\"fas fa-heartbeat nav-icon\"></i></td>
				</tr>
				<tr>
					<td><span class=\"badge bg-success\">".$c_diabe_3."</span></td>
					<td>Marcadas como diabéticas</td>
					<td><i class=\"fas fa-tint nav-icon\"></i></td>
				</tr>
				<tr>
					<td></td>
					<td>Data inicial</td>
					<td>".dtshow($dti_3)."</td>
				</tr>
				<tr>
					<td></td>
					<td>Data final</td>
					<td>".dtshow($dtf_3)."</td>
				</tr>
				<tr>
					<td></td>
					<td>Quadrimestre</td>
					<td>".$quadri_3."</td>
				</tr>
				<tr>
					<td>".$versao_3."</td>
					<td>".$datahora_3."</td>
					<td>".$banco_3."</td>
				</tr>
			</tbody>
			</table>
		</div>
		</div>
	</div>
	";
}
//****************************************************************************************************************
//****************************************************************************************************************
//****************************************************************************************************************
//****************************************************************************************************************
$dash_ind4 = "";
$graf_ind4 = "";
$tab_ind4 = "";
if (file_exists("resumo/r_ind4_".$_SESSION['key'].".php")){
	require_once("resumo/r_ind4_".$_SESSION['key'].".php");
	$cor4 = "bg-danger";
	$cor44 = "#DC3545";
	if (ceil($num_prc_4) >= 16 && ceil($num_prc_4) < 28){
		$cor4 = "bg-warning";
		$cor44 = "#FFC107";
	}
	if (ceil($num_prc_4) >= 28 && ceil($num_prc_4) < 40){
		$cor4 = "bg-success";
		$cor44 = "#28A745";
	}
	if (ceil($num_prc_4) > 40){
		$cor4 = "bg-info";
		$cor44 = "#17A2B8";
	}
	$dash_ind4 = "	
          <div class=\"col-md-3 col-sm-6 col-12\">
            <div class=\"info-box ".$cor4."\">
              <span class=\"info-box-icon\"><i class=\"fas fa-female\"></i></span>
              <div class=\"info-box-content\">
                <span class=\"info-box-text\">Indicador 4</span>Cobertura de exame citopatológico
                <span class=\"info-box-number\">".$num_4." [".ceil($num_prc_4)."%]</span>
                <div class=\"progress\">
                  <div class=\"progress-bar\" style=\"width: ".ceil($num_prc_4)."%\"></div>
                </div>
                <span class=\"progress-description\">
                  ".$den_ind_4."
                </span>
              </div>
            </div>
          </div>
	";
	$graf_ind4 = "
	  <div class=\"col-6 col-md-3 text-center\">
		<input type=\"text\" class=\"knob\" data-thickness=\"0.2\" data-angleArc=\"250\" data-angleOffset=\"-125\"
				value=\"".ceil($num_prc_4)."\" data-width=\"120\" data-height=\"120\" data-fgColor=\"".$cor44."\" readonly>
		<div class=\"knob-label\">Indicador 4</div>
	  </div>
	";
	$tab_ind4 = "
	<div class=\"col-md-3\">
		<div class=\"card\">
		<div class=\"card-header\">
			<h3 class=\"card-title\">Indicador 4</h3>
		</div>
		<div class=\"card-body p-0\">
			<table class=\"table table-striped\">
			<thead>
				<tr>
				<th style=\"width: 10px\">Valor</th>
				<th>Dado</th>
				<th style=\"width: 40px\"></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><span class=\"badge bg-success\">".$den_ind_4."</span></td>
					<td>Denominador</td>
					<td></td>
				</tr>
				<tr>
					<td><span class=\"badge bg-success\">".$den_est_4."</span></td>
					<td>Denominador estimado</td>
					<td><span class=\"badge ".$cor4."\">".ceil($num_prc_est_4)."%</span></td>
				</tr>
				<tr>
					<td><span class=\"badge bg-success\">".$num_4."</span></td>
					<td>Numerador</td>
					<td><span class=\"badge ".$cor4."\">".ceil($num_prc_4)."%</span></td>
				</tr>
				<tr>
					<td><span class=\"badge bg-success\">".$c_hiper_4."</span></td>
					<td>Marcadas como hipertensas</td>
					<td><i class=\"fas fa-heartbeat nav-icon\"></i></td>
				</tr>
				<tr>
					<td><span class=\"badge bg-success\">".$c_diabe_4."</span></td>
					<td>Marcadas como diabéticas</td>
					<td><i class=\"fas fa-tint nav-icon\"></i></td>
				</tr>
				<tr>
					<td></td>
					<td>Data inicial</td>
					<td>".dtshow($dti_4)."</td>
				</tr>
				<tr>
					<td></td>
					<td>Data final</td>
					<td>".dtshow($dtf_4)."</td>
				</tr>
				<tr>
					<td></td>
					<td>Quadrimestre</td>
					<td>".$quadri_4."</td>
				</tr>
				<tr>
					<td>".$versao_4."</td>
					<td>".$datahora_4."</td>
					<td>".$banco_4."</td>
				</tr>
			</tbody>
			</table>
		</div>
		</div>
	</div>
	";
}
//****************************************************************************************************************
//****************************************************************************************************************
//****************************************************************************************************************
//****************************************************************************************************************
$dash_ind5 = "";
$graf_ind5 = "";
$tab_ind5 = "";
if (file_exists("resumo/r_ind5_".$_SESSION['key'].".php")){
	require_once("resumo/r_ind5_".$_SESSION['key'].".php");
	$cor5 = "bg-danger";
	$cor55 = "#DC3545";
	if (ceil($num_prc_5) >= 16 && ceil($num_prc_5) < 28){
		$cor5 = "bg-warning";
		$cor55 = "#FFC107";
	}
	if (ceil($num_prc_5) >= 28 && ceil($num_prc_5) < 40){
		$cor5 = "bg-success";
		$cor55 = "#28A745";
	}
	if (ceil($num_prc_5) > 40){
		$cor5 = "bg-info";
		$cor55 = "#17A2B8";
	}
	$dash_ind5 = "	
          <div class=\"col-md-3 col-sm-6 col-12\">
            <div class=\"info-box ".$cor5."\">
              <span class=\"info-box-icon\"><i class=\"fas fa-flask\"></i></span>
              <div class=\"info-box-content\">
                <span class=\"info-box-text\">Indicador 5</span>Cobertura vacinal de Poliomielite inativada e de Pentavalente
                <span class=\"info-box-number\">".$num_5." [".ceil($num_prc_5)."%]</span>
                <div class=\"progress\">
                  <div class=\"progress-bar\" style=\"width: ".ceil($num_prc_5)."%\"></div>
                </div>
                <span class=\"progress-description\">
                  ".$den_ind_5."
                </span>
              </div>
            </div>
          </div>
	";
	$graf_ind5 = "
	  <div class=\"col-6 col-md-3 text-center\">
		<input type=\"text\" class=\"knob\" data-thickness=\"0.2\" data-angleArc=\"250\" data-angleOffset=\"-125\"
				value=\"".ceil($num_prc_5)."\" data-width=\"120\" data-height=\"120\" data-fgColor=\"".$cor55."\" readonly>
		<div class=\"knob-label\">Indicador 5</div>
	  </div>
	";
	$tab_ind5 = "
	<div class=\"col-md-3\">
		<div class=\"card\">
		<div class=\"card-header\">
			<h3 class=\"card-title\">Indicador 5</h3>
		</div>
		<div class=\"card-body p-0\">
			<table class=\"table table-striped\">
			<thead>
				<tr>
				<th style=\"width: 10px\">Valor</th>
				<th>Dado</th>
				<th style=\"width: 40px\"></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><span class=\"badge bg-success\">".$den_ind_5."</span></td>
					<td>Denominador</td>
					<td></td>
				</tr>
				<tr>
					<td><span class=\"badge bg-success\">".$den_est_5."</span></td>
					<td>Denominador estimado</td>
					<td><span class=\"badge ".$cor5."\">".ceil($num_prc_est_5)."%</span></td>
				</tr>
				<tr>
					<td><span class=\"badge bg-success\">".$num_5."</span></td>
					<td>Numerador</td>
					<td><span class=\"badge ".$cor5."\">".ceil($num_prc_5)."%</span></td>
				</tr>
				<tr>
					<td><span class=\"badge bg-success\">".$c_hiper_5."</span></td>
					<td>Marcadas como hipertensas</td>
					<td><i class=\"fas fa-heartbeat nav-icon\"></i></td>
				</tr>
				<tr>
					<td><span class=\"badge bg-success\">".$c_diabe_5."</span></td>
					<td>Marcadas como diabéticas</td>
					<td><i class=\"fas fa-tint nav-icon\"></i></td>
				</tr>
				<tr>
					<td></td>
					<td>Data inicial</td>
					<td>".dtshow($dti_5)."</td>
				</tr>
				<tr>
					<td></td>
					<td>Data final</td>
					<td>".dtshow($dtf_5)."</td>
				</tr>
				<tr>
					<td></td>
					<td>Quadrimestre</td>
					<td>".$quadri_5."</td>
				</tr>
				<tr>
					<td>".$versao_5."</td>
					<td>".$datahora_5."</td>
					<td>".$banco_5."</td>
				</tr>
			</tbody>
			</table>
		</div>
		</div>
	</div>
	";
}
//****************************************************************************************************************
//****************************************************************************************************************
//****************************************************************************************************************
//****************************************************************************************************************
$dash_ind6 = "";
$graf_ind6 = "";
$tab_ind6 = "";
if (file_exists("resumo/r_ind6_".$_SESSION['key'].".php")){
	require_once("resumo/r_ind6_".$_SESSION['key'].".php");
	$cor6 = "bg-danger";
	$cor66 = "#DC3545";
	if (ceil($num_prc_6) >= 16 && ceil($num_prc_6) < 28){
		$cor6 = "bg-warning";
		$cor66 = "#FFC107";
	}
	if (ceil($num_prc_6) >= 28 && ceil($num_prc_6) < 40){
		$cor6 = "bg-success";
		$cor66 = "#28A745";
	}
	if (ceil($num_prc_6) > 40){
		$cor6 = "bg-info";
		$cor66 = "#17A2B8";
	}
	$dash_ind6 = "	
          <div class=\"col-md-3 col-sm-6 col-12\">
            <div class=\"info-box ".$cor6."\">
              <span class=\"info-box-icon\"><i class=\"fas fa-heartbeat\"></i></span>
              <div class=\"info-box-content\">
                <span class=\"info-box-text\">Indicador 6</span>Percentual de pessoas hipertensas com Pressão Arterial aferida em cada semestre
                <span class=\"info-box-number\">".$num_6." [".ceil($num_prc_6)."%]</span>
                <div class=\"progress\">
                  <div class=\"progress-bar\" style=\"width: ".ceil($num_prc_6)."%\"></div>
                </div>
                <span class=\"progress-description\">
                  ".$den_ind_6."
                </span>
              </div>
            </div>
          </div>
	";
	$graf_ind6 = "
	  <div class=\"col-6 col-md-3 text-center\">
		<input type=\"text\" class=\"knob\" data-thickness=\"0.2\" data-angleArc=\"250\" data-angleOffset=\"-125\"
				value=\"".ceil($num_prc_6)."\" data-width=\"120\" data-height=\"120\" data-fgColor=\"".$cor66."\" readonly>
		<div class=\"knob-label\">Indicador 6</div>
	  </div>
	";
	$tab_ind6 = "
	<div class=\"col-md-3\">
		<div class=\"card\">
		<div class=\"card-header\">
			<h3 class=\"card-title\">Indicador 6</h3>
		</div>
		<div class=\"card-body p-0\">
			<table class=\"table table-striped\">
			<thead>
				<tr>
				<th style=\"width: 10px\">Valor</th>
				<th>Dado</th>
				<th style=\"width: 40px\"></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><span class=\"badge bg-success\">".$den_ind_6."</span></td>
					<td>Denominador</td>
					<td></td>
				</tr>
				<tr>
					<td><span class=\"badge bg-success\">".$den_est_6."</span></td>
					<td>Denominador estimado</td>
					<td><span class=\"badge ".$cor6."\">".ceil($num_prc_est_6)."%</span></td>
				</tr>
				<tr>
					<td><span class=\"badge bg-success\">".$num_6."</span></td>
					<td>Numerador</td>
					<td><span class=\"badge ".$cor6."\">".ceil($num_prc_6)."%</span></td>
				</tr>
				<tr>
					<td><span class=\"badge bg-success\">".$c_gest_6."</span></td>
					<td>Marcadas como gestantes</td>
					<td><i class=\"fas fa-baby-carriage nav-icon\"></i></td>
				</tr>
				<tr>
					<td><span class=\"badge bg-success\">".$c_diabe_6."</span></td>
					<td>Marcadas como diabéticas</td>
					<td><i class=\"fas fa-tint nav-icon\"></i></td>
				</tr>
				<tr>
					<td></td>
					<td>Data inicial</td>
					<td>".dtshow($dti_6)."</td>
				</tr>
				<tr>
					<td></td>
					<td>Data final</td>
					<td>".dtshow($dtf_6)."</td>
				</tr>
				<tr>
					<td></td>
					<td>Quadrimestre</td>
					<td>".$quadri_6."</td>
				</tr>
				<tr>
					<td>".$versao_6."</td>
					<td>".$datahora_6."</td>
					<td>".$banco_6."</td>
				</tr>
			</tbody>
			</table>
		</div>
		</div>
	</div>
	";
}
//****************************************************************************************************************
//****************************************************************************************************************
//****************************************************************************************************************
//****************************************************************************************************************
$dash_ind7 = "";
$graf_ind7 = "";
$tab_ind7 = "";
if (file_exists("resumo/r_ind7_".$_SESSION['key'].".php")){
	require_once("resumo/r_ind7_".$_SESSION['key'].".php");
	$cor7 = "bg-danger";
	$cor77 = "#DC3545";
	if (ceil($num_prc_7) >= 16 && ceil($num_prc_7) < 28){
		$cor7 = "bg-warning";
		$cor77 = "#FFC107";
	}
	if (ceil($num_prc_7) >= 28 && ceil($num_prc_7) < 40){
		$cor7 = "bg-success";
		$cor77 = "#28A745";
	}
	if (ceil($num_prc_7) > 40){
		$cor7 = "bg-info";
		$cor77 = "#17A2B8";
	}
	$dash_ind7 = "	
          <div class=\"col-md-3 col-sm-6 col-12\">
            <div class=\"info-box ".$cor7."\">
              <span class=\"info-box-icon\"><i class=\"fas fa-tint\"></i></span>
              <div class=\"info-box-content\">
                <span class=\"info-box-text\">Indicador 7</span>Percentual de diabéticos com solicitação de hemoglobina glicada
                <span class=\"info-box-number\">".$num_7." [".ceil($num_prc_7)."%]</span>
                <div class=\"progress\">
                  <div class=\"progress-bar\" style=\"width: ".ceil($num_prc_7)."%\"></div>
                </div>
                <span class=\"progress-description\">
                  ".$den_ind_7."
                </span>
              </div>
            </div>
          </div>
	";
	$graf_ind7 = "
	  <div class=\"col-6 col-md-3 text-center\">
		<input type=\"text\" class=\"knob\" data-thickness=\"0.2\" data-angleArc=\"250\" data-angleOffset=\"-125\"
				value=\"".ceil($num_prc_7)."\" data-width=\"120\" data-height=\"120\" data-fgColor=\"".$cor77."\" readonly>
		<div class=\"knob-label\">Indicador 7</div>
	  </div>
	";
	$tab_ind7 = "
	<div class=\"col-md-3\">
		<div class=\"card\">
		<div class=\"card-header\">
			<h3 class=\"card-title\">Indicador 7</h3>
		</div>
		<div class=\"card-body p-0\">
			<table class=\"table table-striped\">
			<thead>
				<tr>
				<th style=\"width: 10px\">Valor</th>
				<th>Dado</th>
				<th style=\"width: 40px\"></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><span class=\"badge bg-success\">".$den_ind_7."</span></td>
					<td>Denominador</td>
					<td></td>
				</tr>
				<tr>
					<td><span class=\"badge bg-success\">".$den_est_7."</span></td>
					<td>Denominador estimado</td>
					<td><span class=\"badge ".$cor7."\">".ceil($num_prc_est_7)."%</span></td>
				</tr>
				<tr>
					<td><span class=\"badge bg-success\">".$num_7."</span></td>
					<td>Numerador</td>
					<td><span class=\"badge ".$cor7."\">".ceil($num_prc_7)."%</span></td>
				</tr>
				<tr>
					<td><span class=\"badge bg-success\">".$c_hiper_7."</span></td>
					<td>Marcadas como hipertensas</td>
					<td><i class=\"fas fa-heartbeat nav-icon\"></i></td>
				</tr>
				<tr>
					<td><span class=\"badge bg-success\">".$c_gest_7."</span></td>
					<td>Marcadas como gestantes</td>
					<td><i class=\"fas fa-baby-carriage nav-icon\"></i></td>
				</tr>
				<tr>
					<td></td>
					<td>Data inicial</td>
					<td>".dtshow($dti_7)."</td>
				</tr>
				<tr>
					<td></td>
					<td>Data final</td>
					<td>".dtshow($dtf_7)."</td>
				</tr>
				<tr>
					<td></td>
					<td>Quadrimestre</td>
					<td>".$quadri_7."</td>
				</tr>
				<tr>
					<td>".$versao_7."</td>
					<td>".$datahora_7."</td>
					<td>".$banco_7."</td>
				</tr>
			</tbody>
			</table>
		</div>
		</div>
	</div>
	";
}


?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Informações gerais</h1> <?php echo $cbend4;?>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active">Geral</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <?php echo $dash_ind1;?>
		  <?php echo $dash_ind2;?>
		  <?php echo $dash_ind3;?>
		  <?php echo $dash_ind4;?>
        </div>
        <div class="row">
          <?php echo $dash_ind5;?>
		  <?php echo $dash_ind6;?>
		  <?php echo $dash_ind7;?>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="far fa-chart-bar"></i>
                  Gráficos
                </h3>
              </div>
              <div class="card-body">
                <div class="row">
					<?php echo $graf_ind1;?>
					<?php echo $graf_ind2;?>
					<?php echo $graf_ind3;?>
					<?php echo $graf_ind4;?>
                </div>
                <div class="row">
					<?php echo $graf_ind5;?>
					<?php echo $graf_ind6;?>
					<?php echo $graf_ind7;?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.row -->
        <div class="row">
			<?php echo $tab_ind1;?>
			<?php echo $tab_ind2;?>
			<?php echo $tab_ind3;?>
			<?php echo $tab_ind4;?>
        </div>
        <div class="row">
			<?php echo $tab_ind5;?>
			<?php echo $tab_ind6;?>
			<?php echo $tab_ind7;?>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
      <i class="fas fa-chevron-up"></i>
    </a>
  </div>
  <!-- /.content-wrapper -->
<script>
  $(function () {
    /* jQueryKnob */

    $('.knob').knob({
      /*change : function (value) {
       //console.log("change : " + value);
       },
       release : function (value) {
       console.log("release : " + value);
       },
       cancel : function () {
       console.log("cancel : " + this.value);
       },*/
      draw: function () {

        // "tron" case
        if (this.$.data('skin') == 'tron') {

          var a   = this.angle(this.cv)  // Angle
            ,
              sa  = this.startAngle          // Previous start angle
            ,
              sat = this.startAngle         // Start angle
            ,
              ea                            // Previous end angle
            ,
              eat = sat + a                 // End angle
            ,
              r   = true

          this.g.lineWidth = this.lineWidth

          this.o.cursor
          && (sat = eat - 0.3)
          && (eat = eat + 0.3)

          if (this.o.displayPrevious) {
            ea = this.startAngle + this.angle(this.value)
            this.o.cursor
            && (sa = ea - 0.3)
            && (ea = ea + 0.3)
            this.g.beginPath()
            this.g.strokeStyle = this.previousColor
            this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false)
            this.g.stroke()
          }

          this.g.beginPath()
          this.g.strokeStyle = r ? this.o.fgColor : this.fgColor
          this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false)
          this.g.stroke()

          this.g.lineWidth = 2
          this.g.beginPath()
          this.g.strokeStyle = this.o.fgColor
          this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false)
          this.g.stroke()

          return false
        }
      }
    })
  })

</script>