<?php session_start(); ?>
<style>
td.data-alumn{
	/*border-color:#666666; 
	border-style:dashed; 
	border-width:2px;*/
	border-style: dashed;
	border-left: white;
	width:260px;
	height:170px;
	max-height:170px;
	background-image:url(../images/fondo-cred2.png);
	background-repeat:no-repeat;
}

@media all {
   div.breakPage{
      display: none;
   }
}
 
@media print{
   div.breakPage{
      display:block;
      page-break-before:always;
   }
 
   /*No imprimir*/
   .oculto {display:none}
}
</style>
<?php
include "../../conexion.php";
include "../functions.php";
$id_esc		= $_POST["id_esc"];
$id_dep		= $_POST["id_dep"];
$sexo		= $_POST["sexo"];
$id_cat		= $_POST["id_cat"];
$id_equip	= $_POST["id_equip"];

$id_tipo_dep = getValue($conexion, "deportes", "id_tipo_dep", "id_dep='$id_dep'");

switch($id_tipo_dep){
	case 1:
		$qryRep = "SELECT * FROM vwrep_participaciones 
					WHERE participa=1
					AND esc_id_alum = '$id_esc' 
					AND id_deporte_cat = '$id_dep'";
		$poneEquipo = false;
		break;
	case 2:
		$qryRep = "SELECT * FROM vwinscripcion_equipos 
					WHERE confirm=1
					AND esc_id_alum = '$id_esc' 
					AND id_deporte_cat = '$id_dep'";
		if($sexo != "") $qryRep .= " AND sexo_cat = '$sexo'";
		if($id_cat != "") $qryRep .= " AND id_cat = '$id_cat'";
		if($id_equip != "") $qryRep .= " AND id_equip = '$id_equip'";
		$poneEquipo = true;
		break;
}

$resRep = mysql_query($qryRep);

$nombre_esc = getValue($conexion, "escuelas", "nombre_esc", "id_esc='$id_esc'");
$nombre_dep = getValue($conexion, "deportes", "nombre_dep", "id_dep='$id_dep'");

if($id_equip != "") $nombre_equip = getValue($conexion, "vwequipos", "nombre_equip", "id_equip='$id_equip'");

$i = 0;
$clsTab = 10;
$auxSex = "M";
while ($rowRep = mysql_fetch_array($resRep)){
	$nombre_completo_alum	= $rowRep["nombre_completo_alum"];
	$fecha_nac_alum			= $rowRep["fecha_nac_alum"];
	$sexo_alum				= $rowRep["sexo_alum"];
	$curp_alum				= $rowRep["curp_alum"];
	$estado_nac_alum		= $rowRep["estado_nac_alum"];
	$path_nombre_pic		= utf8_decode($rowRep["path_pic"]."/".$rowRep["nombre_pic"]);
	$nombre_cat = getValue($conexion, "categorias", "nombre_cat", "id_cat='".$rowRep["id_cat"]."'");
	switch($sexo_alum){
		case "H":
			$rama_cat = "Varonil";
		break;
		case "M":
			$rama_cat = "Femenil";
		break;
	}
	
	if($i==0){
		echo "	<table width='100%'>
					<!-- <tr>
						<td><b>Escuela:</b></td>
						<td colspan=7 style='height:23px; border-bottom: 1px solid black !important;'>$nombre_esc</td>
						<td rowspan=3><img src='../images/Logo_Copa.png' width='100' height='100' border='0' alt=''></td>
					</tr>
					<tr>
						<td valign='bottom'><b>Deporte:</b></td>
						<td valign='bottom' style='height:23px; border-bottom: 1px solid black !important;'>$nombre_dep</td> -->";
	if($id_dep!=6 && $id_dep!=7) {
		echo "			<!-- <td valign='bottom'><b>Categor&iacute;a:</b></td>
						<td valign='bottom' style='border-bottom: 1px solid black !important;'>$nombre_cat</td>
						<td valign='bottom'><b>Rama:</b></td>
						<td valign='bottom' style='border-bottom: 1px solid black !important;'>$rama_cat</td>
						<td valign='bottom'><b>Equipo:</b></td>
						<td valign='bottom' style='border-bottom: 1px solid black !important;'>$nombre_equip</td> -->";
	}
		echo "		</tr>
					<!-- <tr>
						<td valign='bottom'>Autorizaci&oacute;n:</td>
						<td style='border-bottom: 1px solid black !important;' colspan=2></td>
					<tr> -->
				</table>";
		echo "<table>";
	}
	if(fmod($i,2)==0) echo "<tr>";
	echo "		<td style='border-style: dashed;border-right: white;'>
					&nbsp;<img id='blah' src='../$path_nombre_pic' alt='your image' width='90px' height='110px' style='border-width: 2px; border-style: solid; border-color: black;'/>
				</td>
				<td class='data-alumn'>
					<b>Escuela: $nombre_esc<br>
					Deporte: $nombre_dep<br>
					Categoria: $nombre_cat - $rama_cat<br>";
	if($poneEquipo) echo "Equipo: $nombre_equip<br>";
	echo "			$nombre_completo_alum<br>
					$fecha_nac_alum<br>
					$curp_alum<br>
					$estado_nac_alum</b>
				</td>";
	if(fmod($i,2)==1) echo "</tr>";
	if($i==($clsTab-1)){
		echo "</table>";
		echo "<div class='breakPage'></div>";
		$i = -1;
	}
	$i++;
}
?>