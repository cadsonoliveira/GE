<?php
	//Dá a opção de apagar as imagens do paciente anterior
	$existe_temp = false;
	$d = opendir("../temp");

	while ($entry = readdir($d)) {
		if($entry != "." && $entry != ".."){
			$existe_temp = true;
			break;
		}
	}
	if($existe_temp){
		echo "var apagar_imagens = confirm('Apagar as imagens do paciente anterior?');";
	}else{
		echo"var apagar_imagens = false;";
	}
?>