<?php
session_start();
include "../../conexion.php";
include "../functions.php";
$id_equip = $_POST["id_equip"];
$id_cat = $_POST["id_cat"];
$tipo_dep = getValue($conexion, "vwcategorias", "id_tipo_dep", "id_cat='$id_cat'");

switch($tipo_dep){
	case 1:
		$auxAlum = "<table class='generic rounded'>
						<tr>
							<th></th>
							<th>Alumno</th>
							<th>Edad</th>";
		$qryPru = "SELECT prueba FROM vwpruebas WHERE id_cat='$id_cat'";
		$resPru = mysql_query($qryPru);
		while($rowPru = mysql_fetch_array($resPru)){
			$auxAlum .= "	<th>" . utf8_encode($rowPru["prueba"]) . "</th>";
		}
		$auxAlum .= "	</tr>";
		$i=1;
		$non = true;
		$qryAlum = "SELECT DISTINCT id_alum, nombre_completo_alum, fecha_nac_alum FROM vwparticipaciones WHERE id_cat='$id_cat' AND esc_id_alum='".$_SESSION["esc_id_usr"]."'";
		$resAlum = mysql_query($qryAlum);
		while($rowAlum = mysql_fetch_array($resAlum)){
			if($non){
				$auxClass = "non";
			}else{
				$auxClass = "par";
			}
			$non = !$non;
			$auxAlum .= "<tr class='$auxClass' onclick='verInscAlum(" . $rowAlum["id_alum"] . ")'>
							<td>$i</td>
							<td>" . utf8_encode($rowAlum["nombre_completo_alum"]) ."</td>
							<td>" . edad($rowAlum["fecha_nac_alum"]) . "</td>";
			
			$qryPru = "SELECT id_pru_cat FROM vwpruebas WHERE id_cat='$id_cat'";
			$resPru = mysql_query($qryPru);
			while($rowPru = mysql_fetch_array($resPru)){
				$id_part = getValue($conexion, "participaciones", "id_part", "id_pru_cat='" . $rowPru["id_pru_cat"] . "' AND id_alum='" . $rowAlum["id_alum"] . "' AND participa=1");
				$auxParticipa = "";
				if($id_part!="") $auxParticipa = "<img src='images/check.png' width='16' height='16'>";
				$auxAlum .= "<td><center>" . $auxParticipa . "</center></td>";
			}
			$auxAlum .= "</tr>";
			$i++;
		}
		$auxAlum .= "</table>";
	break;
	case 2:
		$qryAlum = "SELECT * FROM vwinscripcion_equipos WHERE id_equip = '$id_equip'AND confirm=1";
		$resAlum = mysql_query($qryAlum);
		$auxAlum = "<table class='generic rounded'>
						<tr>
							<th></th>
							<th>Alumno</th>
							<th>Edad</th>
							<th></th>
						</tr>";
		$i=1;
		$non = true;
		while($rowAlum = mysql_fetch_array($resAlum)){
			if($non){
				$auxClass = "non";
			}else{
				$auxClass = "par";
			}
			$non = !$non;
			$auxAlum .= "<tr class='$auxClass'>
							<td onclick='verInscAlum(" . $rowAlum["id_alum"] . ")'>$i</td>
							<td onclick='verInscAlum(" . $rowAlum["id_alum"] . ")'>" . utf8_encode($rowAlum["nombre_completo_alum"]) ."</td>
							<td onclick='verInscAlum(" . $rowAlum["id_alum"] . ")'>" . edad($rowAlum["fecha_nac_alum"]) . "</td>
							<td><a href='javascript:confirmaBorraInscripcion(" . $rowAlum["id_insc"] . ")'><img src='images/delete.png' width='16' height='16' border='0' alt=''></a>
							<a href='javascript:editarEquipo(" . $rowAlum["id_insc"] . ")'><img src='images/edit_alumno.png' width='16' height='16' border='0' alt=''></a></td>
						</tr>";
			$i++;
		}
		$auxAlum .= "</table>";

		$num_max_part_dep = getValue($conexion, "vwequipos", "num_max_part_dep", "id_equip='$id_equip'");
		$i--;
		$restantes = $num_max_part_dep - $i;

		$auxAlum = "<p align='right'><b>Lugares Disponibles:</b> " . $restantes . "</p>" . $auxAlum;
	break;
}
echo $auxAlum;
?>