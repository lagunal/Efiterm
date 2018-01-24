<form id="frmDatos">
	<div style="display:none">
		<?php include "../controles/datos.php"; ?>
	</div>
</form>
<script language="javascript" type="text/javascript">
	function copiaPapelera(meintext){
		if(window.clipboardData){
	   		window.clipboardData.setData("Text", meintext);
	   	}
	}
	
	function copiarDatos(nombreControl){
		control = document.getElementById(nombreControl);
	
		control = control.innerHTML.toString();
		alert(control);
	}
</script>
<script language="JavaScript" type="text/javascript">
	var primero = false;
	var etiquetaVisible = "";
	
	function mostrarDetalleError(nombreControl){
		controlAnterior = document.getElementById(etiquetaVisible);
		control = document.getElementById(nombreControl);

		if(controlAnterior) controlAnterior.style.display = "none";

		if(etiquetaVisible==nombreControl){
			control.style.display = "none";
			
			etiquetaVisible = "";
		}else{
			if(control.style.display == "none")
				control.style.display = "inline";
			else
				control.style.display = "none";

			etiquetaVisible = nombreControl;
		}
	}
	
	function calcularIndicador(indicador, actual, diseno, mensaje){
		indicadorImg = document.getElementById(indicador);
		indicadorLink = document.getElementById(indicador + "Link");

		valordiseno = document.getElementById(diseno).innerHTML;
		valordiseno.toString;
		valordiseno = valordiseno.replace(",", ".");

		valoractual = document.getElementById(actual).innerHTML;
		valoractual.toString;

		if(valoractual != "n/a"){
			valoractual = valoractual.replace(".", ",");
	
			eactual = new Number(document.getElementById(actual).innerHTML);
			ediseno = new Number(valordiseno);
	
			if(eactual>0 && ediseno>0){
				rango = Math.abs(eactual-ediseno);
				if(rango <= 3){
					imagen = "arriba.gif";
					clase = "eficienciaMayor";
				}else{
					imagen = "abajo.gif";
					clase = "eficienciaMenor";
				}
			}else{
				imagen = "menos.gif";
				clase = "eficienciaIgual";
			}
			
			document.getElementById(actual).innerHTML = valoractual;
			indicadorLink.href = "#";
		}else{
			indicadorDiv = document.getElementById(indicador + "Div");

			etiqueta = mensaje.split(",");
			c = etiqueta.length;

			if(c > 0){
				indicadorDiv.innerHTML = "&nbsp;No se pudo obtener el valor de: <br>";

				for(i=0; i<c; i++){
					nombre = etiqueta[i].toString();
					nombre = nombre.replace("txt", "lbl");
					
					texto = document.getElementById(nombre).innerHTML;
					texto = texto.toString();
					texto = texto.replace(":", "");

					indicadorDiv.innerHTML += "&nbsp;- " + texto + "<br>";
				}

				alto = 15 * (c + 1);
				alto = alto.toString();
				
				indicadorDiv.style.height = alto;
			}
			
			imagen = "sindatos.gif";
			clase = "eficienciaIgual";
		}
		
		document.getElementById(actual).className = clase;
		indicadorImg.src = "../imagenes/" + imagen;
	}
	
	function actualizarGrafico(forma){
		forma = document.getElementById(forma);
		
		document.getElementById(forma.id + 'txtPuntosGrafico').value = document.getElementById(forma.id + 'tdPuntosGrafico').innerHTML;
		campo = document.getElementById(forma.id + 'txtEficiencias').value;

		fila = campo.split(";");
		c = fila.length;

		if(c>1){
			valores = fila[c-1].split(":");
			document.getElementById(forma.id + 'EficienciaActual').innerHTML = valores[1];
		}else{
			document.getElementById(forma.id + 'EficienciaActual').innerHTML = "n/a";
		}
		
		calcularIndicador(forma.id + 'Indicador', forma.id + 'EficienciaActual', forma.id + 'EficienciaDiseno', document.getElementById(forma.id + 'txtCamposFaltantes').value);		
	
		var ruta = document.getElementById('lnkVerGrafico' + forma.id);
		ruta = ruta.toString();
		eval(unescape(ruta.substr(11)));
	
		if(!primero){
			primero = true;
		}
	}

	function verGrafico(control, datos, controlTitulo, nombre){
		document.getElementById(controlTitulo).innerHTML = nombre;

		datosGrafico = document.getElementById(datos).value;
		
		ruta = "../controles/graficoEficiencia.php?datos=" + datosGrafico;

		forma = datos.replace("txtEficiencias", "");

		if(datosGrafico!=""){
			eficienciaActual = document.getElementById(forma + 'EficienciaDiseno').innerHTML;
			
			ruta += "&ancho=310&alto=190&link=no";

			if(eficienciaActual != "N/A"){
				eficienciaActual = eficienciaActual.toString();
				eficienciaActual = eficienciaActual.replace(",", ".");
				ruta += "&efi=" + eficienciaActual;
			}
		}else{
			document.getElementById(controlTitulo).innerHTML += "<br><label class=Error>Faltan datos para el cálculo</label>";
		}
		
		document.getElementById(control).src = ruta;
	}
	
	function verDetalle(form, respuesta){
		form = document.getElementById(form);

		if(respuesta == "no")
			form.target = "";
		else if(respuesta == "si")
			form.target = "_blank";
		
		if(respuesta != "cancelar")	
			form.submit();
	}
