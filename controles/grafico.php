<?php
    include "../../jpgraph-2.1.1/src/jpgraph.php";
    include "../../jpgraph-2.1.1/src/jpgraph_bar.php";
    include "../config.php";
    include "../clases/clad.php";

    $config = new config();
    $clad = new clad($config->queryString);

    // We need some data
    $datos = $clad->obtenerLogIntervalo();
    $c = count($datos);
    
    for($i = 0; $i < $c; $i++){
        $datay[] = $datos[$i]["visitas"];
        $datax[] = $datos[$i]["periodo"];
    }
    
    // Setup the graph. 
    $graph = new Graph(590,200,"auto");
    $graph->SetScale("textlin");
    $graph->img->SetMargin(30, 5, 15, 20);
    $graph->SetMarginColor('white');
    $graph->SetFrame(false);
    $graph->xaxis->SetTickLabels($datax);
    
    //$graph->title->Set('Visitas');
    //$graph->title->SetColor('darkred');
    
    // Setup font for axis
    $graph->xaxis->SetFont(FF_FONT1);
    $graph->yaxis->SetFont(FF_FONT1);
    
    // Create the bar pot
    $bplot = new BarPlot($datay);
    $bplot->SetWidth(0.3);

    $bplot->value->SetFormatCallback("mostrarValor");
    $bplot->value->Show();  
    
     // Setup color for gradient fill style 
    $bplot->SetFillGradient("#880000", "#FF0000", GRAD_MIDVER);
    
    // Set color for the frame of each bar
    $bplot->SetColor("navy");
    $graph->Add($bplot);
    
    // Finally send the graph to the browser
    $graph->Stroke();

    function mostrarValor($aVal) {
        return sprintf($aVal);
    }
?>
