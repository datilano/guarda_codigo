<?php
// ************************************************************************
// Archivo: 		Funciones.php
// Descripcion: 	Funciones generales de la aplicación
// Autor: 			Daniel Alberto Atilano Rosales
//					datilano26@gmail.com
// ************************************************************************

// ********************************************************
// Muestra la última actualización a la aplicación
// ********************************************************


//function getValue -> Trae un valor de la base de datos
//$cnn -> Conexion con la BD
//$tabla -> Tabla de donde se quiere traer el valor
//$column -> Columna seleccionada para traer el valor
//$where -> Clausula WHERE que traera el valor de la columna
//NOTA: OJO solo trera el primer valor que encuentre
function getValue($cnn, $tabla, $columna, $where){
	$qryValue = "SELECT $columna FROM $tabla WHERE $where";
	$resValue = mysql_query($qryValue, $cnn);
	$rowValue = mysql_fetch_array($resValue);
	if(!$resValue){
		echo $qryValue.mysql_error();
	}
	return $rowValue[0];
}

//function getValues -> Retorna arreglo de la BD
//$cnn -> Conexion con la BD
//$tabla -> Tabla de donde se quiere traer el valor
//$column -> Columna seleccionada para traer el valor
//$where -> Clausula WHERE que traera el valor de la columna
//NOTA: Regresa arreglo no variables, funciona para un solo renglon
function getValues($cnn, $tabla, $columna, $where){
	if($where != "") $where = "WHERE $where";
	$qryValue = "SELECT $columna FROM $tabla $where";
	$resValue = mysql_query($qryValue, $cnn);
	$rowValue = mysql_fetch_array($resValue);
	return $rowValue;
}



function InfoUltimaActualizacion () {
	$archivo = 'funciones.php';
	if (file_exists($archivo)) {
    	echo date("F d Y H:i:s.", fileatime($archivo));
	}
}
// ********************************************************
// Muestra la versión actual de la aplicación
// ********************************************************
function InfoVersionActual () {
	global $link;
	$query		= "SELECT * FROM `sistema`";
	$result		= mysql_query($query, $link);
	$row 		= mysql_fetch_array($result);
	echo $row['Sistema_Version'];
}
// ********************************************************
// Muestra el usuario que se ha seleccionado
// ********************************************************
function InfoActual ($nTabla,$nCampoLlave,$nCampoMostrar1,$nCampoMostrar2,$nCampoMostrar3,$nSeparador) {
	global $link,$link2;
	if(isset($_SESSION['ValorLlave'])) {
		$ValorLlave = $_SESSION['ValorLlave']; 
	} else {
		$ValorLlave = '';
	}
	$query		= "SELECT * FROM " . $nTabla . " WHERE " . $nCampoLlave . "=" . $ValorLlave;
	$result		= mysql_query($query, $link);
	$row 		= mysql_fetch_array($result);
	$IdUsuario 	= $ValorLlave;
	$cMostrar 	= $row[$nCampoMostrar1];
	if($nCamposMostrar2<>"") { $cMostrar = $cMostrar . $nSeparador . $row[$nCampoMostrar2]; }
	if($nCamposMostrar3<>"") { $cMostrar = $cMostrar . $nSeparador . $row[$nCampoMostrar3]; }
	echo "<b><span class='LetraNormalAzul'>" . $cMostrar . "</span></b>";
}
// ********************************************************
// Muestra el estatus de un registro (Activo/Inactivo)
// ********************************************************
function InfoStatus ($nTabla,$nCampoLlave,$nCampoEstatus) {
	global $link,$link2;
	$ref	= "ejecuta.funcion.php?Funcion=CambiaStatus(" . $nTabla . "," . $nCampoLlave . "," . $nCampoEstatus;
	if(isset($_SESSION['ValorLlave'])) {
		$ValorLlave = $_SESSION['ValorLlave']; 
	} else {
		$ValorLlave = '';
	}
	$query	= "SELECT * FROM " . $nTabla . " WHERE " . $nCampoLlave . "=" . $ValorLlave;
	$result	= mysql_query($query, $link);
	$row 	= mysql_fetch_array($result);
	$Status	= $row[$nCampoEstatus];
	if($Status==1) {
		$ref	= $ref . ",0)";
		echo "<span class='LetraNormal'><img src='iconos/icono.activo.gif'> Activo</span> &nbsp;  &nbsp;[ <a href='$ref'><span class='LetraNormalAzul'>Desactivar</span></a> ]";
	} else {
		$ref	= $ref . ",1)";
		echo "<span class='LetraNormal'><img src='iconos/icono.inactivo.gif'> Inactivo</span> &nbsp;  &nbsp;[ <a href='$ref'><span class='LetraNormalAzul'>Activar</span></a> ]";
	}
}

