<?php
include "../controles/header.php";

    include_once "../clases/clad.php";
    $clad = new clad();

    require("../clases/efitermAjax.php");
?>
<html>
<head>
	<link rel="shortcut icon" href="../favicon.ico">    
	<meta name="generator" content="HTML Tidy, see www.w3.org" />
    <title><?php echo $config->nombreAplicacion; ?></title>
    <link rel="stylesheet" title="Principal-Aplicaciones" type="text/css" href="../css/main-aplicacion.css" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <?php
        $xajax->printJavascript("../");
    ?>
</head>
<body>
    <table width="760px" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td><?php echo $pres->crearEncabezado($config->nombreAplicacion); ?></td>
        </tr>

        <tr>
            <td>
                <?php 
                    echo $pres->crearVentanaInicio("METODOLOGÍA");
                    include "../controles/menu.php";
                    echo $pres->crearVentanaIntermedia();
                ?>
                <form id="form1" method="post" style="margin:0px">
                    <table id="tblEncabezado" cellpadding="0" cellspacing="0" border="0" width="590px" height="100%">
                        <tr>
                            <td colspan="3" height="0px">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%">
                                    <tr>
                                        <td width="100%" height="100%">
                                            <table id="table1" height="100%" cellspacing="0" cellpadding="0" width="100%" border="0">
                                                <tr>
                                                    <td valign="middle" align="center" height="60"><img src="../imagenes/logoEFITERM250px.jpg" /></td>
                                                </tr>

                                                <tr height="100%">
                                                    <td valign="top">
                                                    	<br />
                                                        <table cellspacing="0" cellpadding="0" width="100%" border="0" id="table3">
                                                            <tr>
                                                                <td class="Titulo"><a class="Titulo" href="metodologia.php#API">-Eficiencia Térmica de&nbsp;Horno - API 532&nbsp;&nbsp;</a></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="Titulo"><a class="Titulo" href="metodologia.php#ASME">-Eficiencia Térmica de Caldera - ASME PTC 4.1</a> </td>
                                                            </tr>
                                                        </table>
                                                        <p class="Titulo" align="justify">&nbsp;</p>
                                                        <p class="Titulo" id="API" align="justify">EFICIENCIA TÉRMICA DE HORNOS - API 532:</p>
                                                        <p class="Detalle" align="justify">El cálculo de la eficiencia térmica de hornos está basado en la práctica recomendada API-532 "Measurement of the Thermal Efficiency of Fired Process Heaters", la cual establece una metodología clara y concreta del cálculo de la eficiencia. La API-532 presenta igualmente, los procedimientos para realizar la recolección de los datos en campo y que serán utilizados para el cálculo de la eficiencia térmica, de manera que éstos sean lo más precisos y confiables posible. Según esta práctica recomendada, la eficiencia térmica, basada en el poder calorífico inferior del combustible quemado, puede ser determinada por la siguiente ecuación:</p>
                                                        <p class="Detalle" align="center"><img src="../imagenes/EcuacionHorno.1.jpg" /></p>
                                                        <p class="Detalle" align="justify">Por lo tanto,</p>
                                                        <p class="Detalle" align="center">&nbsp;<img src="../imagenes/EcuacionHorno.2.jpg" /></p>
                                                        <p class="Detalle" align="justify">donde:</p>
                                                        <p class="Detalle" align="justify">LHV: Poder calorífico inferior del combustible quemado [BTU/lbm]</p>
                                                        <p class="Detalle" align="justify">Ha: Corrección del calor sensible del aire [BTU/lbm]</p>
                                                        <p class="Detalle" align="justify">Hf: Corrección del calor sensible del combustible [BTU/lbm]</p>
                                                        <p class="Detalle" align="justify">Hm : Corrección del calor sensible del medio atomizante [BTU/lbm]</p>
                                                        <p class="Detalle" align="justify">Qr : Perdidas por radiación [BTU/lbm]</p>
                                                        <p class="Detalle" align="justify">Qs : Perdidas por gases de combustión [BTU/lbm]</p>
                                                        <p class="Detalle" align="justify">&nbsp;</p>
                                                        <p class="Detalle" align="justify"><a class="Detallee">Poder calorífico inferior del combustible (LHV)</a></p>
                                                        <p class="Detalle" align="justify">Indica la cantidad de energía liberada por el combustible cuando en la reacción de combustión no se considera la formación de agua en forma liquida. Puede ser determinado mediante un análisis de laboratorio o estimado a partir de valores térmicos.</p>
                                                        <p class="Detalle" align="justify">En el caso de estimación térrica para una mezcla de gases se emplea la siguiente ecuación:</p>
                                                        <p class="Detalle" align="center"><img src="../imagenes/EcuacionHorno.3.jpg" />&nbsp;</p>
                                                        <p class="Detalle" align="justify">donde:</p>
                                                        <p class="Detalle" align="justify">LHVi : Poder calorífico inferior del componente "i" [BTU/lbm].</p>
                                                        <p class="Detalle" align="justify"><img src="../imagenes/EcuacionHorno.4.jpg" />: Flujo másico del componente "i" [lbm/h]</p>
                                                        <p class="Detalle" align="justify"><img src="../imagenes/EcuacionHorno.5.jpg" /> :&nbsp;Flujo másico del combustible [lbm/h]</p>
                                                        <p class="Detalle" align="justify">&nbsp;</p>
                                                        <p class="DetalleNegrita" align="justify">Cálculo de la Corrección del calor sensible del aire (Ha)</p>
                                                        <p class="Detalle" align="justify">Consiste en expresar el calor sensible del aire en función de la relación entre el flujo de aire suministrado y el flujo de combustible. Se calcula empleando la relación que se muestra en la siguiente ecuación:</p>
                                                        <p class="Detalle" align="center"><img src="../imagenes/EcuacionHorno.6.jpg" />&nbsp;</p>
                                                        <p class="Detalle" align="justify">donde:</p>
                                                        <p class="Detalle" align="justify">Ha : Calor sensible del aire [BTU/lbm]</p>
                                                        <p class="Detalle" align="justify">Cpaire : Capacidad calorífica del aire = 0.24 BTU/lbm °F</p>
                                                        <p class="Detalle" align="justify">&nbsp;<img src="../imagenes/EcuacionHorno.7.jpg" /> : Flujo másico de aire [lbm/h]</p>
                                                        <p class="Detalle" align="justify">Ta : Temperatura ambiental [°F]</p>
                                                        <p class="Detalle" align="justify">tref : Temperatura de referencia ( 60 °F )</p>
                                                        <p class="Detalle" align="justify">&nbsp;</p>
                                                        <p class="DetalleNegrita" align="justify">Cálculo de la Corrección del calor sensible del combustible (Hf)</p>
                                                        <p class="Detalle" align="justify">Considera el calor asociado a la diferencia entre la temperatura del combustible y la temperatura de referencia (60°F). Se calcula empleando la ecuación que se muestra a continuación:</p>
                                                        <p class="Detalle" align="center"><img src="../imagenes/EcuacionHorno.8.jpg" />&nbsp;</p>
                                                        <p class="Detalle" align="justify">donde:</p>
                                                        <p class="Detalle" align="justify">Ha : Calor sensible del combustible [BTU/lbm]</p>
                                                        <p class="Detalle" align="justify">Cpcombustible : Capacidad calorífica del combustible [BTU/lbm °F]</p>
                                                        <p class="Detalle" align="justify">Tf : Temperatura del combustible [°F]</p>
                                                        <p class="Detalle" align="justify">Para calcular la capacidad calorífica del combustible se sugiere utilizar la siguiente ecuación:</p>
                                                        <p class="Detalle" align="center">&nbsp;<img src="../imagenes/EcuacionHorno.9.jpg" /></p>
                                                        <p class="Detalle" align="justify">donde:</p>
                                                        <p class="Detalle" align="justify">Cpi : Capacidad calorífica de cada componente "i" de la mezcla [BTU/lbm Â°F]</p>
                                                        <p class="Detalle" align="justify">&nbsp;</p>
                                                        <p class="DetalleNegrita" align="justify">Cálculo de la corrección del calor sensible del medio atomizante (Hm)</p>
                                                        <p class="Detalle" align="justify">En el caso que se emplee combustible líquido se debe considerar el calor asociado al medio atomizante empleado. Por lo general, se utiliza vapor saturado o sobrecalentado; la presencia de agua líquida junto con el vapor disminuye la eficiencia de la combustión. El cálculo de este calor se hace por medio de la siguiente ecuación:</p>
                                                        <p class="Detalle" align="center">&nbsp;<img src="../imagenes/EcuacionHorno.10.jpg" /></p>
                                                        <p class="Detalle" align="justify">donde:</p>
                                                        <p class="Detalle" align="justify">Hm : Calor sensible del medio atomizante [BTU/lbm]</p>
                                                        <p class="Detalle" align="justify">Hmedio : Entalpía del medio atomizante a la temperatura y presión de operación [BTU/lbm]</p>
                                                        <p class="Detalle" align="justify">Href : Entalpía del agua a tref (Href = 1087,7 BTU/lbm).</p>
                                                        <p class="Detalle" align="justify"><img src="../imagenes/EcuacionHorno.11.jpg" />: Flujo másico del medio atomizante [lbm/h]</p>
                                                        <p class="Detalle" align="justify">&nbsp;</p>
                                                        <p class="DetalleNegrita" align="justify">Cálculo del Calor Perdido por radiación (Qr)</p>
                                                        <p class="Detalle" align="justify">El calor perdido por radiación representan el calor cedido al ambiente a través de las superficies externas del horno. Se calcula según la siguiente ecuación:</p>
                                                        <p class="Detalle" align="center">&nbsp;<img src="../imagenes/EcuacionHorno.12.jpg" /></p>
                                                        <p class="Detalle" align="justify">donde:</p>
                                                        <p class="Detalle" align="justify">Qr : Calor perdido por radiación [BTU/lbm]</p>
                                                        <p class="Detalle" align="justify">Kr: Factor de pérdidas por radiación. La práctica recomendada API-532 establece un valor típico de 0.025 para combustibles gaseosos y 0.015 para combustibles líquidos.</p>
                                                        <p class="Detalle" align="justify">&nbsp;</p>
                                                        <p class="DetalleNegrita" align="justify">Calor Perdido por los gases de combustión (Qs)</p>
                                                        <p class="Detalle" align="justify">El proceso de combustión completa podría ocurrir con proporciones exactas de combustible y oxígeno, o lo que es lo mismo, en cantidades estequiométricas, o sin exceso de oxígeno. Sin embargo, en la realidad, se debe suplir una cantidad de aire en exceso de la estequiométrica para ayudar que el aire se mezcle eficientemente con el combustible y así alcanzar una combustión completa. Los gases que se generan cuando la combustión es completa están compuestos principalmente por CO2, H2O, O2 y N2. Para calcular las pérdidas de calor asociadas a estos gases, productos de una combustión completa, se emplea la siguiente ecuación:</p>
                                                        <p class="Detalle" align="center">&nbsp;<img src="../imagenes/EcuacionHorno.13.jpg" /></p>
                                                        <p class="Detalle" align="justify">donde:</p>
                                                        <p class="Detalle" align="justify">Qs : Calor perdido en los gases de combustión [BTU/lbm]</p>
                                                        <p class="Detalle" align="justify">QCO2 : Calor asociado al CO2 contenido en los gases de combustión [BTU/lbm]</p>
                                                        <p class="Detalle" align="justify">QH2O : Calor asociado al H2O contenido en los gases de combustión [BTU/lbm]</p>
                                                        <p class="Detalle" align="justify">QN2 : Calor asociado al N2 contenido en los gases de combustión [BTU/lbm]</p>
                                                        <p class="Detalle" align="justify">Qaire : Calor asociado al aire contenido en los gases de combustión [BTU/lbm]</p>
                                                        <p class="Detalle" align="justify">&nbsp;</p>
                                                        <p class="Detalle" align="justify">El calor asociado a cada uno de los componentes de los gases de combustión se calcula de la siguiente manera:</p>
                                                        <p class="Detalle" align="center">&nbsp;<img src="../imagenes/EcuacionCaldera.2.jpg" /></p>
                                                        <p class="Detalle" align="justify">donde:</p>
                                                        <p class="Detalle" align="justify">Qi : Calor asociado al componente "i" de los gases de combustión [BTU/llbm]</p>
                                                        <p class="Detalle" align="justify"><img src="../imagenes/EcuacionHorno.15.jpg" />: Flujo másico del componente "i" en los gases de combustión [lbm/h]</p>
                                                        <p class="Detalle" align="justify">Hi : Entalpía del componente "i" de los gases de combustión a la temperatura de chimenea [BTU/lbm].</p>
                                                        <p class="Detalle" align="justify">Cpi : Capacidad calorífica del componente "i" de los gases de combustión a la temperatura de chimenea [BTU/lbm Â°F]</p>
                                                        <p class="Detalle" align="justify">Tchi : Temperatura de los gases de combustión en la chimenea [Â°F]</p>
                                                        <p class="Detalle" align="justify">&nbsp;</p>
                                                        <p class="Detalle" align="justify">Para calcular el flujo másico de cada componente "i" de los productos de combustión se emplea la siguiente ecuación:</p>
                                                        <p class="Detalle" align="center"><img src="../imagenes/EcuacionHorno.16.jpg" />&nbsp;</p>
                                                        <p class="Detalle" align="justify">donde:</p>
                                                        <p class="Detalle" align="justify">Xi : Fracción molar del componente "i" en los gases de combustión [adim]</p>
                                                        <p class="Detalle" align="justify"><img src="../imagenes/EcuacionHorno.17.jpg" />: Flujo másico de los gases de combustión [lbm/hr]</p>
                                                        <p class="Detalle" align="justify">PMi : Peso molecular del componente "i"</p>
                                                        <p class="Detalle" align="justify">PMgases combustión : Peso molecular de los gases de combustión.</p>
                                                        <p class="Detalle" align="justify">&nbsp;</p>
                                                        <p class="Detalle" align="justify">El peso molecular de los gases de combustión se calcula como se muestra a continuación:</p>
                                                        <p class="Detalle" align="center"><img src="../imagenes/EcuacionHorno.18.jpg" />&nbsp;</p>
                                                        <p class="Detalle" align="justify">&nbsp;</p>
                                                        <p class="DetalleNegrita" align="justify">Calor Perdido por los gases de combustión cuando la combustión es incompleta</p>
                                                        <p class="Detalle" align="justify">En un proceso donde la combustión del combustible es incompleta, además de producirse CO2, H2O, N2 y O2, puede detectarse la aparición de nuevos componentes como lo son el CO, NO, N2O, NO2 y el SO2. Aunque la práctica recomendada API-532 no utiliza el calor perdido por los gases de chimenea cuando la combustión es incompleta, con <a class="DetalleNegrita">EfiTerm</a> se puede calcular este calor siempre y cuando la composición de los gases de combustión sea conocida. Éste calor se calcula de la siguiente manera:</p>
                                                        <p class="Detalle" align="center">&nbsp;<img src="../imagenes/EcuacionHorno.19.jpg" /></p>
                                                        <p class="Detalle" align="justify">donde:</p>
                                                        <p class="Detalle" align="justify">QSO2 : Calor asociado al SO2 contenido en los gases de combustión [BTU/lbm]</p>
                                                        <p class="Detalle" align="justify">QNO : Calor asociado al NO contenido en los gases de combustión [BTU/lbm]</p>
                                                        <p class="Detalle" align="justify">QCO : Calor asociado al CO contenido en los gases de combustión [BTU/lbm]</p>
                                                        <p class="Detalle" align="justify">&nbsp;</p>
                                                        <p class="Detalle" align="justify">La composición de los gases de combustión puede obtenerse a través de un análisis de los mismos y existen varias maneras de realizarlos, una manera es la recolección de una muestra de éstos gases en la chimenea del equipo con un cilindro toma muestra para luego realizar el análisis Orsat en el laboratorio; otra manera es empleando un analizador de gases portátil, el cual permite analizar una muestra de gases directamente tomada en el equipo a través de una sonda. El análisis de la composición de los gases de combustión puede ser en base seca y en base húmeda, dependiendo de las características y funcionamiento del equipo.</p>
                                                        <p class="segundoNivel" align="justify"><a class="Detalle" href="metodologia.php#tblEncabezado">volver</a></p>
                                                        <p class="segundoNivel" align="justify">&nbsp;</p>
                                                        <p class="Titulo" id="ASME" align="justify">EFICIENCIA TÉRMICA DE CALDERAS&nbsp;- ASME PTC 4.1:&nbsp;</p>
                                                        <p class="Detalle" align="justify">Para calcular la eficiencia térmica de calderas se tomó como base la práctica recomendada ASME PTC 4-1998 "Fired Steam Generators"; la cual establece dos métodos para realizar dicho cálculo. El primer método se denomina "Balance de Energía", en donde se consideran todos los aportes y perdidas de energía en el sistema, tales como la energía contenida en el fluido de trabajo a la entrada del generador, en el combustible, en el aire para la combustión, en los equipos auxiliares, en los gases de combustión expulsados por la chimenea, entre otros; en el segundo método, conocido como "Entrada/Salida" se toma en cuenta únicamente la energía aportada por el combustible y la energía absorbida por el fluido de trabajo. El método utilizado en <a class="DetalleNegrita">EfiTerm</a> para calcular la eficiencia térmica de calderas es de "Entrada/Salida", el cual nos da resultados confiables con pocos datos de entrada, siempre y cuando las mediciones sean lo más exactas posibles con el fin de minimizar la incertidumbre. Método Entrada/Salida</p>
                                                        <p class="Detalle" align="justify">El método de entrada/salida está basado en la relación entre la energía disponible en el flujo de combustible y la energía contenida en el vapor a la salida de la caldera. En este caso la eficiencia térmica de la caldera se calcula empleando la siguiente expresión:</p>
                                                        <p class="Detalle" align="center"><img src="../imagenes/EcuacionCaldera.1.jpg" /></p>
                                                        <p class="Detalle" align="justify">donde:</p>
                                                        <p class="Detalle" align="justify">Qro : Calor absorbido por el fluido de trabajo [BTU/lbm]</p>
                                                        <p class="Detalle" align="justify">Qrf : Calor total disponible en el combustible [BTU/lbm]</p>
                                                        <p class="Detalle" align="justify">&nbsp;</p>
                                                        <p class="DetalleNegrita" align="justify">Cálculo de Qro</p>
                                                        <p class="Detalle" align="justify">Es el calor absorbido por el fluido de trabajo (en este caso el fluido es agua) y se calcula de la siguiente manera:</p>
                                                        <p class="Detalle" align="center">&nbsp; <img src="../imagenes/EcuacionCaldera.2.jpg" /></p>
                                                        <p class="Detalle" align="justify">donde:</p>
                                                        <p class="Detalle" align="justify"><img src="../imagenes/EcuacionCaldera.3.jpg" />: Flujo másico del fluido de trabajo (vapor/agua) a la salida de la caldera [lbm/h]</p>
                                                        <p class="Detalle" align="justify">Hlvz : Entalpía del vapor a la salida de la caldera [BTU/lbm]</p>
                                                        <p class="Detalle" align="justify">Henz : Entalpía del agua a la entrada de la caldera [BTU/lbm]</p>
                                                        <p class="Detalle" align="justify">&nbsp;</p>
                                                        <p class="Detalle" align="justify">En <a class="DetalleNegrita">EfiTerm</a> Qro se calcula como la sumatoria de la energía del vapor generado y la energía de la purga de la caldera.</p>
                                                        <p class="Detalle" align="justify">&nbsp;</p>
                                                        <p class="DetalleNegrita" align="justify">Cálculo de Qrf</p>
                                                        <p class="Detalle" align="justify">Es el calor disponible en el flujo de combustible, se determina empleando la siguiente ecuación:</p>
                                                        <p class="Detalle" align="center">&nbsp;<img src="../imagenes/EcuacionCaldera.4.jpg" /></p>
                                                        <p class="Detalle" align="justify">donde:</p>
                                                        <p class="Detalle" align="justify"><img src="../imagenes/EcuacionCaldera.5.jpg" />: Flujo másico de combustible alimentado a la caldera [lbm/h]</p>
                                                        <p class="Detalle" align="justify">HHV : Poder calorífico superior del combustible [BTU/lbm]</p>
                                                        <p class="Detalle" align="justify">&nbsp;</p>
                                                        <p class="DetalleNegrita" align="justify">Poder Calorífico Superior del Combustible (HHV)</p>
                                                        <p class="Detalle" align="justify">Indica la cantidad de energía liberada por el combustible cuando se toma en cuenta la formación de agua en forma líquida. Puede ser determinado mediante un análisis de laboratorio o estimado a partir de valores teóricos. En el caso de estimación teórica del poder calorífico para una mezcla de gases se emplea la siguiente ecuación:</p>
                                                        <p class="Detalle" align="center"><img src="../imagenes/EcuacionCaldera.6.jpg" />&nbsp;</p>
                                                        <p class="Detalle" align="justify">donde:</p>
                                                        <p class="Detalle" align="justify">HHVi : Poder calorífico superior del componente "i" [BTU/lbm]</p>
                                                        <p class="Detalle" align="justify">Ecuación 7.caldera : Flujo másico del componente "i" [lbm/h] .</p>
                                                        <p class="segundoNivel" align="justify"><a class="Detalle" href="metodologia.php#tblEncabezado">volver</a></p>
                                                        <p class="segundoNivel" align="justify">&nbsp;</p>
                                                    </td>
                                                </tr>

                                                <tr valign="top" height="100%">
                                                    <td valign="top" height="100%">
                                                        <table id="table2" height="100%" cellspacing="0" cellpadding="0" width="100%" border="0">
                                                            <tr>
                                                                <td valign="bottom" align="right" width="100%" height="100%">
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </form>
                <?php echo $pres->crearVentanaFin(); ?>
            </td>
        </tr>

        <tr>
            <td><?php echo $pres->crearPie(); ?></td>
        </tr>
    </table>
</body>
</html>

