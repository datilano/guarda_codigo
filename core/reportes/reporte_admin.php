<?php session_start(); ?>
<style>
table {
	font-family:Arial, Helvetica, sans-serif;
	color:#666;
	font-size:12px;
	text-shadow: 1px 1px 0px #fff;
	background:#eaebec;
	margin:20px;
	border:#ccc 1px solid;

	-moz-border-radius:3px;
	-webkit-border-radius:3px;
	border-radius:3px;

	-moz-box-shadow: 0 1px 2px #d1d1d1;
	-webkit-box-shadow: 0 1px 2px #d1d1d1;
	box-shadow: 0 1px 2px #d1d1d1;
}

table th {
	padding:21px 25px 22px 25px;
	border-top:1px solid #fafafa;
	border-bottom:1px solid #e0e0e0;

	background: #ededed;
	background: -webkit-gradient(linear, left top, left bottom, from(#ededed), to(#ebebeb));
	background: -moz-linear-gradient(top,  #ededed,  #ebebeb);
}

table tr {
	text-align: center;
	padding-left:20px;
	background: #fafafa;
}

tr.categoria {
	background: #B2D6FF;
}

tr.escuela {
	background: #B2FFE5;
}

table td {
	padding:18px;
	border-top: 1px solid #ffffff;
	border-bottom:1px solid #e0e0e0;
	border-left: 1px solid #e0e0e0;

	/*background: #fafafa;
	background: -webkit-gradient(linear, left top, left bottom, from(#fbfbfb), to(#fafafa));
	background: -moz-linear-gradient(top,  #fbfbfb,  #fafafa);*/
}

table tr:hover td {
	background: #B2B2B2;
	background: -webkit-gradient(linear, left top, left bottom, from(#B2B2B2), to(#B2B2B2));
	background: -moz-linear-gradient(top,  #B2B2B2,  #B2B2B2);	
}

table tr.categoria:hover td {
	background: #B2D6FF;
}

table tr.escuela:hover td {
	background: #B2FFE5;
}

</style>
<?php
include "../../conexion.php";
include "../functions.php";
$id_rep		= $_POST["id_rep"];
$id_dep		= $_POST["id_dep"];
$id_esc		= $_POST["id_esc"];
$sexo		= $_POST["sexo"];
$id_cat		= $_POST["id_cat"];
$contadorEquip=0;
$contadorAlumn=0;

switch($id_rep){
	//Equipo Inscritos
	case 1:
		$qryRep = "SELECT * FROM vwcategorias WHERE id_tipo_dep = 2";
		if($id_dep!="") $qryRep .= " AND id_deporte_cat='$id_dep'";
		if($id_cat!="") $qryRep .= " AND id_cat='$id_cat'";
		if($sexo!="") $qryRep .= " AND sexo_cat='$sexo'";
		$resRep = mysql_query($qryRep);
		echo "<table border=1>
				<tr>
					<th>Deporte / Escuela</th>
					<th>Categor&iacutea / Equipo</th>
					<th>Inscritos</th>
					<th>Permitidos</th>
					<th>Disponibles</th>
				</tr>";
		while($rowRep = mysql_fetch_array($resRep)){
			$arrEquipInsc = getValues($conexion, "vwequipos_inscritos", "*", "id_cat_equip='".$rowRep["id_cat"]."'");
			echo "<tr class='categoria'>
					<td>".$rowRep["nombre_dep"]."</td>
					<td>".$rowRep["nombre_cat"]." - ".$rowRep["rama_cat"]."</td>
					<td>".$arrEquipInsc["no_equipos"]."</td>
					<td>".$arrEquipInsc["total_lugares"]."</td>
					<td>".($arrEquipInsc["total_lugares"]-$arrEquipInsc["no_equipos"])."</td>
				</tr>";
				$qryEquip = "SELECT * FROM vwequipos WHERE id_cat_equip = '".$rowRep["id_cat"]."'";
				$resEquip = mysql_query($qryEquip);
				while($rowEquip = mysql_fetch_array($resEquip)){
					$alumInsc = getValue($conexion, "vwinscripcion_equipos", "COUNT(id_insc)", "id_equip='".$rowEquip["id_equip"]."'");
					$auxIncompleto = "";
					if($alumInsc<$rowEquip["num_min_part_dep"]) $auxIncompleto = "style='background: rgb(240, 146, 111);'";
					echo "<tr>
							<td>".$rowEquip["nombre_esc"]."</td>
							<td>".$rowEquip["nombre_equip"]."</td>
							<td>".$alumInsc."</td>
							<td>".$rowEquip["num_max_part_dep"]."</td>
							<td $auxIncompleto>".($rowEquip["num_max_part_dep"]-$alumInsc)."</td>
						</tr>";
					$contadorEquip++;
					$contadorAlumn+=$alumInsc;
				}
		}
		echo "</table>";
		echo "<b>Total de equipos:</b> " . $contadorEquip;
		echo "<br><b>Total de alumnos:</b> " . $contadorAlumn;
		break;
	
	case 2:
		$qryRep = "SELECT * FROM vwrep_participaciones WHERE participa=1";
		if($id_dep!="") $qryRep .= " AND id_deporte_cat='$id_dep'";
		if($id_cat!="") $qryRep .= " AND id_cat='$id_cat'";
		if($id_esc!="") $qryRep .= " AND esc_id_alum='$id_esc'";
		if($sexo!="") $qryRep .= " AND sexo_cat='$sexo'";
		
		$resRep = mysql_query($qryRep);
		echo "<table border=1>
				<tr>
					<th>Escuela</th>
					<th>Alumno</th>
					<th>CURP</th>
					<th>Fecha Nac.</th>
					<th>Deporte</th>
					<th>Categoria</th>
					<th>Rama</th>
					<th colspan=5>Pruebas</th>
				</tr>";
		$auxIdEsc = 0;
		$auxIdCat = 0;
		while($rowRep = mysql_fetch_array($resRep)){
			if($auxIdEsc != $rowRep["esc_id_alum"]){
				echo "<tr class='escuela'>
						<td colspan=7 align='left'>".$rowRep["nombre_esc"]."</td>
					</tr>";
				$auxIdEsc = $rowRep["esc_id_alum"];
				$auxIdCat = 0;
			}
			if($auxIdCat != $rowRep["id_cat"]){
				echo "<tr class='categoria'>
						<td colspan=7 align='right'>".$rowRep["nombre_dep"]." ".$rowRep["nombre_cat"]."-".$rowRep["rama_cat"]."</td>";
				$qryPru = "SELECT * FROM vwpruebas WHERE aplica=1 AND id_cat='".$rowRep["id_cat"]."'";
				$resPru = mysql_query($qryPru);
				while($rowPru = mysql_fetch_array($resPru)){
					echo "<td>".$rowPru["prueba"]."</td>";
				}
				echo "</tr>";
				$auxIdCat = $rowRep["id_cat"];
			}
			echo "<tr>
					<td>".$rowRep["nombre_esc"]."</td>
					<td>".$rowRep["nombre_completo_alum"]."</td>
					<td>".$rowRep["curp_alum"]."</td>
					<td>".$rowRep["fecha_nac_alum"]."</td>
					<td>".$rowRep["nombre_dep"]."</td>
					<td>".$rowRep["nombre_cat"]."</td>
					<td>".$rowRep["rama_cat"]."</td>";
			$contadorAlumn++;
			$qryPru = "SELECT * FROM vwpruebas WHERE aplica=1 AND id_cat='".$rowRep["id_cat"]."'";
			$resPru = mysql_query($qryPru);
			while($rowPru = mysql_fetch_array($resPru)){
				$alumPart = getValue($conexion, "participaciones", "participa", "participa=1 AND id_pru_cat='".$rowPru["id_pru_cat"]."' AND id_alum='".$rowRep["id_alum"]."'");
				$auxPalomita = "";
				if($alumPart==1) $auxPalomita = "<span style='font-family:Wingdings'>&uuml;</span>";
				echo "<td>$auxPalomita</td>";
			}
			echo "</tr>";
		}
		echo "</table>";
		echo "<b>Total de alumnos:</b> " . $contadorAlumn;
		break;
		
}
?>