// ********************************************************
// Encripta una cadena
// ********************************************************
function Encripta($string) { 
	$crypted = crypt(md5($string), md5($string));
	return $crypted;
}
// ********************************************************
// Cambia el status de un Registro (Activo / Inactivo)
// ********************************************************
function CambiaStatus ($cTabla,$cCampoLlave,$cCampoEstatus,$cValorEstatus) {
	global $link,$link2;
	if(isset($_SESSION['ValorLlave'])) {
		$ValorLlave = $_SESSION['ValorLlave']; 
	} else {
		$ValorLlave = '';
	}
	$query		= "SELECT * FROM " . $cTabla . " WHERE " . $cCampoLlave . "=" . $ValorLlave;
	$result		= mysql_query($query, $link);
	$row 		= mysql_fetch_array($result);
	$Status = 	$row[$cCampoEstatus];
	if($Status == 0) {$Status = 1;} else {$Status = 0;}
	$query2		= "UPDATE " . $cTabla . " SET " . $cCampoEstatus . "=" . $cValorEstatus . " WHERE " . $cCampoLlave . "=" . $ValorLlave;
	$result2	= mysql_query($query2, $link);
	$IdPantalla = $_SESSION['idpantallaactual'];
	echo "<script>
		window.location.replace('dibuja.pantalla.php?IdPantalla=$IdPantalla&ValorLlave=$ValorLlave', '_self');
	</script>";
}
// ********************************************************
// Muestra el nombre del sistema
// ********************************************************
function NombreSistema () {
	global $link;
	$query	= "SELECT * FROM `sistema`";
	$result	= mysql_query($query, $link);
	$row 	= mysql_fetch_array($result);
	$Nombre = $row["Sistema_Nombre"];
	echo $Nombre;
}
// ********************************************************
// Muestra la versión del sistema
// ********************************************************
function VersionSistema () {
	global $link;
	$query		= "SELECT * FROM `sistema`";
	$result		= mysql_query($query, $link);
	$row 		= mysql_fetch_array($result);
	$Version 	= $row["Sistema_Version"];
	echo $Version;
}
// ********************************************************
// Muestra la fecha de la ultima revision del sistema
// ********************************************************
function RevisionSistema () {
	global $link;
	$query	= "SELECT * FROM `sistema`";
	$result	= mysql_query($query, $link);
	$row 	= mysql_fetch_array($result);
	$UltRev	= $row["Sistema_UltimaRevision"];
	$Fmto 	= 'd/m/Y';
	echo gmdate($Fmto, strtotime($UltRev));
}
// ********************************************************
// Muestra la informacion de Derechos Reservados
// ********************************************************
function CopyRights () {
	global $link;
	$query		= "SELECT * FROM `sistema`";
	$result		= mysql_query($query, $link);
	$row 		= mysql_fetch_array($result);
	$CopyRights = $row["Sistema_Copyright"];
	echo $CopyRights;
}
// ********************************************************
// Muestra la empresa que utiliza el sistema
// ********************************************************
function Empresa () {
	global $link;
	$query		= "SELECT * FROM `sistema`";
	$result		= mysql_query($query, $link);
	$row 		= mysql_fetch_array($result);
	$Empresa 	= $row["Sistema_Empresa"];
	echo $Empresa;
}
// ********************************************************
// Agrega una transacción realizada por la aplicación
// ********************************************************
function AgregaTransaccion ($IdUsuario,$IdAccion,$vTabla,$vTarea,$vValorLlave) {
	global $link;
	$query	= "INSERT INTO transacciones (Transacciones_IdUsuario,Transacciones_IdAccion,Transacciones_Tabla,Transacciones_Registro,Transacciones_IdTarea,
		Transacciones_FechaHora) VALUES (" . $IdUsuario . "," . $IdAccion . ",'" . $vTabla . "','" . $vValorLlave . "'," . $vTarea . ",now())";
	$result	= mysql_query($query, $link) or die(mysql_error());
}
// ********************************************************
// Cambia una fecha a formato de MySQL para su grabado
// ********************************************************
function FechaAMySQL($DatoFecha, $Formato) { 
	if($DatoFecha == '0000-00-00 00:00:00' or $DatoFecha == NULL or $DatoFecha == '00-00-0000') { 
		$FechaConvertida = '00-00-0000';
	} else {
		$FechaConvertida = date($Formato, strtotime($DatoFecha));
	}
	return $FechaConvertida;
}
// ********************************************************
// Cambia una fecha a formato de MySQL para su grabado
// ********************************************************
function FechaMySQL($DatoFecha, $Formato) { 
	$DatoFecha = str_replace("/","-",$DatoFecha);
	echo "Fecha sin diagonales: " . $DatoFecha . "<br>";
	if($DatoFecha == '0000-00-00 00:00:00' or $DatoFecha == NULL or $DatoFecha == '00-00-0000') { 
		$FechaConvertida = '0000-00-00';
	} else {
		// Convertimos la fecha a formato MM-DD-AAAA
//		$DatoFecha = substr($DatoFecha,3,2) . "-" . substr($DatoFecha,
		$FechaConvertida = date($Formato, strtotime($DatoFecha));
	}
	return $FechaConvertida;
}
// ********************************************************
// Cambia una fecha a formato de MySQL AAAA-MM-DD HH:MM:SS
// ********************************************************
function FechaDeMySQL($DatoFecha) { 
	if($DatoFecha == '0000-00-00 00:00:00' or $DatoFecha == NULL) { 
		$FechaConvertida = '';
	} else { 
		$FechaConvertida = date('Y-m-d H:i:s', strtotime($DatoFecha));
	}
	return $FechaConvertida;
}
// ****************************************************************************
// Función para convertir una fecha a formato largo
// ****************************************************************************
function FechaLarga ($FechaConvertir) {
	// Obtiene el día de la semana
	$FechaConvertir = strtotime($FechaConvertir);
	$DiaSemana = date("D",$FechaConvertir);
	switch ($DiaSemana) {
		case 'Mon':
			$DiaSem = 'Lunes';
			break;
		case 'Tue':
			$DiaSem = 'Martes';
			break;
		case 'Wed':
			$DiaSem = 'Miércoles';
			break;
		case 'Thu':
			$DiaSem = 'Jueves';
			break;
		case 'Fri':
			$DiaSem = 'Viernes';
			break;
		case 'Sat':
			$DiaSem = 'Sábado';
			break;
		case 'Sun':
			$DiaSem = 'Domingo';
			break;
	}
	// Obtiene el nombre del mes
	$Mes3 = date("M",$FechaConvertir);
	switch ($Mes3) {
		case 'Jan':
			$NomMes = 'Enero';
			break;
		case 'Feb':
			$NomMes = 'Febrero';
			break;
		case 'Mar':
			$NomMes = 'Marzo';
			break;
		case 'Apr':
			$NomMes = 'Abril';
			break;
		case 'May':
			$NomMes = 'Mayo';
			break;
		case 'Jun':
			$NomMes = 'Junio';
			break;
		case 'Jul':
			$NomMes = 'Julio';
			break;
		case 'Aug':
			$NomMes = 'Agosto';
			break;
		case 'Sep':
			$NomMes = 'Septiembre';
			break;
		case 'Oct':
			$NomMes = 'Octubre';
			break;
		case 'Nov':
			$NomMes = 'Noviembre';
			break;
		case 'Dec':
			$NomMes = 'Diciembre';
			break;
	}
	// Formatea la fecha a día-mes-año
	$FechaConFormato = $DiaSem . " " . date("d",$FechaConvertir) . " de " . $NomMes . " de " . date("Y",$FechaConvertir);
	return $FechaConFormato;
}
// ********************************************************
// Obtiene la diferencia entre 2 fechas
// ********************************************************
function RestaFechas($Fecha1,$Fecha2) {
	if($Fecha1=='' or $Fecha1=='0000-00-00 00:00:00' or $Fecha1=='00-00-0000' or $Fecha2=='' or 
		$Fecha2=='0000-00-00 00:00:00' or $Fecha2=='00-00-0000') {
		return -1;
	} else {
		$f1 = strtotime($Fecha1);
		$f2 = strtotime($Fecha2);
		$df = $f2 - $f1;
		return $df;
	}
}
// *************************************
// Convierte un número decimal a binario
// *************************************
function DecimalABinario($nDecimal) {
	$cadbin = decbin($nDecimal);
	$longit	= strlen($cadbin);
	$nueva 	= "";
	$z = 0;
	do {
		$nueva = substr($cadbin,$z,1) . $nueva;
		$z = $z + 1;
	} while($z<=$longit);
	$ceros	= "";
	if($longit<7) {
		$ceros = str_repeat("0",(7-$longit));
	}
	$cadbin	= $nueva . $ceros;
	return $cadbin;
}
// *************************************
// Convierte un número binario a decimal
// *************************************
function BinarioADecimal($cBinario) {
	$longit	= strlen($cBinario);
	$nueva 	= "";
	$z = 0;
	do {
		$nueva = substr($cBinario,$z,1) . $nueva;
		$z = $z + 1;
	} while($z<=$longit);
	$ceros	= "";
	if($longit<7) {
		$ceros = str_repeat("0",(7-$longit));
	}
	$numero	= $nueva . $ceros;
	$nDecimal = bindec($numero);
	return $nDecimal;
}
// ********************************
// Verifica la validez de una fecha
// ********************************
function VerificaFecha($data) {
    if (date('Y-m-d H:i:s', strtotime($data)) == $data) {
        return true;
    } else {
        return false;
    }
}
// ********************************
// Determina el color de la lista
// ********************************
function ColorUL($nColor) {
	$theme = "a";
	switch($nColor) {
		case 1:
			$theme = "a";
			break;
		case 2:
			$theme = "b";
			break;
		case 3:
			$theme = "c";
			break;
		case 4:
			$theme = "e";
			break;
		case 5:
			$theme = "d";
			break;
		case 6:
			$theme = "a";
			break;
		case 7:
			$theme = "b";
			break;
		case 8:
			$theme = "c";
			break;
		case 9:
			$theme = "e";
			break;
		case 10:
			$theme = "d";
			break;
		case 11:
			$theme = "a";
			break;
		case 12:
			$theme = "b";
			break;
		case 13:
			$theme = "c";
			break;
		case 14:
			$theme = "e";
			break;
		case 15:
			$theme = "d";
			break;
		case 16:
			$theme = "a";
			break;
		case 17:
			$theme = "b";
			break;
		case 18:
			$theme = "c";
			break;
		case 19:
			$theme = "e";
			break;
		case 20:
			$theme = "d";
			break;
	}
	return $theme;
}
// ********************************************************
// Agrega diagonales en donde encuentre comillas sencillas
// ********************************************************
function Comillas($cadena) {
	$cadena_nueva = str_replace("'","\'",$cadena);
	return $cadena_nueva;
}


