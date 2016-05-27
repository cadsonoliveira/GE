<?php
    include_once("../classes/classPersistencia.php");
	include_once("../classes/classCommIvision2Sge.php");

	$ivision2sge = new CommIvision2Sge(1);

	$timeout = $ivision2sge->getTimeout();
	// if($timeout >= 10)
	// {
		// $ivision2sge->setCameraConectada("0");
	// }
	// else
	// {
		// $timeout++;
		// $ivision2sge->setTimeout($timeout);
	// }
	// $ivision2sge->bUpdate();
	
	$bool_modo_cam = ($ivision2sge->getModoCam() == "T") ? "true" : "false";
	$bool_video_iniciado = ($ivision2sge->getVideoIniciado() == "T") ? "true" : "false";
	
	$result = '{"timeout":"'.$timeout.'","modo_cam":'.$bool_modo_cam.' , "video_iniciado":'.$bool_video_iniciado.', "camera_conectada":"'.$ivision2sge->getCameraConectada().'"}';
	
	echo $result;
	
?>