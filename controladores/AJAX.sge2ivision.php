<?php
    include_once("../classes/classPersistencia.php");
	include_once("../classes/classCommSge2Ivision.php");

	$sge2ivision = new CommSge2Ivision(1);
	
	if(isset($_POST['tipo'])){
		$sge2ivision = new CommSge2Ivision(1);
		
		if($_POST['tipo'] == "r"){
			$bool_gravar_video = ($sge2ivision->getGravarVideo() == "T") ? "true" : "false";
			echo '{"gravar_video":"'.$bool_gravar_video.'"}';
		} elseif($_POST['tipo'] == "w") {
		
			$gravar_video = $_POST['gravar_video'];
			if($gravar_video == "true"){
					$char_gravar_video = "T";
			} else {
					$char_gravar_video = "F";
			}
			$sge2ivision->setGravarVideo($char_gravar_video);
			
			$sge2ivision->bUpdate();
		}
	}
?>