// *********************************
// Valida si trae valor para regresar la cadena correcta
// *********************************
function validaObjeto($auxValue, $auxType) {
	switch($auxType){
		case "length":
			if($auxValue>0){
				$auxValue = "maxlength='" . $auxValue . "'";
			}else{
				$auxValue = "";
			}
			break;
		case "required":
			if($auxValue == 1){
				$auxValue = "required";
			}else{
				$auxValue = "";
			}
			break;
		case "placeholder":
			if($auxValue <> ""){
				$auxValue = "placeholder='" . $auxValue . "'";
			}else{
				$auxValue = "";
			}
			break;
		case "value":
			if(strval($auxValue) == "" or $auxValue<0){
				$auxValue = "";
			}else{			
				$auxValue = "value='" . $auxValue . "'";
			}
			break;
		case "min":
			if(isset($auxValue)){
				$auxValue = "min='" . $auxValue . "'";
			}else{			
				$auxValue = "";
				
			}
			break;
		case "max":
			if(isset($auxValue)){
				$auxValue = "max='" . $auxValue . "'";
			}else{			
				$auxValue = "";				
			}
			break;
		case "close":
			if($auxValue){
				$auxValue = "nobottomborder";
			}else{			
				$auxValue = "bottomborder";				
			}
			break;
			
	}
	return $auxValue;
}

