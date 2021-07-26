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

$tab_ind1 = "";
if (file_exists("resumo/r_ind1_".$_SESSION['key'].".php")){
	require_once("resumo/r_ind1_".$_SESSION['key'].".php");
	$tab_ind1 = "
    <div class=\"card\">
      <div class=\"card-header\">
        <h3 class=\"card-title\">Indicador 1</h3>
		<!--
        <div class=\"card-tools\">
          <ul class=\"pagination pagination-sm float-right\">
            <li class=\"page-item\"><a class=\"page-link\" href=\"#\">&laquo;</a></li>
            <li class=\"page-item\"><a class=\"page-link\" href=\"#\">1</a></li>
            <li class=\"page-item\"><a class=\"page-link\" href=\"#\">2</a></li>
            <li class=\"page-item\"><a class=\"page-link\" href=\"#\">3</a></li>
            <li class=\"page-item\"><a class=\"page-link\" href=\"#\">&raquo;</a></li>
          </ul>
          <div class=\"input-group input-group-sm\" style=\"width: 150px;\">
            <input type=\"text\" name=\"table_search\" class=\"form-control float-right\" placeholder=\"Search\">
            <div class=\"input-group-append\">
              <button type=\"submit\" class=\"btn btn-default\">
                <i class=\"fas fa-search\"></i>
              </button>
            </div>
          </div>
        </div>
		-->
      </div>
      <div class=\"card-body p-0\">
        <table class=\"table table-striped\">
          <thead>
            <tr>
              <th style=\"width: 10px\">CNES</th>
              <th>Nome</th>
			  <th>Denominador</th>
			  <th>Numerador</th>
              <th>Indicador</th>
              <th style=\"width: 40px\"></th>
            </tr>
          </thead>
          <tbody>
	";
	$total_den = 0;
	$total_num = 0;
	for ($e=0;$e<count($unidade_1);$e++){
		$porcent = 0;
		if ($unidade_1[$e]['den'] > 0){
			$porcent = (100 * $unidade_1[$e]['num']) / $unidade_1[$e]['den'];
		}
		$cor1 = "bg-danger"; // vermelho
		if (ceil($porcent) >= 16 && ceil($porcent) < 28){
			$cor1 = "bg-warning"; // amarelo
		}
		if (ceil($porcent) >= 28 && ceil($porcent) < 40){
			$cor1 = "bg-success"; // verde
		}
		if (ceil($porcent) > 40){
			$cor1 = "bg-primary"; // azul
		}
		$tab_ind1 .= "
			<tr>
			  <td>".$unidade_1[$e]['cnes']."</td>
			  <td>".$unidade_1[$e]['nome']."</td>
			  <td>".$unidade_1[$e]['den']."</td>
			  <td>".$unidade_1[$e]['num']."</td>
			  <td>
				<div class=\"progress progress-xs\">
				  <div class=\"progress-bar ".$cor1."\" style=\"width: ".ceil($porcent)."%\"></div>
				</div>
			  </td>
			  <td><span class=\"badge ".$cor1."\">".ceil($porcent)."%</span></td>
			</tr>
		";	
		$total_den = $total_den + $unidade_1[$e]['den'];
		$total_num = $total_num + $unidade_1[$e]['num'];
	}
	$porcent = 0;
	if ($total_den > 0){
		$porcent = (100 * $total_num) / $total_den;
	}
	$cor1 = "bg-danger"; // vermelho
	if (ceil($porcent) >= 16 && ceil($porcent) < 28){
		$cor1 = "bg-warning"; // amarelo
	}
	if (ceil($porcent) >= 28 && ceil($porcent) < 40){
		$cor1 = "bg-success"; // verde
	}
	if (ceil($porcent) > 40){
		$cor1 = "bg-primary"; // azul
	}
	$tab_ind1 .= "
          </tbody>
		  <tfoot>
			<tr>
			  <td> </td>
			  <td> </td>
			  <td>".$total_den."</td>
			  <td>".$total_num."</td>
			  <td>
				<div class=\"progress progress-xs\">
				  <div class=\"progress-bar ".$cor1."\" style=\"width: ".ceil($porcent)."%\"></div>
				</div>
			  </td>
			  <td><span class=\"badge ".$cor1."\">".ceil($porcent)."%</span></td>
			</tr>
		  </tfoot>
        </table>
      </div>
    </div>
	";
}
//****************************************************************************************************************
//****************************************************************************************************************
//****************************************************************************************************************
//****************************************************************************************************************
$tab_ind2 = "";
if (file_exists("resumo/r_ind2_".$_SESSION['key'].".php")){
	require_once("resumo/r_ind2_".$_SESSION['key'].".php");
	$tab_ind2 = "
    <div class=\"card\">
      <div class=\"card-header\">
        <h3 class=\"card-title\">Indicador 2</h3>
		<!--
        <div class=\"card-tools\">
          <ul class=\"pagination pagination-sm float-right\">
            <li class=\"page-item\"><a class=\"page-link\" href=\"#\">&laquo;</a></li>
            <li class=\"page-item\"><a class=\"page-link\" href=\"#\">1</a></li>
            <li class=\"page-item\"><a class=\"page-link\" href=\"#\">2</a></li>
            <li class=\"page-item\"><a class=\"page-link\" href=\"#\">3</a></li>
            <li class=\"page-item\"><a class=\"page-link\" href=\"#\">&raquo;</a></li>
          </ul>
          <div class=\"input-group input-group-sm\" style=\"width: 150px;\">
            <input type=\"text\" name=\"table_search\" class=\"form-control float-right\" placeholder=\"Search\">
            <div class=\"input-group-append\">
              <button type=\"submit\" class=\"btn btn-default\">
                <i class=\"fas fa-search\"></i>
              </button>
            </div>
          </div>
        </div>
		-->
      </div>
      <div class=\"card-body p-0\">
        <table class=\"table table-striped\">
          <thead>
            <tr>
              <th style=\"width: 10px\">CNES</th>
              <th>Nome</th>
			  <th>Denominador</th>
			  <th>Numerador</th>
              <th>Indicador</th>
              <th style=\"width: 40px\"></th>
            </tr>
          </thead>
          <tbody>
	";
	$total_den = 0;
	$total_num = 0;
	for ($e=0;$e<count($unidade_2);$e++){
		$porcent = 0;
		if ($unidade_2[$e]['den'] > 0){
			$porcent = (100 * $unidade_2[$e]['num']) / $unidade_2[$e]['den'];
		}
		$cor2 = "bg-danger"; // vermelho
		if (ceil($porcent) >= 16 && ceil($porcent) < 28){
			$cor2 = "bg-warning"; // amarelo
		}
		if (ceil($porcent) >= 28 && ceil($porcent) < 40){
			$cor2 = "bg-success"; // verde
		}
		if (ceil($porcent) > 40){
			$cor2 = "bg-primary"; // azul
		}
		$tab_ind2 .= "
			<tr>
			  <td>".$unidade_2[$e]['cnes']."</td>
			  <td>".$unidade_2[$e]['nome']."</td>
			  <td>".$unidade_2[$e]['den']."</td>
			  <td>".$unidade_2[$e]['num']."</td>
			  <td>
				<div class=\"progress progress-xs\">
				  <div class=\"progress-bar ".$cor2."\" style=\"width: ".ceil($porcent)."%\"></div>
				</div>
			  </td>
			  <td><span class=\"badge ".$cor2."\">".ceil($porcent)."%</span></td>
			</tr>
		";	
		$total_den = $total_den + $unidade_2[$e]['den'];
		$total_num = $total_num + $unidade_2[$e]['num'];
	}
	$porcent = 0;
	if ($total_den > 0){
		$porcent = (100 * $total_num) / $total_den;
	}
	$cor2 = "bg-danger"; // vermelho
	if (ceil($porcent) >= 16 && ceil($porcent) < 28){
		$cor2 = "bg-warning"; // amarelo
	}
	if (ceil($porcent) >= 28 && ceil($porcent) < 40){
		$cor2 = "bg-success"; // verde
	}
	if (ceil($porcent) > 40){
		$cor2 = "bg-primary"; // azul
	}
	$tab_ind2 .= "
          </tbody>
		  <tfoot>
			<tr>
			  <td> </td>
			  <td> </td>
			  <td>".$total_den."</td>
			  <td>".$total_num."</td>
			  <td>
				<div class=\"progress progress-xs\">
				  <div class=\"progress-bar ".$cor2."\" style=\"width: ".ceil($porcent)."%\"></div>
				</div>
			  </td>
			  <td><span class=\"badge ".$cor2."\">".ceil($porcent)."%</span></td>
			</tr>
		  </tfoot>
        </table>
      </div>
    </div>
	";
}
//****************************************************************************************************************
//****************************************************************************************************************
//****************************************************************************************************************
//****************************************************************************************************************
$tab_ind3 = "";
if (file_exists("resumo/r_ind3_".$_SESSION['key'].".php")){
	require_once("resumo/r_ind3_".$_SESSION['key'].".php");
	$tab_ind3 = "
    <div class=\"card\">
      <div class=\"card-header\">
        <h3 class=\"card-title\">Indicador 3</h3>
		<!--
        <div class=\"card-tools\">
          <ul class=\"pagination pagination-sm float-right\">
            <li class=\"page-item\"><a class=\"page-link\" href=\"#\">&laquo;</a></li>
            <li class=\"page-item\"><a class=\"page-link\" href=\"#\">1</a></li>
            <li class=\"page-item\"><a class=\"page-link\" href=\"#\">2</a></li>
            <li class=\"page-item\"><a class=\"page-link\" href=\"#\">3</a></li>
            <li class=\"page-item\"><a class=\"page-link\" href=\"#\">&raquo;</a></li>
          </ul>
          <div class=\"input-group input-group-sm\" style=\"width: 150px;\">
            <input type=\"text\" name=\"table_search\" class=\"form-control float-right\" placeholder=\"Search\">
            <div class=\"input-group-append\">
              <button type=\"submit\" class=\"btn btn-default\">
                <i class=\"fas fa-search\"></i>
              </button>
            </div>
          </div>
        </div>
		-->
      </div>
      <div class=\"card-body p-0\">
        <table class=\"table table-striped\">
          <thead>
            <tr>
              <th style=\"width: 10px\">CNES</th>
              <th>Nome</th>
			  <th>Denominador</th>
			  <th>Numerador</th>
              <th>Indicador</th>
              <th style=\"width: 40px\"></th>
            </tr>
          </thead>
          <tbody>
	";
	$total_den = 0;
	$total_num = 0;
	for ($e=0;$e<count($unidade_3);$e++){
		$porcent = 0;
		if ($unidade_3[$e]['den'] > 0){
			$porcent = (100 * $unidade_3[$e]['num']) / $unidade_3[$e]['den'];
		}
		$cor3 = "bg-danger"; // vermelho
		if (ceil($porcent) >= 16 && ceil($porcent) < 28){
			$cor3 = "bg-warning"; // amarelo
		}
		if (ceil($porcent) >= 28 && ceil($porcent) < 40){
			$cor3 = "bg-success"; // verde
		}
		if (ceil($porcent) > 40){
			$cor3 = "bg-primary"; // azul
		}
		$tab_ind3 .= "
			<tr>
			  <td>".$unidade_3[$e]['cnes']."</td>
			  <td>".$unidade_3[$e]['nome']."</td>
			  <td>".$unidade_3[$e]['den']."</td>
			  <td>".$unidade_3[$e]['num']."</td>
			  <td>
				<div class=\"progress progress-xs\">
				  <div class=\"progress-bar ".$cor3."\" style=\"width: ".ceil($porcent)."%\"></div>
				</div>
			  </td>
			  <td><span class=\"badge ".$cor3."\">".ceil($porcent)."%</span></td>
			</tr>
		";	
		$total_den = $total_den + $unidade_3[$e]['den'];
		$total_num = $total_num + $unidade_3[$e]['num'];
	}
	$porcent = 0;
	if ($total_den > 0){
		$porcent = (100 * $total_num) / $total_den;
	}
	$cor3 = "bg-danger"; // vermelho
	if (ceil($porcent) >= 16 && ceil($porcent) < 28){
		$cor3 = "bg-warning"; // amarelo
	}
	if (ceil($porcent) >= 28 && ceil($porcent) < 40){
		$cor3 = "bg-success"; // verde
	}
	if (ceil($porcent) > 40){
		$cor3 = "bg-primary"; // azul
	}
	$tab_ind3 .= "
          </tbody>
		  <tfoot>
			<tr>
			  <td> </td>
			  <td> </td>
			  <td>".$total_den."</td>
			  <td>".$total_num."</td>
			  <td>
				<div class=\"progress progress-xs\">
				  <div class=\"progress-bar ".$cor3."\" style=\"width: ".ceil($porcent)."%\"></div>
				</div>
			  </td>
			  <td><span class=\"badge ".$cor3."\">".ceil($porcent)."%</span></td>
			</tr>
		  </tfoot>
        </table>
      </div>
    </div>
	";
}
//****************************************************************************************************************
//****************************************************************************************************************
//****************************************************************************************************************
//****************************************************************************************************************
$tab_ind4 = "";
if (file_exists("resumo/r_ind4_".$_SESSION['key'].".php")){
	require_once("resumo/r_ind4_".$_SESSION['key'].".php");
	$tab_ind4 = "
    <div class=\"card\">
      <div class=\"card-header\">
        <h3 class=\"card-title\">Indicador 4</h3>
		<!--
        <div class=\"card-tools\">
          <ul class=\"pagination pagination-sm float-right\">
            <li class=\"page-item\"><a class=\"page-link\" href=\"#\">&laquo;</a></li>
            <li class=\"page-item\"><a class=\"page-link\" href=\"#\">1</a></li>
            <li class=\"page-item\"><a class=\"page-link\" href=\"#\">2</a></li>
            <li class=\"page-item\"><a class=\"page-link\" href=\"#\">3</a></li>
            <li class=\"page-item\"><a class=\"page-link\" href=\"#\">&raquo;</a></li>
          </ul>
          <div class=\"input-group input-group-sm\" style=\"width: 150px;\">
            <input type=\"text\" name=\"table_search\" class=\"form-control float-right\" placeholder=\"Search\">
            <div class=\"input-group-append\">
              <button type=\"submit\" class=\"btn btn-default\">
                <i class=\"fas fa-search\"></i>
              </button>
            </div>
          </div>
        </div>
		-->
      </div>
      <div class=\"card-body p-0\">
        <table class=\"table table-striped\">
          <thead>
            <tr>
              <th style=\"width: 10px\">CNES</th>
              <th>Nome</th>
			  <th>Denominador</th>
			  <th>Numerador</th>
              <th>Indicador</th>
              <th style=\"width: 40px\"></th>
            </tr>
          </thead>
          <tbody>
	";
	$total_den = 0;
	$total_num = 0;
	for ($e=0;$e<count($unidade_4);$e++){
		$porcent = 0;
		if ($unidade_4[$e]['den'] > 0){
			$porcent = (100 * $unidade_4[$e]['num']) / $unidade_4[$e]['den'];
		}
		$cor4 = "bg-danger"; // vermelho
		if (ceil($porcent) >= 16 && ceil($porcent) < 28){
			$cor4 = "bg-warning"; // amarelo
		}
		if (ceil($porcent) >= 28 && ceil($porcent) < 40){
			$cor4 = "bg-success"; // verde
		}
		if (ceil($porcent) > 40){
			$cor4 = "bg-primary"; // azul
		}
		$tab_ind4 .= "
			<tr>
			  <td>".$unidade_4[$e]['cnes']."</td>
			  <td>".$unidade_4[$e]['nome']."</td>
			  <td>".$unidade_4[$e]['den']."</td>
			  <td>".$unidade_4[$e]['num']."</td>
			  <td>
				<div class=\"progress progress-xs\">
				  <div class=\"progress-bar ".$cor4."\" style=\"width: ".ceil($porcent)."%\"></div>
				</div>
			  </td>
			  <td><span class=\"badge ".$cor4."\">".ceil($porcent)."%</span></td>
			</tr>
		";	
		$total_den = $total_den + $unidade_4[$e]['den'];
		$total_num = $total_num + $unidade_4[$e]['num'];
	}
	$porcent = 0;
	if ($total_den > 0){
		$porcent = (100 * $total_num) / $total_den;
	}
	$cor4 = "bg-danger"; // vermelho
	if (ceil($porcent) >= 16 && ceil($porcent) < 28){
		$cor4 = "bg-warning"; // amarelo
	}
	if (ceil($porcent) >= 28 && ceil($porcent) < 40){
		$cor4 = "bg-success"; // verde
	}
	if (ceil($porcent) > 40){
		$cor4 = "bg-primary"; // azul
	}
	$tab_ind4 .= "
          </tbody>
		  <tfoot>
			<tr>
			  <td> </td>
			  <td> </td>
			  <td>".$total_den."</td>
			  <td>".$total_num."</td>
			  <td>
				<div class=\"progress progress-xs\">
				  <div class=\"progress-bar ".$cor4."\" style=\"width: ".ceil($porcent)."%\"></div>
				</div>
			  </td>
			  <td><span class=\"badge ".$cor4."\">".ceil($porcent)."%</span></td>
			</tr>
		  </tfoot>
        </table>
      </div>
    </div>
	";
}
//****************************************************************************************************************
//****************************************************************************************************************
//****************************************************************************************************************
//****************************************************************************************************************
$tab_ind5 = "";
if (file_exists("resumo/r_ind5_".$_SESSION['key'].".php")){
	require_once("resumo/r_ind5_".$_SESSION['key'].".php");
	$tab_ind5 = "
    <div class=\"card\">
      <div class=\"card-header\">
        <h3 class=\"card-title\">Indicador 5</h3>
		<!--
        <div class=\"card-tools\">
          <ul class=\"pagination pagination-sm float-right\">
            <li class=\"page-item\"><a class=\"page-link\" href=\"#\">&laquo;</a></li>
            <li class=\"page-item\"><a class=\"page-link\" href=\"#\">1</a></li>
            <li class=\"page-item\"><a class=\"page-link\" href=\"#\">2</a></li>
            <li class=\"page-item\"><a class=\"page-link\" href=\"#\">3</a></li>
            <li class=\"page-item\"><a class=\"page-link\" href=\"#\">&raquo;</a></li>
          </ul>
          <div class=\"input-group input-group-sm\" style=\"width: 150px;\">
            <input type=\"text\" name=\"table_search\" class=\"form-control float-right\" placeholder=\"Search\">
            <div class=\"input-group-append\">
              <button type=\"submit\" class=\"btn btn-default\">
                <i class=\"fas fa-search\"></i>
              </button>
            </div>
          </div>
        </div>
		-->
      </div>
      <div class=\"card-body p-0\">
        <table class=\"table table-striped\">
          <thead>
            <tr>
              <th style=\"width: 10px\">CNES</th>
              <th>Nome</th>
			  <th>Denominador</th>
			  <th>Numerador</th>
              <th>Indicador</th>
              <th style=\"width: 40px\"></th>
            </tr>
          </thead>
          <tbody>
	";
	$total_den = 0;
	$total_num = 0;
	for ($e=0;$e<count($unidade_5);$e++){
		$porcent = 0;
		if ($unidade_5[$e]['den'] > 0){
			$porcent = (100 * $unidade_5[$e]['num']) / $unidade_5[$e]['den'];
		}
		$cor5 = "bg-danger"; // vermelho
		if (ceil($porcent) >= 16 && ceil($porcent) < 28){
			$cor5 = "bg-warning"; // amarelo
		}
		if (ceil($porcent) >= 28 && ceil($porcent) < 40){
			$cor5 = "bg-success"; // verde
		}
		if (ceil($porcent) > 40){
			$cor5 = "bg-primary"; // azul
		}
		$tab_ind5 .= "
			<tr>
			  <td>".$unidade_5[$e]['cnes']."</td>
			  <td>".$unidade_5[$e]['nome']."</td>
			  <td>".$unidade_5[$e]['den']."</td>
			  <td>".$unidade_5[$e]['num']."</td>
			  <td>
				<div class=\"progress progress-xs\">
				  <div class=\"progress-bar ".$cor5."\" style=\"width: ".ceil($porcent)."%\"></div>
				</div>
			  </td>
			  <td><span class=\"badge ".$cor5."\">".ceil($porcent)."%</span></td>
			</tr>
		";	
		$total_den = $total_den + $unidade_5[$e]['den'];
		$total_num = $total_num + $unidade_5[$e]['num'];
	}
	$porcent = 0;
	if ($total_den > 0){
		$porcent = (100 * $total_num) / $total_den;
	}
	$cor5 = "bg-danger"; // vermelho
	if (ceil($porcent) >= 16 && ceil($porcent) < 28){
		$cor5 = "bg-warning"; // amarelo
	}
	if (ceil($porcent) >= 28 && ceil($porcent) < 40){
		$cor5 = "bg-success"; // verde
	}
	if (ceil($porcent) > 40){
		$cor5 = "bg-primary"; // azul
	}
	$tab_ind5 .= "
          </tbody>
		  <tfoot>
			<tr>
			  <td> </td>
			  <td> </td>
			  <td>".$total_den."</td>
			  <td>".$total_num."</td>
			  <td>
				<div class=\"progress progress-xs\">
				  <div class=\"progress-bar ".$cor5."\" style=\"width: ".ceil($porcent)."%\"></div>
				</div>
			  </td>
			  <td><span class=\"badge ".$cor5."\">".ceil($porcent)."%</span></td>
			</tr>
		  </tfoot>
        </table>
      </div>
    </div>
	";
}
//****************************************************************************************************************
//****************************************************************************************************************
//****************************************************************************************************************
//****************************************************************************************************************
$tab_ind6 = "";
if (file_exists("resumo/r_ind6_".$_SESSION['key'].".php")){
	require_once("resumo/r_ind6_".$_SESSION['key'].".php");
	$tab_ind6 = "
    <div class=\"card\">
      <div class=\"card-header\">
        <h3 class=\"card-title\">Indicador 6</h3>
		<!--
        <div class=\"card-tools\">
          <ul class=\"pagination pagination-sm float-right\">
            <li class=\"page-item\"><a class=\"page-link\" href=\"#\">&laquo;</a></li>
            <li class=\"page-item\"><a class=\"page-link\" href=\"#\">1</a></li>
            <li class=\"page-item\"><a class=\"page-link\" href=\"#\">2</a></li>
            <li class=\"page-item\"><a class=\"page-link\" href=\"#\">3</a></li>
            <li class=\"page-item\"><a class=\"page-link\" href=\"#\">&raquo;</a></li>
          </ul>
          <div class=\"input-group input-group-sm\" style=\"width: 150px;\">
            <input type=\"text\" name=\"table_search\" class=\"form-control float-right\" placeholder=\"Search\">
            <div class=\"input-group-append\">
              <button type=\"submit\" class=\"btn btn-default\">
                <i class=\"fas fa-search\"></i>
              </button>
            </div>
          </div>
        </div>
		-->
      </div>
      <div class=\"card-body p-0\">
        <table class=\"table table-striped\">
          <thead>
            <tr>
              <th style=\"width: 10px\">CNES</th>
              <th>Nome</th>
			  <th>Denominador</th>
			  <th>Numerador</th>
              <th>Indicador</th>
              <th style=\"width: 40px\"></th>
            </tr>
          </thead>
          <tbody>
	";
	$total_den = 0;
	$total_num = 0;
	for ($e=0;$e<count($unidade_6);$e++){
		$porcent = 0;
		if ($unidade_6[$e]['den'] > 0){
			$porcent = (100 * $unidade_6[$e]['num']) / $unidade_6[$e]['den'];
		}
		$cor6 = "bg-danger"; // vermelho
		if (ceil($porcent) >= 16 && ceil($porcent) < 28){
			$cor6 = "bg-warning"; // amarelo
		}
		if (ceil($porcent) >= 28 && ceil($porcent) < 40){
			$cor6 = "bg-success"; // verde
		}
		if (ceil($porcent) > 40){
			$cor6 = "bg-primary"; // azul
		}
		$tab_ind6 .= "
			<tr>
			  <td>".$unidade_6[$e]['cnes']."</td>
			  <td>".$unidade_6[$e]['nome']."</td>
			  <td>".$unidade_6[$e]['den']."</td>
			  <td>".$unidade_6[$e]['num']."</td>
			  <td>
				<div class=\"progress progress-xs\">
				  <div class=\"progress-bar ".$cor6."\" style=\"width: ".ceil($porcent)."%\"></div>
				</div>
			  </td>
			  <td><span class=\"badge ".$cor6."\">".ceil($porcent)."%</span></td>
			</tr>
		";	
		$total_den = $total_den + $unidade_6[$e]['den'];
		$total_num = $total_num + $unidade_6[$e]['num'];
	}
	$porcent = 0;
	if ($total_den > 0){
		$porcent = (100 * $total_num) / $total_den;
	}
	$cor6 = "bg-danger"; // vermelho
	if (ceil($porcent) >= 16 && ceil($porcent) < 28){
		$cor6 = "bg-warning"; // amarelo
	}
	if (ceil($porcent) >= 28 && ceil($porcent) < 40){
		$cor6 = "bg-success"; // verde
	}
	if (ceil($porcent) > 40){
		$cor6 = "bg-primary"; // azul
	}
	$tab_ind6 .= "
          </tbody>
		  <tfoot>
			<tr>
			  <td> </td>
			  <td> </td>
			  <td>".$total_den."</td>
			  <td>".$total_num."</td>
			  <td>
				<div class=\"progress progress-xs\">
				  <div class=\"progress-bar ".$cor6."\" style=\"width: ".ceil($porcent)."%\"></div>
				</div>
			  </td>
			  <td><span class=\"badge ".$cor6."\">".ceil($porcent)."%</span></td>
			</tr>
		  </tfoot>
        </table>
      </div>
    </div>
	";
}
//****************************************************************************************************************
//****************************************************************************************************************
//****************************************************************************************************************
//****************************************************************************************************************
$tab_ind7 = "";
if (file_exists("resumo/r_ind7_".$_SESSION['key'].".php")){
	require_once("resumo/r_ind7_".$_SESSION['key'].".php");
	$tab_ind7 = "
    <div class=\"card\">
      <div class=\"card-header\">
        <h3 class=\"card-title\">Indicador 7</h3>
		<!--
        <div class=\"card-tools\">
          <ul class=\"pagination pagination-sm float-right\">
            <li class=\"page-item\"><a class=\"page-link\" href=\"#\">&laquo;</a></li>
            <li class=\"page-item\"><a class=\"page-link\" href=\"#\">1</a></li>
            <li class=\"page-item\"><a class=\"page-link\" href=\"#\">2</a></li>
            <li class=\"page-item\"><a class=\"page-link\" href=\"#\">3</a></li>
            <li class=\"page-item\"><a class=\"page-link\" href=\"#\">&raquo;</a></li>
          </ul>
          <div class=\"input-group input-group-sm\" style=\"width: 150px;\">
            <input type=\"text\" name=\"table_search\" class=\"form-control float-right\" placeholder=\"Search\">
            <div class=\"input-group-append\">
              <button type=\"submit\" class=\"btn btn-default\">
                <i class=\"fas fa-search\"></i>
              </button>
            </div>
          </div>
        </div>
		-->
      </div>
      <div class=\"card-body p-0\">
        <table class=\"table table-striped\">
          <thead>
            <tr>
              <th style=\"width: 10px\">CNES</th>
              <th>Nome</th>
			  <th>Denominador</th>
			  <th>Numerador</th>
              <th>Indicador</th>
              <th style=\"width: 40px\"></th>
            </tr>
          </thead>
          <tbody>
	";
	$total_den = 0;
	$total_num = 0;
	for ($e=0;$e<count($unidade_7);$e++){
		$porcent = 0;
		if ($unidade_7[$e]['den'] > 0){
			$porcent = (100 * $unidade_7[$e]['num']) / $unidade_7[$e]['den'];
		}
		$cor7 = "bg-danger"; // vermelho
		if (ceil($porcent) >= 16 && ceil($porcent) < 28){
			$cor7 = "bg-warning"; // amarelo
		}
		if (ceil($porcent) >= 28 && ceil($porcent) < 40){
			$cor7 = "bg-success"; // verde
		}
		if (ceil($porcent) > 40){
			$cor7 = "bg-primary"; // azul
		}
		$tab_ind7 .= "
			<tr>
			  <td>".$unidade_7[$e]['cnes']."</td>
			  <td>".$unidade_7[$e]['nome']."</td>
			  <td>".$unidade_7[$e]['den']."</td>
			  <td>".$unidade_7[$e]['num']."</td>
			  <td>
				<div class=\"progress progress-xs\">
				  <div class=\"progress-bar ".$cor7."\" style=\"width: ".ceil($porcent)."%\"></div>
				</div>
			  </td>
			  <td><span class=\"badge ".$cor7."\">".ceil($porcent)."%</span></td>
			</tr>
		";	
		$total_den = $total_den + $unidade_7[$e]['den'];
		$total_num = $total_num + $unidade_7[$e]['num'];
	}
	$porcent = 0;
	if ($total_den > 0){
		$porcent = (100 * $total_num) / $total_den;
	}
	$cor7 = "bg-danger"; // vermelho
	if (ceil($porcent) >= 16 && ceil($porcent) < 28){
		$cor7 = "bg-warning"; // amarelo
	}
	if (ceil($porcent) >= 28 && ceil($porcent) < 40){
		$cor7 = "bg-success"; // verde
	}
	if (ceil($porcent) > 40){
		$cor7 = "bg-primary"; // azul
	}
	$tab_ind7 .= "
          </tbody>
		  <tfoot>
			<tr>
			  <td> </td>
			  <td> </td>
			  <td>".$total_den."</td>
			  <td>".$total_num."</td>
			  <td>
				<div class=\"progress progress-xs\">
				  <div class=\"progress-bar ".$cor7."\" style=\"width: ".ceil($porcent)."%\"></div>
				</div>
			  </td>
			  <td><span class=\"badge ".$cor7."\">".ceil($porcent)."%</span></td>
			</tr>
		  </tfoot>
        </table>
      </div>
    </div>
	";
}


?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="contacnesr-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Indicadores por unidade</h1> <?php echo $cbend4;?>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active">unidades</li>
            </ol>
          </div>
        </div>
      </div><!-- /.contacnesr-fluid -->
    </section>
    <section class="content">
      <div class="contacnesr-fluid">
        <div class="row">
          <div class="col-md-12">
			<?php echo $tab_ind1;?>	
			<?php echo $tab_ind2;?>	
			<?php echo $tab_ind3;?>	
			<?php echo $tab_ind4;?>	
			<?php echo $tab_ind5;?>	
			<?php echo $tab_ind6;?>	
			<?php echo $tab_ind7;?>	
          </div>
          <!-- /.col -->
        </div>
      </div><!-- /.contacnesr-fluid -->
    </section>
    <!-- /.content -->
    <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
      <i class="fas fa-chevron-up"></i>
    </a>
  </div>
  <!-- /.content-wrapper -->

