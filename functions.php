<?php
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//   15/07/2021
//   Rodrigo Silva
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

function dataint($data,$tp="t"){  
	if ($tp == "t"){ // ----------------------- 2021-03-20
		$dts = explode('-',$data);
		$ndi = (int) $dts[0].$dts[1].$dts[2];
	} else {         // ----------------------- 20/03/2021
		$dts = explode('/',$data);
		$ndi = (int) $dts[2].$dts[1].$dts[0];
	}
	return $ndi;
}
function cmp($a, $b) {
	return $a['data'] > $b['data'];
}
function datamostra($data,$t=0){
	if ($t == 0){ // 2021-03-20
		$dts = explode('-',$data);
		$ndi = $dts[2]."/".$dts[1]."/".$dts[0];
	}
	if ($t == 1){ // 20210320
		$ndi = substr($data,6,2)."/".substr($data,4,2)."/".substr($data,0,4);
	}
	return $ndi;
}
function dtshow($data){ // 20210320
	$ndi = substr($data,6,2)."/".substr($data,4,2)."/".substr($data,0,4);
	return $ndi;
}
function zeroesq($num,$nz,$inc=0){
	$strfinal = '';
	$num = (int) $num;
	if (ctype_digit($num)){
		$num = $num + $inc;
		if (strlen($num) < $nz){
			for ($i=0;$i<($nz-strlen($num));$i++){
				$strfinal = $strfinal."0";
			}
			$strfinal = $strfinal.$num;
		} else {
			$strfinal = $num;
		}
	} else {
		$strfinal = $num;
	}
	return $strfinal;
}
function cnes($fxm,$pcn,$pcb,$pioc,$ioc='I'){
	// cnes('xml/cnes.xml',$cns,$cbo,$cnes,'C')
	// cnes('xml/cnes.xml',$cns,$cbo,$ine,'I')
	$retorno = false;
	$p_cns = false;
	$p_ioc = false;
	$p_cbo = false;
	$sioc = '';
	$xml = simplexml_load_file($fxm);
	foreach($xml->IDENTIFICACAO->PROFISSIONAIS->DADOS_PROFISSIONAIS as $registro):
		if ($pcn == $registro['CO_CNS']){
			$p_cns = true;
		}
		foreach($registro->LOTACOES->DADOS_LOTACOES as $lota):
			if ($p_cns){
				if ($ioc == 'C'){
					$sioc = $lota['CNES'];
				} else {
					$sioc = $lota['CO_INE'];
				}
				if ($pioc == $sioc){
					$p_ioc = true;
				}
				if ($p_ioc){
					if ($pcb == $lota['CO_CBO']){
						$p_cbo = true;
					}
				}
			}
		endforeach;
	endforeach;
	if ($p_cns && $p_ioc && $p_cbo){
		$retorno = true;
	}
	return $retorno;
}
function zesq($num,$nz,$inc=0){
	$strfinal = '';
	$num = $num + $inc;
	if (strlen($num) < $nz){
		for ($i=0;$i<($nz-strlen($num));$i++){
			$strfinal = $strfinal."0";
		}
		$strfinal = $strfinal.$num;
	} else {
		$strfinal = $num;
	}
	return $strfinal;
}
function mcpf($num){
	$strfinal = '';
	if (strlen($num) < 11){
		for ($i=0;$i<(11-strlen($num));$i++){
			$strfinal = $strfinal."0";
		}
		$strfinal = $strfinal.$num;
	} else {
		$strfinal = $num;
	}
	$strfinal = substr($strfinal,0,3).".".substr($strfinal,3,3).".".substr($strfinal,6,3)."-".substr($strfinal,9,2);
	return $strfinal;
}
function mcns($num){
	$strfinal = '';
	if (strlen($num) < 15){
		for ($i=0;$i<(15-strlen($num));$i++){
			$strfinal = $strfinal."0";
		}
		$strfinal = $strfinal.$num;
	} else {
		$strfinal = $num;
	}
	$strfinal = substr($strfinal,0,3).".".substr($strfinal,3,3).".".substr($strfinal,6,3).".".substr($strfinal,9,3).".".substr($strfinal,12,3);
	return $strfinal;
}
function idadeint($data,$dtp=0){  // data int 20210225
	if ($dtp == 0){
		$dtp = date('Ymd');
	}
	$idade = 0;
	$ano = (int) substr($data,0,4);
	$mes = (int) substr($data,4,2);
	$dia = (int) substr($data,6,2);
	$ano_p = (int) substr($dtp,0,4);
	$mes_p = (int) substr($dtp,4,2);
	$dia_p = (int) substr($dtp,6,2);
	if ($ano_p >= $ano){
		$idade = $ano_p - $ano;
		if ($mes_p < $mes){
			$idade--;
		}
		if ($mes_p == $mes){
			if ($dia_p < $dia){
				$idade--;
			}
		}
	}
	return $idade;
}
function datasomadias($data,$dias=0,$operador='+'){  // $data int 20210225 e retorna int Ymd
	$novadata = $data;
	$ano = (int) substr($data,0,4);
	$mes = (int) substr($data,4,2);
	$dia = (int) substr($data,6,2);
	$tdia = "days";
	if ($dias == 1){
		$tdia = "day";
	}
	$novadata = date('Ymd', strtotime($operador.$dias.' '.$tdia, strtotime($dia.'-'.$mes.'-'.$ano)));
	return $novadata;
}
function datasomameses($data,$meses=0,$operador='+'){  // $data int 20210225 e retorna int Ymd
	$novadata = $data;
	$ano = (int) substr($data,0,4);
	$mes = (int) substr($data,4,2);
	$dia = (int) substr($data,6,2);
	/*
	$tmes = "months";
	if ($meses == 1){
		$tmes = "month";
	}
	*/
	$tmes = "month";
	$novadata = date('Ymd', strtotime($operador.$meses.' '.$tmes, strtotime($dia.'-'.$mes.'-'.$ano)));
	//date("d/m/Y",strtotime(date("Y-m-d",strtotime($data_de_referencia))."-12 month"));
	//date("d/m/Y",strtotime(date("Y-m-d")."+12 month"));
	return $novadata;
}
function semestrem($data,$numm=6){  // $data int 20210225 e retorna int Ymd
	$novadata = $data;
	$ano = (int) substr($data,0,4);
	$mes = (int) substr($data,4,2);
	$dia = (int) substr($data,6,2);
	$ultimo_dia = false;
	switch ($mes) {
		case 1:
			if ($dia == 31)
				$ultimo_dia = true;
			break;
		case 2:
			/*
			$dia_fev = 28;
			if (($ano % 4) == 0 && ($ano % 100) != 0){
				$dia_fev = 29;
			}
			if (($ano % 400) == 0 && ($ano % 100) == 0){
				$dia_fev = 29;
			}
			if ($dia == $dia_fev)
				$ultimo_dia = true;
			*/
			if ($dia == 28 || $dia == 29)
				$ultimo_dia = true;
			break;
		case 3:
			if ($dia == 31)
				$ultimo_dia = true;
			break;
		case 4:
			if ($dia == 30)
				$ultimo_dia = true;
			break;
		case 5:
			if ($dia == 31)
				$ultimo_dia = true;
			break;
		case 6:
			if ($dia == 30)
				$ultimo_dia = true;
			break;
		case 7:
			if ($dia == 31)
				$ultimo_dia = true;
			break;
		case 8:
			if ($dia == 31)
				$ultimo_dia = true;
			break;
		case 9:
			if ($dia == 30)
				$ultimo_dia = true;
			break;
		case 10:
			if ($dia == 31)
				$ultimo_dia = true;
			break;
		case 11:
			if ($dia == 30)
				$ultimo_dia = true;
			break;
		case 12:
			if ($dia == 31)
				$ultimo_dia = true;
			break;
	}
	if ($mes > $numm){
		$mes = $mes - $numm;
	} else {
		$mes = 12 - ($numm - $mes);
		$ano--;
	}
	if ($ultimo_dia){
		$mes++;
		$dia = 1;
	}
	if (strlen($dia) == 1){
		$dia = '0'.$dia;
	}
	if (strlen($mes) == 1){
		$mes = '0'.$mes;
	}
	$novadata = $ano.$mes.$dia;
	return $novadata;
}
function unique_multidim_array($array, $key) {
    $temp_array = array();
    $i = 0;
    $key_array = array();
	$zero_array = array();
    foreach($array as $val) {
		if (!in_array($val[$key], $key_array)) {
			$key_array[$i] = $val[$key];
			$temp_array[$i] = $val;
			$i++;
		} else {
			if ($val[$key] == '0'){
				$temp_array[$i] = $val;
				$i++;	
			}
		}
    }
    return $temp_array;
}
function validaCNS($cns){
	if (strlen(trim($cns)) != 15){
		return false;
	}
	if (substr($cns,0,1) == 1 || substr($cns,0,1) == 2){
		$pis = substr($cns,0,11);
		$soma = substr($pis,0,1) * 15;
		$soma = $soma + substr($pis,1,1) * 14;
		$soma = $soma + substr($pis,2,1) * 13;
		$soma = $soma + substr($pis,3,1) * 12;
		$soma = $soma + substr($pis,4,1) * 11;
		$soma = $soma + substr($pis,5,1) * 10;
		$soma = $soma + substr($pis,6,1) * 9;
		$soma = $soma + substr($pis,7,1) * 8;
		$soma = $soma + substr($pis,8,1) * 7;
		$soma = $soma + substr($pis,9,1) * 6;
		$soma = $soma + substr($pis,10,1) * 5;
		$resto = $soma % 11;
		$dv = 11 - $resto;
		if ($dv == 11){
			$dv = 0;
		}
		if ($dv == 10){
			$soma = $soma + 2;
			$resto = $soma % 11;
			$dv = 11 - $resto;
			$resultado = $pis."001".$dv;
		}
		else{
			$resultado = $pis."000".$dv;
		}
		if ($cns != $resultado){
			return false;
		} else{
			return true;
		}
	} else {
		if (substr($cns,0,1) == 7 || substr($cns,0,1) == 8 || substr($cns,0,1) == 9){
			$soma = substr($cns,0,1) * 15;
			$soma = $soma + substr($cns,1,1) * 14;
			$soma = $soma + substr($cns,2,1) * 13;
			$soma = $soma + substr($cns,3,1) * 12;
			$soma = $soma + substr($cns,4,1) * 11;
			$soma = $soma + substr($cns,5,1) * 10;
			$soma = $soma + substr($cns,6,1) * 9;
			$soma = $soma + substr($cns,7,1) * 8;
			$soma = $soma + substr($cns,8,1) * 7;
			$soma = $soma + substr($cns,9,1) * 6;
			$soma = $soma + substr($cns,10,1) * 5;
			$soma = $soma + substr($cns,11,1) * 4;
			$soma = $soma + substr($cns,12,1) * 3;
			$soma = $soma + substr($cns,13,1) * 2;
			$soma = $soma + substr($cns,14,1) * 1;
			$resto = $soma % 11;
			if ($resto != 0){
				return false;
			} else{
				return true;
			}
		} else {
			return false;
		}
	}
}
function validaCPF($cpf) {
    $cpf = preg_replace( '/[^0-9]/is', '', $cpf );
    if (strlen($cpf) != 11) {
        return false;
    }
    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }
    return true;
}
function qfechado(){
	$final = "";
	$ano = (int) date("Y");
	$quadrimestre = 1;
	switch (date("m")) {
	  case "01":
		$quadrimestre = 1;
		break;
	  case "02":
		$quadrimestre = 1;
		break;
	  case "03":
		$quadrimestre = 1;
		break;
	  case "04":
		$quadrimestre = 1;
		break;
	  case "05":
		$quadrimestre = 2;
		break;
	  case "06":
		$quadrimestre = 2;
		break;
	  case "07":
		$quadrimestre = 2;
		break;
	  case "08":
		$quadrimestre = 2;
		break;
	  case "09":
		$quadrimestre = 3;
		break;
	  case "10":
		$quadrimestre = 3;
		break;
	  case "11":
		$quadrimestre = 3;
		break;
	  case "12":
		$quadrimestre = 3;
		break;
	  default:
		$quadrimestre = 1;
	}
	if ($quadrimestre == 3){
		$qant = 2;
	}
	if ($quadrimestre == 2){
		$qant = 1;
	}
	if ($quadrimestre == 1){
		$qant = 3;
		$ano--;
	}
	$final = "Q".$qant."/".$ano;
	return $final;
}
function qatual(){
	$final = "";
	$ano = (int) date("Y");
	$quadrimestre = 1;
	switch (date("m")) {
	  case "01":
		$quadrimestre = 1;
		break;
	  case "02":
		$quadrimestre = 1;
		break;
	  case "03":
		$quadrimestre = 1;
		break;
	  case "04":
		$quadrimestre = 1;
		break;
	  case "05":
		$quadrimestre = 2;
		break;
	  case "06":
		$quadrimestre = 2;
		break;
	  case "07":
		$quadrimestre = 2;
		break;
	  case "08":
		$quadrimestre = 2;
		break;
	  case "09":
		$quadrimestre = 3;
		break;
	  case "10":
		$quadrimestre = 3;
		break;
	  case "11":
		$quadrimestre = 3;
		break;
	  case "12":
		$quadrimestre = 3;
		break;
	  default:
		$quadrimestre = 1;
	}
	$final = "Q".$quadrimestre."/".$ano;
	return $final;
}
function qdata($data){ // data no formato yyyymmdd
	$final = "";
	$ano = substr($data,0,4);
	$mes = substr($data,4,2);
	$quadrimestre = 1;
	switch ($mes) {
	  case "01":
		$quadrimestre = 1;
		break;
	  case "02":
		$quadrimestre = 1;
		break;
	  case "03":
		$quadrimestre = 1;
		break;
	  case "04":
		$quadrimestre = 1;
		break;
	  case "05":
		$quadrimestre = 2;
		break;
	  case "06":
		$quadrimestre = 2;
		break;
	  case "07":
		$quadrimestre = 2;
		break;
	  case "08":
		$quadrimestre = 2;
		break;
	  case "09":
		$quadrimestre = 3;
		break;
	  case "10":
		$quadrimestre = 3;
		break;
	  case "11":
		$quadrimestre = 3;
		break;
	  case "12":
		$quadrimestre = 3;
		break;
	  default:
		$quadrimestre = 1;
	}
	$final = "Q".$quadrimestre."/".$ano;
	return $final;
}
function difdatas($data1,$data2){ // as duas datas no formato yyyymmdd (dt1 menor que dt2)
	$data1 = substr($data1,0,4)."-".substr($data1,4,2)."-".substr($data1,6,2);
	$data2 = substr($data2,0,4)."-".substr($data2,4,2)."-".substr($data2,6,2);
	$d1 = strtotime($data1); 
	$d2 = strtotime($data2);
	$dataFinal = ($d2 - $d1) /86400;
	if($dataFinal < 0)
	  $dataFinal *= -1;
	return  $dataFinal;	
}
?>