// *********************************
// Dibuja un control de tipo textbox
// *********************************
function DibujaTextBox($oNombre,$oTitulo,$oMaxLen,$oObjReq,$opHolder,$oValor,$closeForm) {
	$oMaxLen = validaObjeto($oMaxLen, "length");
	$oObjReq = validaObjeto($oObjReq, "required");
	$opHolder = validaObjeto($opHolder, "placeholder");
	$oValor = validaObjeto($oValor, "value");
	$closeForm = validaObjeto($closeForm, "close");
	echo "
		<div class='input $closeForm'>
			<div class=inputtext>$oTitulo: </div>
			<div class=inputcontent>
				<input type='text' name='" . $oNombre . "' id='" . $oNombre . "' " . $oValor . " " . $oMaxLen . " " . $opHolder . " " . $oObjReq . "/>
			</div>
		</div>";
	return;
}
// *********************************
// Dibuja un control de tipo number
// *********************************
function DibujaNumber($oNombre,$oTitulo,$oMaxVal,$oMinVal,$oObjReq,$opHolder,$oValor,$closeForm) {
	$oMinVal = validaObjeto($oMinVal, "min");
	$oMaxVal = validaObjeto($oMaxVal, "max");
	$oObjReq = validaObjeto($oObjReq, "required");
	$opHolder = validaObjeto($opHolder, "placeholder");
	$oValor = validaObjeto($oValor, "value");
	$closeForm = validaObjeto($closeForm, "close");
	echo "
		<div class='input $closeForm'>
			<div class=inputtext>$oTitulo: </div>
			<div class=inputcontent>
				<input type='number' name='" . $oNombre . "' id='" . $oNombre . "' " . $oValor . " " . $oMaxVal . " " . $oMinVal . " " . $opHolder . " " . $oObjReq . "/>
			</div>
		</div>";
	return;
}
// **********************************
// Dibuja un control de tipo textarea
// **********************************
function DibujaTextArea($oNombre,$oTitulo,$oObjReq,$opHolder,$oValor,$closeForm) {
	$oMaxLen = validaObjeto($oMaxLen, "length");
	$oObjReq = validaObjeto($oObjReq, "required");
	$opHolder = validaObjeto($opHolder, "placeholder");
	$closeForm = validaObjeto($closeForm, "close");
	echo "
		<div class='inputtextbox $closeForm'>
			<div class='inputtext'>$oTitulo: </div>
			<div class='inputcontent'>
				<textarea class='textarea' name='".  $oNombre . "' id='" . $oNombre . "' " . $oObjReq . " " . $opHolder  . ">" . $oValor . "</textarea>
			</div>
		</div>";
	return;
}
// **********************************
// Dibuja un control de tipo checkbox
// **********************************
function DibujaCheckBox($otOpcion,$ocIdOpcion,$ocMostrar,$oNombre,$oTitulo,$oObjReq,$oValor,$oConexion) {
	global $link,$link2;
	echo "
		<fieldset data-role='controlgroup'>
		<legend>" . $oTitulo . "</legend>";
	$qrychk		= "SELECT * FROM " . $otOpcion . " ORDER BY " . $ocIdOpcion;
	if($oConexion==2) {
		$reschk	= mysql_query($qrychk,$link2);
	} else {
		$reschk	= mysql_query($qrychk,$link);
	}
	$rowchk		= mysql_fetch_array($reschk);
	$noobj		= 0;
	$oValor		= DecimalABinario($oValor);
	echo "
		<table><tr>";
	do {
		$nomchk = $oNombre . $noobj;
		if(substr($oValor,$noobj,1)=="1") { $marcado = "checked"; } else { $marcado = ""; }
		$nomOpcion = htmlentities(trim($rowchk[$ocMostrar]));
		echo "
			<input type='checkbox' name='" . $nomchk . "' id='" . $nomchk . "' " . $marcado . " class='custom' " . $oObjReq . "/>
			<label for='" . $nomchk . "'>" . $nomOpcion . "</label>";
			$noobj = $noobj + 1;
	} while($rowchk=mysql_fetch_array($reschk));
	echo "
		</fieldset>";
	return;
}
// **************************************
// Dibuja un control de tipo radio button
// **************************************
function DibujaRadioButton($otOpcion,$ocIdOpcion,$ocMostrar,$oNombre,$oTitulo,$oObjReq,$oValor,$oConexion,$oModo) {
	global $link,$link2;
	echo "
		<fieldset data-role='controlgroup' data-type='horizontal'>
		<legend>" . $oTitulo . "</legend>";
	$qryrad		= "SELECT * FROM " . $otOpcion . " ORDER BY " . $ocIdOpcion;
	if($oConexion==2) {
		$resrad	= mysql_query($qryrad,$link2);
	} else {
		$resrad	= mysql_query($qryrad,$link);
	}
	$rowrad		= mysql_fetch_array($resrad);
	$noobj		= 0;
	do {
		$nomRad		= $oNombre . $noobj;
		$nomOpcion 	= htmlentities(trim($rowrad[$ocMostrar]));
		if($oModo==1) {
			if($rowrad[$cIdOpcion]==trim($rowdatos[$cOrigen])) {
				$marcado = "checked='checked'";
			} else {
				$marcado = "";
			}
		} else {
			$marcado = "";
		}
		echo "
			<input type='radio' name='" . $oNombre . "' id='" . $nomRad . "' " . $marcado . " value='" . $rowrad[$ocIdOpcion] . "' " . $oObjReq . "/>
			<label for='" . $nomRad . "'>" . $nomOpcion . "</label>";
		$noobj = $noobj + 1;
	} while($rowrad=mysql_fetch_array($resrad));
	echo "
		</fieldset>";
	return;
}
// **********************************
// Dibuja un control de tipo password
// **********************************
function DibujaPassword($oNombre,$oTitulo,$oMaxLen,$oObjReq,$opHolder,$oValor,$closeForm) {
	$oMaxLen = validaObjeto($oMaxLen, "length");
	$oObjReq = validaObjeto($oObjReq, "required");
	$opHolder = validaObjeto($opHolder, "placeholder");
	$oValor = validaObjeto($oValor, "value");
	$closeForm = validaObjeto($closeForm, "close");
	echo "
		<div class='input $closeForm'>
			<div class=inputtext>$oTitulo: </div>
			<div class=inputcontent>
				<input type='password' class='txt_pwd' name='" . $oNombre . "' id='" . $oNombre . "' " . $oValor . " " . $oMaxLen . " " . $opHolder . " " . $oObjReq . "/><br><br>
				<a href='#' id='gen_pwd'>Generar Contrase&ntilde;a</a>
			</div>
		</div>";
	return;
}

