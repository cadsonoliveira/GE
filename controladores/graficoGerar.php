<?php

  include_once('../bibliotecas/phplot/phplot.php');

  //$graph =& new PHPlot(400,200);
  $graph = new PHPlot(400,200);
  $graph->SetPlotType("pie");
  $legend = array();
  $values= array();
  $values[]='dado';
  
  if(isset($_GET['sucesso'])){
     $sucesso=$_GET['sucesso'];
     $legend[] = "Sucesso";
     $values[]=$sucesso;
  }
  if(isset($_GET['insucesso'])){
     $insucesso=$_GET['insucesso'];
     $legend[] = "Insucesso";
     $values[]=$insucesso;
  }
  if(isset($_GET['pendente'])){
     $pendente=$_GET['pendente'];
     $legend[] = "Pendente";
     $values[]=$pendente;
  }
  if(isset($_GET['cancelado'])){
     $cancelado=$_GET['cancelado'];
     $legend[] = "Cancelado";
     $values[]=$cancelado;
  }
  
  //Define some data
  $example_data = array($values);

  $graph->SetDataValues($example_data);
  $graph->SetLegendPixels(1,5,false);
  $graph->SetLegend($legend);
  //Draw it
  $graph->DrawGraph();
  ?>
