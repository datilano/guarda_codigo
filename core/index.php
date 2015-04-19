<?php
header('Content-Type: text/html; charset=iso-8859-1');
include "seguridad.php";
include "../conexion.php";
include "functions.php";
include "variables_session.php";

//Cuando cambia de modulo se deben reiniciar las variables de procesos y subprocesos
if(isset($_GET["id_mod"])){
	unset($_SESSION["id_proc"]);
}

//Modulo que se esta viendo
if($_GET["id_mod"] != "" ){$_SESSION["id_mod"] = $_GET["id_mod"];}

if($_SESSION["id_mod"] == ""){ 
	$id_mod = 2; 
}else{ 
	$id_mod = $_SESSION["id_mod"];
}

$a = $_REQUEST["a"];
$id_reg = $_REQUEST["id_reg"];

//Proceso que se esta viendo
if($_GET["id_proc"] != "" ){$_SESSION["id_proc"] = $_GET["id_proc"];}
$id_proc = $_SESSION["id_proc"];
$id_tipo_proc = getValue($conexion, "procesos", "id_tipo_proc", "id_proc = '$id_proc'");
?>
<!DOCTYPE html>
<html>
<head>
<title>Guarda Codigo</title>
<meta charset="utf-8">
<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico" />
<link rel="stylesheet" href="css/reset.css" type="text/css" media="all">
<link rel="stylesheet" href="css/style.css" type="text/css" media="all">
<link rel="stylesheet" href="css/table.css" type="text/css" media="all">
<link rel="stylesheet" href="css/form.css" type="text/css" media="all">
<link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.8.13.custom.css" type="text/css" media="all">
<script type="text/javascript" src="js/jquery-1.4.2.min.js" ></script>
<script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
<script type="text/javascript" src="js/cufon-yui.js"></script>
<script type="text/javascript" src="js/cufon-replace.js"></script>
<script type="text/javascript" src="js/Myriad_Pro_300.font.js"></script>
<script type="text/javascript" src="js/Myriad_Pro_400.font.js"></script>
<script type="text/javascript" src="js/script.js"></script>