// **********************************
// Dibuja un control de tipo hidden
// **********************************
function DibujaHidden($oNombre,$oValor) {
	echo "<input type='hidden' name='" . $oNombre . "' id='" . $oNombre . "' value='" . $oValor . "' />";
	return;
}

// **********************************
// Dibuja un control de tipo combobox
// $otOpcion -> Nombre de la tabla
// $ocIdOpcion -> Columna del valor
// $ocMostrar -> Columna mostrada
// $ocOrigen -> 
// $oCargar -> 
// $ocPivote -> 
// $ooPivote -> 
// $oNombre -> Nombre del input
// $oTitulo -> Titulo del input
// $oObjReq -> Input requerido
// $opHolder -> Placeholder del input
// $oValor -> El valor
// $oConexion -> Conexion para consultar datos
// $oModo ->
// **********************************
function DibujaComboBox($otOpcion,$ocIdOpcion,$ocMostrar,$ocOrigen,$oCargar,$ocPivote,$ooPivote,$oNombre,$oTitulo,$oObjReq,$opHolder,$oValor,$oConexion,$oModo,$closeForm,$arrOption = null) {
	$closeForm = validaObjeto($closeForm, "close");
	echo "	<div id='div_" . $oNombre . "' class='input $closeForm'>
				<div class='inputtext'>" . $oTitulo . ": </div>
				<div class='inputcontent'>
					<select name='" . $oNombre . "' id='" . $oNombre . "' " . $oObjReq  . ">";
	if($opHolder!="") {
		echo "
			<option value='' data-placeholder='true'>" . $opHolder . "</option>";
	}

	if($oCargar==1) {
		if(is_null($arrOption)) {
			$qrycbo	= "SELECT * FROM " . $otOpcion . " ORDER BY " . $ocMostrar;
			$rescbo	= mysql_query($qrycbo,$oConexion);
			while($rowcbo=mysql_fetch_array($rescbo)) {
				$marcado = "";
				if($rowcbo[$ocIdOpcion]==$oValor) $marcado = "selected";
				$nomOpcion = trim($rowcbo[$ocMostrar]);
				echo " <option value=" . $rowcbo[$ocIdOpcion] . " " . $marcado . ">" . $nomOpcion . "</option>";
			}
		}else{
			foreach($arrOption as $key => $value){
				if($key==$oValor) { $marcado = "selected='selected'"; } else { $marcado = ""; }
				$nomOpcion = trim($value);
				echo " <option value=" . $key . " " . $marcado . ">" . $nomOpcion . "</option>";
			}
		}
	} else {
		if($oModo==1) {
			/*$qrycmb 	= "SELECT * FROM " . $otOpcion . " WHERE " . $ocIdOpcion . "='" . $ocOrigen . "'";
			$rescmb 	= mysql_query($qrycmb,$oConexion);
			$rowcmb 	= mysql_fetch_array($rescmb);
			$numcmb 	= mysql_num_rows($rescmb);
			if($numcmb>0) {
				$vPivote = $rowcmb[$ocPivote];
			} else {
				$vPivote = 0;
			}*/
			$qrycbo	= "SELECT * FROM " . $otOpcion . " WHERE " . $ocPivote . "=" . $ooPivote . " ORDER BY " . $ocMostrar;
			$rescbo	= mysql_query($qrycbo,$oConexion);
			while($rowcbo=mysql_fetch_array($rescbo)) {
				if($rowcbo[$ocIdOpcion]==$oValor) { $marcado = "selected='selected'"; } else { $marcado = ""; }
				$nomOpcion = trim($rowcbo[$ocMostrar]);
				echo "
				<option value=" . $rowcbo[$ocIdOpcion] . " " . $marcado . ">" . $nomOpcion . "</option>";
			}
		}
	}
	echo "
			</select>
		</div>
	</div>";
	return;
}
// *******************************
// Dibuja un control de tipo fecha
// *******************************
function DibujaFecha($oNombre,$oTitulo,$oMaxLen,$oObjReq,$opHolder,$oValor,$closeForm) {
	$oMaxLen = validaObjeto($oMaxLen, "length");
	$oObjReq = validaObjeto($oObjReq, "required");
	$opHolder = validaObjeto($opHolder, "placeholder");
	$oValor = validaObjeto($oValor, "value");
	$closeForm = validaObjeto($closeForm, "close");
	echo "
		<div class='input $closeForm'>
			<div class=inputtext>$oTitulo: </div>
			<div class=inputcontent>
				<input type='date' name='" . $oNombre . "' id='" . $oNombre . "' " . $oValor . " " . $oMaxLen . " " . $opHolder . " " . $oObjReq . "/>
			</div>
		</div>";
	return;
}
// ************************************
// Dibuja un control de tipo fecha-hora
// ************************************
function DibujaFechaHora($oNombre,$oTitulo,$oObjReq,$oValor,$oConexion) {
	if($oValor!="") {
		$vDia 	= substr($oValor,8,2);
		$vMes	= substr($oValor,5,2);
		$vAno	= substr($oValor,0,4);
		$vHora  = substr($oValor,11,2);
		$vMinu  = substr($oValor,14,2);
	} else {
		$vDia	= "";
		$vMes	= "";
		$vAno	= "";
		$vHora	= "";
		$vMinu	= "";
	}
	// Dibuja un objeto para el día, uno para el mes y uno para el año
	$idDia = $oNombre . "_dia";
	$idMes = $oNombre . "_mes";
	$idAno = $oNombre . "_ano";
	$idHora= $oNombre . "_hora";
	$idMinu= $oNombre . "_minu";
	echo "
	<fieldset data-role='controlgroup' data-type='horizontal'>
		<legend>" . $oTitulo . "</legend>";
	// Dibuja el control para el mes
	echo "
		<fieldset data-role='controlgroup' data-type='horizontal'>
			<label for='" . $idMes . "'>Mes</label>
			<select name='" . $idMes . "' id='" . $idMes . "'>
				<option>Mes</option>";
	$qrymes	= "SELECT * FROM vistameses ORDER BY Meses_IdMes";
	$resmes = mysql_query($qrymes,$oConexion);
	$rowmes = mysql_fetch_array($resmes);
	do {
		if($vMes==$rowmes["Meses_IdMes"]) { $marcado = "selected='selected'"; } else { $marcado = ""; }
		echo "	<option value='" . $rowmes["Meses_IdMes"] . "' " . $marcado . ">" . $rowmes["Meses_Abreviacion"] . "</option>";
	} while($rowmes=mysql_fetch_array($resmes));
	echo "
			</select>";
	// Dibuja el control para el día
	echo "
			<label for='" . $idDia . "'>D&iacute;a</label>
			<select name='" . $idDia . "' id='" . $idDia . "'>
				<option>D&iacute;a</option>";
	$ddia = 1;
	do {
		if($vDia==$ddia) { $marcado = "selected='selected'"; } else { $marcado = ""; }
		echo "
				<option value='" . $ddia . "' " . $marcado . ">" . $ddia . "</option>";
		$ddia = $ddia + 1;
	} while($ddia<=31);
	echo "
			</select>";
	// Dibuja el control para el año
	$aano = date("Y");
	$atope = $aano - 80;
	echo "
			<label for='" . $idAno . "'>D&iacute;a</label>
			<select name='" . $idAno . "' id='" . $idAno . "'>
				<option>A&ntilde;o</option>";
	do {
		if($vAno==$aano) { $marcado = "selected='selected'"; } else { $marcado = ""; }
		echo "
				<option value='" . $aano . "' " . $marcado . ">" . $aano . "</option>";
		$aano = $aano - 1;
	} while($aano>=$atope);
	echo "
			</select>
		</fieldset>
		<fieldset data-role='controlgroup' data-type='horizontal'>";
	// Dibuja el control para la hora
	echo "
			<label for='" . $idHora . "'>Hora</label>
			<select name='" . $idHora . "' id='" . $idHora . "'>
				<option>Hora</option>";
	$qryhor	= "SELECT * FROM vistahoras ORDER BY Horas_IdHora";
	$reshor = mysql_query($qryhor,$oConexion);
	$rowhor = mysql_fetch_array($reshor);
	do {
		if($vHora==$rowhor["Horas_Hora"]) { $marcado = "selected='selected'"; } else { $marcado = ""; }
		echo "	<option value='" . $rowhor["Horas_Hora"] . "' " . $marcado . ">" . $rowhor["Horas_Hora"] . "</option>";
	} while($rowhor=mysql_fetch_array($reshor));
	echo "
			</select>";
	// Dibuja el control para los minutos
	echo "
			<label for='" . $idMinu . "'>Minutos</label>
			<select name='" . $idMinu . "' id='" . $idMinu . "'>
				<option>Minutos</option>";
	$qrymin	= "SELECT * FROM vistaminutos ORDER BY Minutos_IdMinuto";
	$resmin = mysql_query($qrymin,$oConexion);
	$rowmin = mysql_fetch_array($resmin);
	do {
		if($vMinu==$rowmin["Minutos_Minutos"]) { $marcado = "selected='selected'"; } else { $marcado = ""; }
		echo "	<option value='" . $rowmin["Minutos_Minutos"] . "' " . $marcado . ">" . $rowmin["Minutos_Minutos"] . "</option>";
	} while($rowmin=mysql_fetch_array($resmin));
	echo "
			</select>
		</fieldset>
	</fieldset>";
	return;
}
// ********************************************
// Función para dibujar una semana de la agenda
// ********************************************
function LlenaArreglosAgenda($iCubiculo,$iLink) {
	global $fechaInicio,$encab,$fechas,$lun,$mar,$mie,$jue,$vie,$sab,$horas,$minut,$idlun,$idmar,$idmie,$idjue,$idvie,$idsab,$link,$link2,$mes,$año;
	$diaSem = date("w",strtotime($fechaInicio));					// Se obtiene el día de la semana (0=Domingo,1=Lunes.....)
	$dia	= date("d",strtotime($fechaInicio));					// Se obtiene el día del mes con dos dígitos
	$mes 	= date("m",strtotime($fechaInicio));					// Se obtiene el mes con dos dígitos
	$año	= date("Y",strtotime($fechaInicio));					// Se obtiene el año con cuatro dígitos
	$ds 	= intval($diaSem);										// Convertimos el día de la semana a entero
	switch($ds) {
		case 0:														// Si el día de la semana es domingo,
			$dn = intval($dia)+1;									// incrementamos del día del mes para
			if($dn<10) { $dn = "0" . $dn; }							// mostrar la siguiente semana
			$fi = date("Y-m-d",strtotime($año."-".$mes."-".$dn));	// Dejamos la fecha de inicio en el sig. lunes
			break;
		case 1:
			$fi = date("Y-m-d",strtotime($año."-".$mes."-".$dia));	// Se queda la fecha igual porque es lunes
			break;
		default:
			$dn = intval($dia);
			for($i=$ds;$i>1;$i--) {									// El día es de martes en adelante, por lo que
				$dn = intval($dn)-1;								// hacemos un ciclo para regresar a el lunes, de
			}														// tal forma que siempre iniciemos en lunes
			if($dn<10) { $dn = "0" . $dn; }
			$fi = date("Y-m-d",strtotime($año."-".$mes."-".$dn));
			break;
	}
	for($i=1;$i<=6;$i++) {
		$ind1	= $i - 1;
		$fechas[$ind1] = date("Y-m-d",strtotime($fi));				// Guardamos la fecha de cada columna
		$año	= date("Y",strtotime($fi));							// tomamos el año de la fecha inicial.
		$mes	= date("m",strtotime($fi));							// tomamos el mes de la fecha inicial.
		$dia 	= date("d",strtotime($fi));							// tomamos el día de la fecha inicial y lo
		$dn		= IncrementarDia($mes,$dia,$año);						// Incrementamos el día
		$ind2	= intval($mes) - 1;
		$encab[$ind1] = $encab[$ind1] . $dia . " " . $meses[$ind2];	// Guardamos el encabezado de cada columna
		$fi		= date("Y-m-d",strtotime($año."-".$mes."-".$dn));	// Creamos la siguiente fecha
	}
	// Buscamos los eventos en la tabla agenda, por fecha y horario
	$base = 4;
	for($x=0;$x<6;$x++) {											// Hacemos un ciclo para cada columna
		for($y=0;$y<13;$y++) {										// y hacemos otro ciclo para cada hora
			for($z=0;$z<4;$z++) {									// y por último, uno para cada fracción de hora
				$ind = ($base * $y) + $z;
				if($iCubiculo==1) {
					$qry = "SELECT * FROM vistaagenda WHERE Agenda_HoraInicio='" . $fechas[$x] . " " . $horas[$y] . ":" . $minut[$z] . ":00'";
				} else {
					$qry = "SELECT * FROM vistaagenda WHERE Agenda_HoraInicio='" . $fechas[$x] . " " . $horas[$y] . ":" . $minut[$z] . 
								":00' AND Agenda_IdCubiculo=" . $iCubiculo;
				}
				$res = mysql_query($qry,$iLink);
				$row = mysql_fetch_array($res);
				$num = mysql_num_rows($res);
				if($num>0) {
					do {
						if($row["Pacientes_ApellidoPaterno"]!=NULL) {
							$desc = htmlentities(trim($row["Pacientes_Nombre"])) . " " . htmlentities(trim($row["Pacientes_ApellidoPaterno"]));
						} else {
							$desc = htmlentities(trim($row["Agenda_Asunto"])) . " " . htmlentities(trim($row["Agenda_Ubicacion"]));
						}
						$id = $row["Agenda_IdAgenda"];
						switch($x) {
							case 0:
								$lun[$ind] = $lun[$ind] . $desc . " ";
								$idlun[$ind] = $id;
								break;
							case 1:
								$mar[$ind] = $mar[$ind] . $desc . " ";
								$idmar[$ind] = $id;
								break;
							case 2:
								$mie[$ind] = $mie[$ind] . $desc . " ";
								$idmie[$ind] = $id;
								break;
							case 3:
								$jue[$ind] = $jue[$ind] . $desc . " ";
								$idjue[$ind] = $id;
								break;
							case 4:
								$vie[$ind] = $vie[$ind] . $desc . " ";
								$idvie[$ind] = $id;
								break;
							case 5:
								$sab[$ind] = $sab[$ind] . $desc . " ";
								$idsab[$ind] = $id;
								break;
						}
					} while($row=mysql_fetch_array($res));
				}
			}
		}
	}
	return;
}
// **************************************************************************
// Función para obtener los parámetros de conexión de una empresa determinada
// **************************************************************************
function ConexionEmpresa($iEmpresa) {
	include("conectar.master.php");
	$qrybd 			= "SELECT * FROM basesdatos WHERE BasesDatos_IdEmpresa=" . $iEmpresa;
	$resbd 			= mysql_query($qrybd,$link);
	$rowbd 			= mysql_fetch_array($resbd);
	$eBdNombre	 	= trim($rowbd["BasesDatos_Nombre"]);
	$eBdServidor	= trim($rowbd["BasesDatos_Servidor"]);
	$eBdUsuario		= trim($rowbd["BasesDatos_Usuario"]);
	$eBdPassword	= trim($rowbd["BasesDatos_Password"]);
	$valorRet		= $eBdNombre . "," . $eBdServidor . "," . $eBdUsuario . "," . $eBdPassword;
	return $valorRet;
}
// *******************************
// Función para incrementar un día
// *******************************
function IncrementarDia($iMes,$iDia,$iAnio) {
	global $mes,$año,$fi;
	switch($iMes) {
		case '1':		// Enero
			$maxdia = 31;
			break;
		case '2':		// Febrero
			$bisiesto = date("L",strtotime($iAno . "-" . $iMes . "-01"));
			if($bisiesto=="1") {
				$maxdia = 29;
			} else {
				$maxdia = 28;
			}
			break;
		case '3':		// Marzo
			$maxdia = 31;
			break;
		case '4':		// Abril
			$maxdia = 30;
			break;
		case '5':		// Mayo
			$maxdia = 31;
			break;
		case '6':		// Junio
			$maxdia = 30;
			break;
		case '7':		// Julio
			$maxdia = 31;
			break;
		case '8':		// Agosto
			$maxdia = 31;
			break;
		case '9':		// Septiembre
			$maxdia = 30;
			break;
		case '10':	// Octubre
			$maxdia = 31;
			break;
		case '11':	// Noviembre
			$maxdia = 30;
			break;
		case '12':	// Diciembre
			$maxdia = 31;
			break;
	}
	$sigdia = intval($iDia) + 1;
	if($sigdia>$maxdia) {
		$sigdia = 1;
		if(intval($mes)>11) {
			$mes='01';
			$año = intval($año) + 1;
		} else {
			$mes = intval($mes) + 1;
			if($mes<10) { $mes = "0" . $mes; }
		}
	}
	if($sigdia<10) { $sigdia = "0" . $sigdia; }
	$fi = date("Y-m-d",strtotime($año."-".$mes."-".$sigdia));
	return $sigdia;
}

