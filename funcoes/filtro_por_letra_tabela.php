<?php
		/**** Filtro por letras *****/
		if($letra == ""){
			$indice_letras = '| <span>Todos</span> - ';
		}else{
			$indice_letras ='| <a href="'.$page_base.'qtdpag='.$qtd_resultado_por_pagina.'" title="Lista todos os pacientes">Todos</a> - ';
		}
		
		for($i=65;$i<91;$i++){
			if($letra == chr($i)){
				$indice_letras .= '<span>'.chr($i).'</span>';
			}else{
				$indice_letras.='<a href="'.$page_base.'qtdpag='.$qtd_resultado_por_pagina.'&amp;pag=1&amp;letra='.chr($i).'" title="Lista os pacientes cujos nomes comecem com a letra '.chr($i).'">'.chr($i).'</a>';
			}
		}
		$indice_letras;


?>