<link class="jsbin" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />
<script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/jquery-ui.min.js"></script>
<!--[if lt IE 7]>
<link rel="stylesheet" href="css/ie6.css" type="text/css" media="screen">
<script type="text/javascript" src="js/ie_png.js"></script>
<script type="text/javascript">ie_png.fix('.png, footer, header nav ul li a, .nav-bg, .list li img');</script>
<![endif]-->
<!--[if lt IE 9]><script type="text/javascript" src="js/html5.js"></script><![endif]-->
</head>
<body id="page1">
<!-- START PAGE SOURCE -->
<div class="wrap">
  <header>
    <div class="container">
      <h1><a href="index.php">Sistema Copa Everest</a></h1>
      <nav>
        <ul>
          <?php
		  $qryMod	= "SELECT * FROM vwaccesos_modulos WHERE id_tipo_usr='" . $_SESSION["id_tipo_usr"] . "' ORDER BY order_mod";
		  $resMod	= mysql_query($qryMod);
		  $i		= 1;
		  while($rowMod = mysql_fetch_array($resMod)){
			  $auxClass = "";
			  if($rowMod["id_mod"] == $id_mod){
				  $auxClass = "class='current'";
			  }
			  echo "<li $auxClass><a href='" . $_SERVER['PHP_SELF'] . "?id_mod=" . $rowMod["id_mod"] . "' class='m$i'>" . $rowMod["nombre_mod"] . "</a></li>";
			  $i++;
		  }
		  ?>
          <li class="last"><a href="cerrar_sesion.php" class="m5">Cerrar Sesi&oacute;n</a></li>
        </ul>
      </nav>
      <!-- <form action="#" id="search-form">
        <fieldset>
          <div class="rowElem">
            <input type="text">
            <a href="#">Search</a></div>
        </fieldset>
      </form> -->
    </div>
  </header>
  <div class="container">
    <aside>
	  <h2><?php echo $_SESSION["nombre_esc"]; ?></h2>
      <h3>Opciones</h3>
      <ul class="categories">
        <?php
		$qryProc = "SELECT * FROM procesos WHERE id_mod_proc = '$id_mod' AND hidden_proc=0 ORDER BY order_proc";
		$resProc = mysql_query($qryProc);
		while($rowProc = mysql_fetch_array($resProc)){
			//if(!bloqueaRegistro() || $rowProc["id_proc"] != 13){ 
				echo "<li><span><a href='" . $_SERVER['PHP_SELF'] . "?id_mod=$id_mod&id_proc=" . $rowProc["id_proc"] . "'>" . $rowProc["nombre_proc"] . "</a></span></li>";
			//}
		}
		?>
		<!-- <li><span><a href="#">Programs</a></span></li>
        <li><span><a href="#">Student Info</a></span></li>
        <li><span><a href="#">Teachers</a></span></li>
        <li><span><a href="#">Descriptions</a></span></li>
        <li><span><a href="#">Administrators</a></span></li>
        <li><span><a href="#">Basic Information</a></span></li>
        <li><span><a href="#">Vacancies</a></span></li>
        <li class="last"><span><a href="#">Calendar</a></span></li> -->
      </ul>
	  <?php if($id_proc == 8 ) { //if($id_proc != "" and $id_tipo_proc == 1) { ?>
      <form id="newsletter-form">
        <fieldset>
          <div class="rowElem">
            <h2>Buscar</h2>
            <input type="text" placeholder="Buscar alumno.." id="searchAlumn" name="searchAlumn" >
            <div class="clear"><a href="#" class="fright" id="submit-alumn">Buscar</a></div>
          </div>
        </fieldset>
      </form>
	  <?php } ?>
      <!-- <h2>Fresh <span>News</span></h2>
      <ul class="news">
        <li><strong>June 30, 2010</strong>
          <h4><a href="#">Sed ut perspiciatis unde</a></h4>
          Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque. </li>
        <li><strong>June 14, 2010</strong>
          <h4><a href="#">Neque porro quisquam est</a></h4>
          Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit consequuntur magni. </li>
        <li><strong>May 29, 2010</strong>
          <h4><a href="#">Minima veniam, quis nostrum</a></h4>
          Uis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae. </li>
      </ul> -->
    </aside>
    <section id="content">
      <?php if($id_proc==""){ ?>
	  <div id="banner">
        <h2>Guarda Codigo<span>Genera tus propios <br>codigos y comparte</span></h2>
      </div>
	  <?php }else{ ?>
	  <div class="inside">
        <?php
		switch($id_tipo_proc){
			case 1:
				$id_tabla_obj = getValue($conexion, "procesos", "id_tabla_proc", "id_proc = '$id_proc'");
				$arrTabla = getValues($conexion, "tablas", "*", "id_tabla = '$id_tabla_obj'");
				$nombre_tabla = $arrTabla["nombre_tabla"];
				$vista_tabla = $arrTabla["vista_tabla"];
				$titulo_tabla = $arrTabla["titulo_tabla"];
				$pk_tabla = $arrTabla["pk_tabla"];
				$nombre_aux_tabla = $arrTabla["nombre_aux_tabla"];
				switch($a) {
					case "add":
						$titleForm = "Agregar";
						include $fld_include."/form.php";
						break;
					case "edit":
						$titleForm = "Editar";
						$id_reg = $_GET["id_reg"];
						include $fld_include."/form.php";
						break;
					case "delete":
						$id_reg = $_GET["id_reg"];
						include $fld_include."/delete.php";
						break;
					default:
						include $fld_include."/dibujatabla.php";
						break;
				}
				break;
			case 2:
				$pagina_proc = getValue($conexion, "procesos", "pagina_proc", "id_proc = '$id_proc'");
				include $fld_include."/".$pagina_proc;
				break;
		}
		?>
		<!-- <h2>Recent <span>Articles</span></h2>
        <ul class="list">
          <li><span><img src="images/icon1.png"></span>
            <h4>About Template</h4>
            <p>Eusus consequam vitae habitur amet nullam vitae condis phasellus sed justo. Orcivel mollis intesque eu sempor ridictum a non laorem lacingilla wisi.</p>
          </li>
          <li><span><img src="images/icon2.png"></span>
            <h4>Branch Office</h4>
            <p>Eusus consequam vitae habitur amet nullam vitae condis phasellus sed justo. Orcivel mollis intesque eu sempor ridictum a non laorem lacingilla wisi.</p>
          </li>
          <li class="last"><span><img src="images/icon3.png"></span>
            <h4>Studentâ€™s Time</h4>
            <p>Eusus consequam vitae habitur amet nullam vitae condis phasellus sed justo. Orcivel mollis intesque eu sempor ridictum a non laorem lacingilla wisi.</p>
          </li>
        </ul>
        <h2>Move Forward <span>With Your Education</span></h2>
        <p><span class="txt1">Eusus consequam</span> vitae habitur amet nullam vitae condis phasellus sed justo. Orcivel mollis intesque eu sempor ridictum a non laorem lacingilla wisi. </p>
        <div class="img-box"><img src="images/1page-img.jpg">Eusus consequam vitae habitur amet nullam vitae condis phasellus sed justo. Orcivel mollis intesque eu sempor ridictum a non laorem lacingilla wisi. Nuncrhoncus eros <a href="#">maurien ulla</a> facilis tor mauris tincidunt et vitae morbi. Velelit condimentes in laorem quis nullamcorper nam fauctor feugiat pellent sociis.</div>
        <p class="p0">Eusus consequam vitae habitur amet nullam vitae condis phasellus sed justo. Orcivel mollis intesque eu sempor ridictum a <a href="#">non laorem</a> lacingilla wisi.</p> -->
      </div>
	  <?php } ?>
    </section>
  </div>
</div>
<footer>
  <div class="footerlink">
    <p class="lf">Copyright &copy; 2010 <a href="#">SiteName</a> - All Rights Reserved</p>
    <p class="rf"><a href="http://www.free-css.com/">Free CSS Templates</a> by <a href="http://www.templatemonster.com/">TemplateMonster</a></p>
    <div style="clear:both;"></div>
  </div>
</footer>
<div id="fondo-negro">
	<center><img src="images/loading.gif" width="300" height="300" border="0" alt=""></center>
</div>
<script type="text/javascript"> Cufon.now(); </script>
<!-- END PAGE SOURCE -->
</body>
</html>
<?php
$qryAlumn = "SELECT nombre_completo_alum, curp_alum FROM vwalumnos WHERE esc_id_alum = '" . $_SESSION["esc_id_usr"] . "'";
$resAlumn = mysql_query($qryAlumn);
$sep2 = "@#@";
while($rowAlumn = mysql_fetch_array($resAlumn)) {
	$auxAlumn .= $sep1 . $rowAlumn["nombre_completo_alum"] . $sep2 . $rowAlumn["curp_alum"];
	$sep1 = $sep2;
}
?>
<script>
	$( document ).ready(function() {
		var arrCol = "<?php echo $auxAlumn; ?>";
		arrCol = arrCol.split("<?php echo $sep2; ?>");
		$( "#searchAlumn" ).autocomplete({
			source: arrCol
		});
		
		$( "#submit-alumn" ).click(function() {
			$( "#newsletter-form" ).submit();
		});
	});
</script>
<?php include "../cerrar_conexion.php"; ?>