<?php header('Content-Type: text/html; charset=iso-8859-1'); ?>
<!DOCTYPE html>
<html lang="en">
<head>

    <!-- Basic Page Needs
  ================================================== -->
    <meta charset="utf-8">
    <title>Login Copa Everes Alpes</title>
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Mobile Specific Metas
  ================================================== -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- CSS
  ================================================== -->

    <!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>

<style>
@import url(http://fonts.googleapis.com/css?family=Roboto:100);
@import url(http://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.css);

body {
  background: #1a1a1a;
  color: white;
  font-family: 'Roboto';
}
.flat-form {
  background: #2775FF;
  margin: 25px auto;
  width: 390px;
  height: 340px;
  position: relative;
  font-family: 'Roboto';
}
.tabs {
  background: #331888;
  height: 40px;
  margin: 0;
  padding: 0;
  list-style-type: none;
  width: 100%;
  position: relative;
  display: block;
  margin-bottom: 20px;
}
.tabs li {
  display: block;
  float: left;
  margin: 0;
  padding: 0;
}
.tabs a {
  background: #331888;
  display: block;
  float: left;
  text-decoration: none;
  color: white;
  font-size: 16px;
  padding: 12px 22px 12px 22px;
  /*border-right: 1px solid @tab-border;*/

}
.tabs li:last-child a {
  border-right: none;
  width: 174px;
  padding-left: 0;
  padding-right: 0;
  text-align: center;
}
.tabs a.active {
  background: #2775FF;
  border-right: none;
  -webkit-transition: all 0.5s linear;
	-moz-transition: all 0.5s linear;
	transition: all 0.5s linear;
}
.form-action {
  padding: 0 20px;
  position: relative;
}

.form-action h1 {
  font-size: 42px;
  padding-bottom: 10px;
}
.form-action p {
  font-size: 12px;
  padding-bottom: 10px;
  line-height: 25px;
}
form {
  padding-right: 20px !important;
}
form input[type=text],
form input[type=password],
form input[type=submit] {
  font-family: 'Roboto';
}

form input[type=text],
form input[type=password] {
  width: 100%;
  height: 40px;
  margin-bottom: 10px;
  padding-left: 15px;
  background: #fff;
  border: none;
  color: #240D5F;
  outline: none;
}

.dark-box {
  background: #5e0400;
  box-shadow: 1px 3px 3px #3d0100 inset;
  height: 40px;
  width: 50px;
}
.form-action .dark-box.bottom {
  position: absolute;
  right: 0;
  bottom: -24px;
}
.tabs + .dark-box.top {
  position: absolute;
  right: 0;
  top: 0px;
}
.show {
  display: block;
}
.hide {
  display: none;
}

.button {
    border: none;
	float: left;
    display: block;
    background: #136899;
    height: 40px;
    width: 80px;
    color: #ffffff;
    text-align: center;
    border-radius: 5px;
    /*box-shadow: 0px 3px 1px #2075aa;*/
  	-webkit-transition: all 0.15s linear;
	  -moz-transition: all 0.15s linear;
	  transition: all 0.15s linear;
}

.button:hover {
  background: #1e75aa;
  /*box-shadow: 0 3px 1px #237bb2;*/
}

.button:active {
  background: #136899;
  /*box-shadow: 0 3px 1px #0f608c;*/
}

.button_cancelar {
    border: none;
	float: right;
    display: block;
    background: #0C3349;
    height: 40px;
    width: 80px;
    color: #ffffff;
    text-align: center;
    border-radius: 5px;
    /*box-shadow: 0px 3px 1px #2075aa;*/
  	-webkit-transition: all 0.15s linear;
	  -moz-transition: all 0.15s linear;
	  transition: all 0.15s linear;
}

.button_cancelar:hover {
  background: #1e75aa;
  /*box-shadow: 0 3px 1px #237bb2;*/
}

.button_cancelar:active {
  background: #136899;
  /*box-shadow: 0 3px 1px #0f608c;*/
}

::-webkit-input-placeholder {
  color: #58575A;
}
:-moz-placeholder {
  /* Firefox 18- */
  color: #58575A;
}
::-moz-placeholder {
  /* Firefox 19+ */
  color: #58575A;
}
:-ms-input-placeholder {
  color: #58575A;
}

p.error {
	color: #F89191;
	font-weight: bold;
}
</style>
<body>

    <div class="container">
        <div class="flat-form">
            <ul class="tabs">
                <li>
                    <a href="#login" class="active">Login</a>
                </li>             
            </ul>
            <div id="login" class="form-action show">
                <h1>Acceso al Sistema</h1>
                <p>
                    Introduce usuario y contrase&ntilde;a que se te asigno.
                </p>
				<?php
				if(isset($_GET["error"])){
					switch($_GET["error"]){
						case 1:
							echo "<p class='error'>Usuario ó contraseña incorrecta, intente de nuevo</p>";
							break;
						case 2:
							echo "<p class='error'>No puedes accesar al sistema hasta que inicies sesion</p>";
							break;
					}
				}
				?>
				<form method="post" action="autenticacion.php">
                    <ul>
                        <li>
                            <input type="text" name="username" placeholder="Username" />
                        </li>
                        <li>
                            <input type="password" name="password" placeholder="Password" />
                        </li>
                        <li>
                            <input type="submit" value="Login" class="button" />
							<input type="button" value="Salir" id="cancel" class="button_cancelar" />
                        </li>
                    </ul>
                </form>
            </div>
        </div>
    </div>
	<center><a href="http://copa-everest-alpes.com/wp-content/uploads/2014/03/Manual-Sistema-de-Competidores1.pdf" target="_blank"><font color="#fff"><b>Manual para el uso del Sistema</b></font></a></center>
    <script class="cssdeck" src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
	<script type="text/javascript">
	<!--
		(function( $ ) {
	  // constants
	  var SHOW_CLASS = 'show',
		  HIDE_CLASS = 'hide',
		  ACTIVE_CLASS = 'active';
	  
	  $( '#cancel' ).on( 'click', function(e){
		  location.href = 'http://copa-everest-alpes.com/';
	  });

	  $( '.tabs' ).on( 'click', 'li a', function(e){
		e.preventDefault();
		var $tab = $( this ),
			 href = $tab.attr( 'href' );
	  
		 $( '.active' ).removeClass( ACTIVE_CLASS );
		 $tab.addClass( ACTIVE_CLASS );
	  
		 $( '.show' )
			.removeClass( SHOW_CLASS )
			.addClass( HIDE_CLASS )
			.hide();
		
		  $(href)
			.removeClass( HIDE_CLASS )
			.addClass( SHOW_CLASS )
			.hide()
			.fadeIn( 550 );
	  });
	})( jQuery );
	//-->
	</script>
</body>
</html>


