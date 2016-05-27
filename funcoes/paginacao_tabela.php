<?php
		//Primeira página
			if($pag_atual != 0){
				$primeira_pagina = '<button type="button" onclick="location.href=\''.$page_base.'qtdpag='.$qtd_resultado_por_pagina.'&amp;pag=1&amp;letra='.$letra.'\'">&laquo; Primeira</button>';
			}
			else{
				$primeira_pagina ='<button type="button" style="visibility:hidden">&laquo; Primeira</button>';
			}
		
		//Página Anterior
		/*if($pag_atual > 0){
			$pag_anterior = '<a href="'.$page_base.'&pag='.$pag_atual.'"><img src="img/p_b.gif" alt="Anterior" width="10" height="10" border="0" /></a>';
		} else {
			$pag_anterior = '<a><img src="img/p_b.gif" alt="Anterior" width="10" height="10" border="0" /></a>';
		}
		
		//Próxima Página
		if($pag_atual < $qtd_paginas-1) {
			$prox_pagina = '<a href="'.$page_base.'&pag='.($pag_atual+2).'"><img src="img/p_n.gif" alt="Próxima" width="10" height="10" border="0" /></a>';
		} else {
			$prox_pagina = '<a><img src="img/p_n.gif" alt="Próxima" width="10" height="10" border="0" /></a>';
		}*/
		
		//Última página
		if($pag_atual < ($qtd_paginas-1)) {
			$ultima_pagina = '<button type="button" onclick="location.href=\''.$page_base.'qtdpag='.$qtd_resultado_por_pagina.'&amp;pag='.($qtd_paginas).'&amp;letra='.$letra.'\'">Última &raquo;</button>';
		} else {
			$ultima_pagina = '<button type="button" style="visibility:hidden">Última &raquo;</button>';
		}
		
		/*** Paginação Tabela ***/
		$paginacao = /*'
			<div id="pages">
			'.*/$primeira_pagina/*.'
			'.$pag_anterior.'
		'*/;
		if($qtd_paginas > 1){
			while($cont < $qtd_paginas){
				if($pag_atual == $cont){
					$paginacao .= '<span>'.($cont+1).'</span>';
				} else {
					$paginacao .= '<a href="'.$page_base.'qtdpag='.$qtd_resultado_por_pagina.'&amp;pag='.($cont+1).'&amp;letra='.$letra.'&amp;chave='.$chave.'" title="Ir para a página '.($cont+1).'">'.($cont+1).'</a>';
				}
				$cont++;
			}
		} else {
			$paginacao .= '<span></span>';
		}
		$paginacao .= /*'
			'.$prox_pagina.'
			'.*/$ultima_pagina/*.'
			</div>
		'*/;		
		
		
?>