// *******************************
// Función para generar un cadena aleatoria
// *******************************
function RandomString($length=10,$uc=TRUE,$n=TRUE,$sc=FALSE)
{
    $source = 'abcdefghijklmnopqrstuvwxyz';
    if($uc==1) $source .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    if($n==1) $source .= '1234567890';
    if($sc==1) $source .= '|@#~$%()=^*+[]{}-_';
    if($length>0){
        $rstr = "";
        $source = str_split($source,1);
        for($i=1; $i<=$length; $i++){
            mt_srand((double)microtime() * 1000000);
            $num = mt_rand(1,count($source));
            $rstr .= $source[$num-1];
        }
 
    }
    return $rstr;
}

// *******************************
// Función para enviar al usuario su contraseña
// *******************************
function sendPwdUser($Usr, $sendTo, $password){
	require 'clases/PHPMailer/PHPMailerAutoload.php';

	$mail = new PHPMailer;

	$mail->isSMTP();                                      // Set mailer to use SMTP
	$mail->Host = 'copa-everest.com';					  // Specify main and backup server
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = 'sistemas@copa-everest.com';  // SMTP username
	$mail->Password = '*.AL^(UTSi~@';                     // SMTP password
//	$mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
	$mail->Port = 25;

	$mail->From = 'sistemas@copa-everest.com';
	$mail->FromName = 'Sistemas Copa Everest';
	$mail->addAddress($sendTo, $nameUsr);					  // Add a recipient

	$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
	$mail->isHTML(true);                                  // Set email format to HTML

	$mail->Subject = 'Acceso Sistema Copa Everest-Alpes';
	$mail->Body    = "	<p>Estimado usuario,</p>

						<p>Para poder accesar al sistema de la Copa Everest 2015 favor de accesar a la siguiente pagina: <a href='http://system.copa-everest.com'>http://system.copa-everest.com</a></p>

						<p>Los datos para que puedas accesar al sistemas son los siguientes:</p>

						<p>Usuario: $Usr</p>

						<p>Contrase&ntilde;a: $password</p>

						<p>Cualquier duda favor de enviar un correo a la siguiente direccion: <a href='mailto:srosales@edu.everestchihuahua.com'>srosales@edu.everestchihuahua.com</a></p>

						<p>Atte.</p>

						<p>Sistemas Copa Everest 2015</p>";

	if(!$mail->send()) {
	   echo '<script>alert("Error: ' . $mail->ErrorInfo . '"); </script>';
	}else{
		echo '<script>alert("Se ha enviado correo al usuario"); </script>';
	}
}

// *******************************
// Función para bloquear registro
// *******************************
function bloqueaRegistro() {
	session_start();
	$arrUserAcc = array(1);
	date_default_timezone_set("America/Chihuahua");
	$fecha_bloqueo = mktime(23,59,59,3,20,2015);
	$fecha_estamos = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));

	if($fecha_estamos <= $fecha_bloqueo and in_array($_SESSION["id_usr"], $arrUserAcc)){
		return false;
	}else{
		return true;
	}
}

function dentroInscripcion($fechaIni, $fechaFin){
	$hoy = date('Y-m-d');

    if (($hoy >= $fechaIni) && ($hoy <= $fechaFin)){
      return true;
    }else{
      return false;
    }
}

function edad($fecha_de_nacimiento) {
    if (is_string($fecha_de_nacimiento)) {
        $fecha_de_nacimiento = strtotime($fecha_de_nacimiento);
    }
    $diferencia_de_fechas = time() - $fecha_de_nacimiento;
    return floor($diferencia_de_fechas / (60 * 60 * 24 * 365));
}
?>