</script>
<?php
	include_once "../clases/clad.php";
	include "crearVentana.php";
	
	$pres = new presentacion();
	$clad = new clad();
	$equiposCalcular = array();
	
	$datosHornos = $clad->obtenerTipoEquipo(1);
	$datosCalderas = $clad->obtenerTipoEquipo(2);
	
	if(isset($_SESSION["id"])){
		$datosConfiguracion = $clad->obtenerConfiguracion(strtoupper($_SESSION["id"]));
		$c = count($datosConfiguracion);
	}else{
		$c = 0;
	}

	if($c>0){
		$rangoGrafico = $datosConfiguracion[0]["rangografico"];
		$actualizacion = $datosConfiguracion[0]["actualizar"];
	}else{
		$rangoGrafico = 5;
	}
	
	$equipos = array();
	$fechaActual = $pres->obtenerFechaActual();
	
	for($i=0;$i<$c;$i++)
		$equipos[] = $datosConfiguracion[$i]["codigo_equipo"];
?>
<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td>
		<?php
			if(count($datosHornos)>0)
				crearVentana("Hornos Proceso", $datosHornos, $equipos, $rangoGrafico, "Horno", "Gas", "Cálculos basados en la práctica API-532. Composición de gases de combustión teórica y base seca.");
		?>
		</td>
	</tr>
	<tr>
		<td>
		<?php
			if(count($datosCalderas)>0)
				crearVentana("Calderas Proceso", $datosCalderas, $equipos, $rangoGrafico, "Caldera", "Gas", "Cálculos basados en la práctica ASME PTC 4.1.");
		?>
		</td>
	</tr>
	<tr>
		<td align="right">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td class="segundoNivel">
					<?php if(count($equipos)>0){ ?>
						<script language="JavaScript" type="text/javascript">
							xajax_calcularEficienciaRangoIndicadores('<?php echo implode(",", $equipos); ?>', '<?php echo $pres->dateSum($fechaActual, -$rangoGrafico); ?>', '<?php echo $fechaActual; ?>');
						</script>
					<?php } ?>
					</td>
				</tr>
				<tr>
					<td height="5px"></td>
				</tr>
				<tr>
					<td>
						<form method="get" style="margin:0px">
							<?php echo $pres->crearBoton("btnActualizar", "Actualizar", "submit"); ?>
					  	</form>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>