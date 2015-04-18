<?php session_start(); ?>
<style>
td.data-alumn{
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
<table>
<?php
for($i=1;$i<=10;$i++){
	if(fmod($i,2)==1) echo "<tr>";
	echo "		<td style='border-style: dashed;border-right: white;'>
					&nbsp;<img id='blah' src='' alt='' width='90px' height='110px' style='border-width: 2px; border-style: solid; border-color: black;'/>
				</td>
				<td class='data-alumn'>
					<b>
					Escuela:_______________________<br>
					Deporte:_______________________<br>
					Categoria:_____________________<br>
					Equipo:________________________<br>
					Nombre:_______________________<br>
					CURP:________________________<br>
					Fecha Nac.:____________________<br>
					Estado Nac.:___________________</b>
				</td>";
	if(fmod($i,2)==0) echo "</tr>";
}
?>