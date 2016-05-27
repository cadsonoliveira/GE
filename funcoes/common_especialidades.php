<?php
	$qtd_resultado_por_pagina = 10;
	if(isset($_GET['qtdpag']) && $_GET['qtdpag']!=0 && $_GET['qtdpag']!="")
		$qtd_resultado_por_pagina = $_GET['qtdpag'];

	$pag_atual = (isset($_GET['pag']) && ($_GET['pag']!= 0)) ? ($_GET['pag']-1) : 0;

	$pers = new Persistencia();

	$sSql = "SELECT * FROM match_code ";
	$sCondSql = "";

	$sCondSql = " WHERE id_especialidade = '".$id."'";
	$sSql .= $sCondSql.' ORDER BY descricao';
	#$sSql .= 'LIMIT '.($pag_atual*$qtd_resultado_por_pagina).', '.($pag_atual*$qtd_resultado_por_pagina + $qtd_resultado_por_pagina);
	$pers->bExecute($sSql);

	$qtd_registros = $pers->getDbNumRows();
	$qtd_paginas = ($qtd_registros%$qtd_resultado_por_pagina==0) ? ($qtd_registros/$qtd_resultado_por_pagina) : ((int)($qtd_registros/$qtd_resultado_por_pagina)+1);

	$cont = 0;
	$page_base = 'editar_especialidade.php?id='.$_GET['id'];
	
	//Primeira página
	if($pag_atual != 0){
		$primeira_pagina = '<button type="button" onclick="location.href=\''.$page_base.'&amp;qtdpag='.$qtd_resultado_por_pagina.'&pag=1\'">&laquo; Primeira</button>';
	}else{
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
		$ultima_pagina = '<button type="button" onclick="location.href=\''.$page_base.'&amp;qtdpag='.$qtd_resultado_por_pagina.'&pag='.($qtd_paginas).'\'">Última &raquo;</button>';
	} else {
		$ultima_pagina = '<button type="button" style="visibility:hidden">Última &raquo;</button>';
	}
	
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
				$paginacao .= '<a href="'.$page_base.'&qtdpag='.$qtd_resultado_por_pagina.'&amp;pag='.($cont+1).'" title="Ir para a página '.($cont+1).'">'.($cont+1).'</a>';
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

	/**** Montagem tabela *****/
	$linha_registro = $pag_atual * $qtd_resultado_por_pagina;
	$tabela = "";

	if(!$pers->getDbNumRows() > 0){
		$tabela = '
			<tr>
				<td colspan="4" style="text-align:center;"><b>Nenhum registro encontrado!</b></td>
			</tr>
		';
	} else {
		for($cont = 0; $cont < $qtd_resultado_por_pagina ; $cont++){
			if($linha_registro < $qtd_registros){
				$pers->bCarregaRegistroPorLinha($linha_registro);
				$res = $pers->getDbArrayDados();
					
				if($cont % 2 == 0){
					$cor_linha_tabela = "tableColor1";
				}else{
					$cor_linha_tabela = "tableColor2";
				}

				$tabela .= '
					<tr class="'.$cor_linha_tabela.'">
						<td class="matchCode" onclick="editar_procedimento(\''.$res['id_match_code'].'\',\''.$res['id_especialidade'].'\',\''.utf8_encode($res['descricao']).'\',\''.$res['tipo'].'\');">'.$res['id_match_code'].'</td>
						<td class="procedimento" onclick="editar_procedimento(\''.$res['id_match_code'].'\',\''.$res['id_especialidade'].'\',\''.utf8_encode($res['descricao']).'\',\''.$res['tipo'].'\');" class="o">'.utf8_encode($res['descricao']).'</td>
						<td class="tipo" onclick="editar_procedimento(\''.$res['id_match_code'].'\',\''.$res['id_especialidade'].'\',\''.utf8_encode($res['descricao']).'\',\''.$res['tipo'].'\');">'.$res['tipo'].'</td>
						<td class="opcoesEspecialidades">
							<span style="display:block; margin:auto; width:58px;">
								<a class="ir editar" onclick="editar_procedimento(\''.$res['id_match_code'].'\',\''.$res['id_especialidade'].'\',\''.utf8_encode($res['descricao']).'\',\''.$res['tipo'].'\');">Editar</a>
								<a class="ir excluir" onclick="remover_procedimento('.$res['id_match_code'].', '.$res['id_especialidade'].' );">Excluir</a>
							</span>
						</td>
					</tr>
				';

				$linha_registro++;
			}
		}
	}		
?>