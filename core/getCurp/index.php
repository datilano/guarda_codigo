<?php

$curp = 'AIRD891226HCHTSN07';
if (function_exists('curl_init')) // Comprobamos si hay soporte para cURL
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 
    'http://consultas.curp.gob.mx/CurpSP/curp1.do?strCurp=' . $curp . '&strTipo=B');
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $resultado = curl_exec($ch);
echo "<br> resultado:".$resultado;
    if($resultado != "") {
		$doc = new DOMDocument();
		$doc->loadHTML($resultado);
		$xpath = new DOMXPath($doc);
		$data = $xpath->query('//form/input[@type="hidden"][position() >= 1 and position() <= 6]/@value');
	   
		foreach ($data as $dato) {
			echo $dato->nodeValue . '<br />';
		}
	}
 
} else {
    echo "No hay soporte para cURL";
}

?>