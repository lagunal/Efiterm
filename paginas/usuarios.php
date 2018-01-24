<?php
	include "../controles/header.php";
	include "../clases/directorioActivo.php";
	$da = new directorioActivo();
	
	include_once "../clases/cargarLog.php";
	$log = new Log();

	include_once "../clases/clad.php";
	$clad = new clad();

	require("../clases/efitermAjax.php");

	$error = "";
	
	if($clad->buscarUsuarioAdministrador($_SESSION["id"])!="1"){
		$log->guardarLog($log->logAccesos, "USUARIOS", "NO");
		exit;
	}else{
		$log->guardarLog($log->logAccesos, "USUARIOS", "OK");
	}

	if(isset($_POST["btnGuardar"]) && $_POST["btnGuardar"]!="" && isset($_POST["cmbRoles"]) && $_POST["cmbRoles"]!=""){
		$datos = $da->obtenerUsuarioID(array($_POST["txtID"]));
		
		if(isset($datos[0]) && $datos[0]!=""){		
		    if($_POST["cmbRoles"]==1 || intval($clad->buscarUsuariosAdministradores()) >= 2 ){
				$clad->actualizarUsuarioRol($_POST["txtID"], $_POST["cmbRoles"]);
		    }else{
		    	$error = "Debe existir al menos 2 administradores";
		    }
		}else{
	    	$error = "El id no es correcto";
		}
	}
	
	if(isset($_POST["txtAccion"])){ 
		if($_POST["txtAccion"]=="e" && isset($_POST["txtCodigo"]) && $_POST["txtCodigo"]!=""){
			if(intval($clad->buscarUsuariosAdministradores()) >= 2){
				$clad->eliminarUsuarioRol($_POST["txtCodigo"]);
			}else{
				$error = "Debe existir al menos 2 administradores";
			}
		}
		
		if($_POST["txtAccion"]=="b" && isset($_POST["txtCodigo"]) && $_POST["txtCodigo"]!=""){
			$clad->bloquearUsuario($_POST["txtCodigo"], 0);
		}
	}
	
	$datosUsuarios = $clad->obtenerUsuariosRoles();
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
<head>
    <title><?php echo $config->nombreAplicacion; ?></title>
    <link rel="shortcut icon" href="../favicon.ico">
    <link rel="stylesheet" title="Principal-Aplicaciones" type="text/css" href="../css/main-aplicacion.css" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>   
	<script language="JavaScript" type="text/javascript" src="../js/formulaire.js"></script>
	<script language="JavaScript" type="text/javascript" src="../js/efiterm.js"></script>
	<script language="JavaScript" type="text/javascript" src="../js/doPostBack.js"></script>
	<script language="JavaScript" type="text/javascript" src="../js/injectionJS.js"></script>
	<script language="JavaScript" type="text/javascript">
		function cargarValor(id, rol){
			txtId = document.getElementById("txtID");
			cmbRol = document.getElementById("cmbRoles");
			
			txtId.value = id;
			cmbRol.value = rol;
		}
		
		function eliminarRol(id){
			eliminarCampo('txtAccion', 'e', 'form1', 'txtCodigo', id);
		}

		function desbloquear(id){
			establecerValor('txtCodigo', id);
			doPostBackValor('form1', 'txtAccion', 'b');
		}
	</script>
	<?php
		$xajax->printJavascript("../");
	?>
</head>
<body>
<table width="760px" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<?php echo $pres->crearEncabezado($config->nombreAplicacion); ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php 
				echo $pres->crearVentanaInicio("USUARIOS");
				include "../controles/menu.php";
				echo $pres->crearVentanaIntermedia();
			?>
			<form id="form1" method="post" style="margin:0px">
	    		<table cellpadding="0" cellspacing="0" border="0" width="590px">
					<tr>
						<td colspan="3" height="0px" style="display:none">
							<input type="hidden" id="txtAccion" name="txtAccion"/>
							<input type="hidden" id="txtCodigo" name="txtCodigo" value="<?php if(isset($_POST["txtCodigo"])) echo $_POST["txtCodigo"]; ?>"/>
							<input type="hidden" id="txtValores" name="txtValores" value=""/>
						</td>
					</tr>
					<tr>
						<td>
      						<?php echo $pres->crearSeparador("Equipos"); ?>
						</td>
					</tr>
					<tr>
						<td>
		                    <table cellpadding="0" cellspacing="0" border="0">
		                        <tr>
		                            <td height="6px">
		                            </td>
		                        </tr>
		                        <tr>
		                            <td class="Detalle" width="70px">
		                            	ID:
		                            </td>
		                            <td width="250px">
		                            	<input id="txtID" name="txtID" type="text" value="" size="25" maxlength="25"/>
		                            </td>
		                            <td class="Detalle" width="70">
		                            	Roles:
		                            </td>
		                            <td colspan="3" width="250px">
		                            	<select id="cmbRoles" name="cmbRoles" class="Detalle" style="WIDTH: 150px">
		                                <?php
		                                    $datos = $clad->obtenerRoles();
		                                    
		                                    echo $pres->crearCombo($datos, "codigo_rol", "rol");
		                                ?>
		                            	</select>
		                            </td>
		                        </tr>
		                        <tr>
		                        	<td height="10px"></td>
		                        </tr>
		                        <tr>
		                        	<td colspan="3">
		                        	</td>
		                        	<td>
										<?php echo $pres->crearBoton("btnGuardar", "Guardar", "submit", ""); ?>
									</td>
		                        </tr>
		                        <tr>
		                            <td height="6px">
		                            </td>
		                        </tr>
		                    </table>
						</td>
					</tr>
					<tr>
						<td class="error">
							<?php echo $error; ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $pres->crearSeparador("Usuarios"); ?>
						</td>
					</tr>
					<tr>
						<td>
		                    <table cellspacing="0" cellpadding="0" border="0">
		                    	<tr>
		                    		<td width="20px">
		                    		</td>
		                    		<td class="Titulo" width="150px">
		                    			ID
		                    		</td>
		                    		<td class="Titulo" width="150px">
		                    			Rol
		                    		</td>
		                    	</tr>
			                    <?php
			                    	echo $pres->crearTablaRoles($datosUsuarios, "Lista-Fondo1", "Lista-Fondo2");
			                    ?>
		                    </table>
						</td>
					</tr>
				</table>
			</form>
			<?php echo $pres->crearVentanaFin(); ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo $pres->crearPie(); ?>
		</td>
	</tr>
</table>
</body>