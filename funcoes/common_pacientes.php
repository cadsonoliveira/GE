<?php
		$qtd_resultado_por_pagina = 10;
		$chave = "";
		
		if(isset($_GET['letra']))
			$letra = $_GET['letra'];
		else
			$letra = '';
	
		if(isset($_GET['qtdpag']) && $_GET['qtdpag']!=0 && $_GET['qtdpag']!="")
		
		$qtd_resultado_por_pagina = $_GET['qtdpag'];
		$pag_atual = (isset($_GET['pag'])&&$_GET['pag']!=0) ? ($_GET['pag']-1) : 0;
		
		$_SESSION['pag_atual'] = $pag_atual+1;
		$_SESSION['letra'] = $letra;
		$_SESSION['qtd_resultado_por_pagina'] = $qtd_resultado_por_pagina;
		
		$pers = new Persistencia();
		$sSql = "SELECT * FROM paciente, pessoa WHERE paciente.id_pessoa = pessoa.id_pessoa";
		$sCondSql = "";
		
		if(isset($_GET['chave'])){
			$sCondSql = " AND pessoa.nome LIKE '%".$_GET['chave']."%'";
			$chave = $_GET['chave'];
		}else {
			$sCondSql = " AND nome REGEXP '^".$letra."'";
			$chave = "";
		}
		
		$sSql .= $sCondSql.' ORDER BY nome';
		#$sSql .= 'LIMIT '.($pag_atual*$qtd_resultado_por_pagina).', '.($pag_atual*$qtd_resultado_por_pagina + $qtd_resultado_por_pagina);
		$pers->bExecute($sSql);
	
		$qtd_registros = $pers->getDbNumRows();
		
		$qtd_paginas = ($qtd_registros%$qtd_resultado_por_pagina==0) ? ($qtd_registros/$qtd_resultado_por_pagina) : ((int)($qtd_registros/$qtd_resultado_por_pagina)+1);
	
		$cont = 0;
		$page_base = $_SERVER['PHP_SELF'].'?';
		if(isset($_REQUEST['chave'])){
			$page_base .= $_REQUEST['chave'];
		}
		
		/**** Filtro Por letra *****/
		include_once ("../funcoes/filtro_por_letra_tabela.php");	

		/**** Paginação Primeira e Última Página da Tabela *****/
		include_once ("../funcoes/paginacao_tabela.php");		
		
?>