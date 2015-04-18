<?php
session_start();
include "../../conexion.php";
include "../functions.php";
$id_dep		= $_POST["id_dep"];
$sexo		= $_POST["sexo"];
$id_cat		= $_POST["id_cat"];
$id_equip	= $_POST["id_equip"];
$nombre_esc	= getValue($conexion, "escuelas", "nombre_esc", "id_esc='" . $_SESSION["esc_id_usr"] . "'");
$tipo_dep	= getValue($conexion, "deportes", "id_tipo_dep", "id_dep='" . $id_dep . "'");

switch($tipo_dep){
	case 1:
		$qryRep = "SELECT DISTINCT nombre_completo_alum, nombre_dep, nombre_cat, rama_alum FROM vwparticipaciones WHERE esc_id_alum = " . $_SESSION["esc_id_usr"];
		break;
	case 2:
		$qryRep = "SELECT * FROM vwinscripcion_equipos WHERE esc_id_alum = " . $_SESSION["esc_id_usr"];
		break;
}

if($id_dep != "") $qryRep .= " AND id_deporte_cat = '$id_dep'";
if($sexo != "") $qryRep .= " AND sexo_cat = '$sexo'";
if($id_cat != "") $qryRep .= " AND id_cat = '$id_cat'";
if($id_equip != "") $qryRep .= " AND id_equip = '$id_equip'";

$qryRep .= " ORDER BY id_deporte_cat, sexo_cat, id_cat, anio_max_cat, nombre_completo_alum";

$resRep = mysql_query($qryRep);
$auxTotal = mysql_num_rows($resRep);
echo "<h2>Escuela: $nombre_esc</h2>";
?>
<table border=1>
<tr>
	<th>Alumno</th>
	<th>Deporte</th>
	<th>Categoria</th>
	<th>Rama</th>
	<th>Equipo</th>
</tr>
<?php
while ($rowRep = mysql_fetch_array($resRep)){
	$nombre_completo_alum = $rowRep["nombre_completo_alum"];
	$nombre_dep = $rowRep["nombre_dep"];
	$nombre_cat = $rowRep["nombre_cat"];
	$rama_cat = $rowRep["rama_alum"];
	$nombre_equip = $rowRep["nombre_equip"];
	if($nombre_equip=="") $nombre_equip="-";
	
	echo "	<tr>
				<td>$nombre_completo_alum</td>
				<td>$nombre_dep</td>
				<td>$nombre_cat</td>
				<td>$rama_cat</td>
				<td>$nombre_equip</td>
			</tr>";
}
?>
</table><br>
<h5>Total de Alumnos: <?php echo $auxTotal; ?></h5>