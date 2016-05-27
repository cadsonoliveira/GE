<?php
	$diretorio = "../temp/";
	$ponteiro = opendir($diretorio);
	
	while ($nome_itens = readdir($ponteiro)){
		$itens[]=$nome_itens;
	}
	
	foreach ($itens as $listar){
		if(!is_dir($listar)){
			$array = explode('.',$listar);
			if (($array[1] == 'gif') || ($array[1] == 'jpg') || ($array[1] == 'jpeg')|| ($array[1] == 'png')){
				$html .= '<li><img class="pic" src="../temp/'.$listar.'" alt="" /></li>';
			}
		}
	}
	
	echo $html;
?>
