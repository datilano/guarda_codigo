<?php
//$curp = $_POST["curp_alum"];
$curp = "AIRD891226HCHTSN07";

if (function_exists('curl_init')) {// Comprobamos si hay soporte para cURL

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://consultas.curp.gob.mx/CurpSP/curp1.do?strCurp=' . $curp . '&strTipo=B');
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $resultado = curl_exec($ch);
    
    $doc = new DOMDocument();
    $doc->loadHTML($resultado);
    $xpath = new DOMXPath($doc);
    $data = $xpath->query('//form/input[@type="hidden"][position() >= 1 and position() <= 7]');
   
    foreach ($data as $dato) {
		$auxAttr = $dato->getAttribute('name');
		$auxCampo = "";
		$firsLtr = false;
		if(strpos(strtolower($auxAttr), "nombre")!==false) {
			$auxCampo = "nombre_alum";
		}elseif(strpos(strtolower($auxAttr), "primer")!==false) {
			$auxCampo = "apellido_pat_alum";
		}elseif(strpos(strtolower($auxAttr), "segund")!==false) {
			$auxCampo = "apellido_mat_alum";
		}elseif(strpos(strtolower($auxAttr), "sexo")!==false) {
			$auxCampo = "sexo_alum";
			$firsLtr = true;
		}elseif(strpos(strtolower($auxAttr), "fecha")!==false) {
			$auxCampo = "fecha_nac_alum";
		}elseif(strpos(strtolower($auxAttr), "ent")!==false) {
			$auxCampo = "estado_nac_alum";
		}

        if($auxCampo!=""){
			$value = $dato->getAttribute('value');
			if($firsLtr) $value = $value[0];
			$auxData.= $pipe . $auxCampo . ":" . $value;
			$pipe = "|";
		}
    }
	echo $auxData;
}
?>