<?php
	//Antiga página planos_geral.php
    session_start();

    include_once ("../classes/classPersistencia.php");
    include_once ("../classes/classContato.php");

    if((!isset($_SESSION['USUARIO'])) && ($_SESSION['USUARIO']['VALIDA'] != true)){
        header("Location: ../layouts/login.php?tipo=2");
    } else {

        $qtd_resultado_por_pagina = 10;
        if(isset($_GET['qtdpag']) && $_GET['qtdpag']!=0 && $_GET['qtdpag']!="")
            $qtd_resultado_por_pagina = $_GET['qtdpag'];

			$pag_atual = (isset($_GET['pag'])&&$_GET['pag']!=0) ? ($_GET['pag']-1) : 0;
	
			$pers = new Persistencia();
	
			$sSql = "SELECT * FROM planosaude";
			$sCondSql = "";
			
        if(isset($_GET['chave'])){
            /*$sCondSql = " WHERE planosaude.".$_GET['filtro']." LIKE '%".$_GET['chave']."%'";
			$chave = $_GET['chave'];
			$filtro = $_GET['filtro'];*/
			$sCondSql = " WHERE planosaude.nome LIKE '%".$_GET['chave']."%'";
			$chave = $_GET['chave'];
			$filtro = "nome";
        }else{
			$chave = "";
			$filtro = "";
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
		
		$urlFiltro = "";
		
		if($chave != ""){
			$urlFiltro = "&filtro=".$filtro."&chave=".$chave;
		}
				
		/*Paginação*/
		
		//Primeira página
        if($pag_atual != 0){
 //           $primeira_pagina = '<button type="button" onclick="location.href=\''.$page_base.'&pag=1\'">&laquo; Primeira</button>';
			$primeira_pagina = '<button type="button" onclick="location.href=\''.$page_base.'qtdpag='.$qtd_resultado_por_pagina.'&amp;pag=1'.$urlFiltro.'\'">&laquo; Primeira</button>';
        } else {
            $primeira_pagina = '<button type="button" style="visibility:hidden">&laquo; Primeira</button>';
        }
		
		/*//Página anterior
        if($pag_atual > 0){
            $pag_anterior = '<a href="'.$page_base.'&pag='.$pag_atual.'"><img src="img/p_b.gif" alt="Anterior" width="10" height="10" border="0" /></a>';
        } else {
            $pag_anterior = '<a><img src="img/p_b.gif" alt="Anterior" width="10" height="10" border="0" /></a>';
        }
		
		//Próxima página
        if($pag_atual < $qtd_paginas-1){
            $prox_pagina = '<a href="'.$page_base.'&pag='.($pag_atual+2).'"><img src="img/p_n.gif" alt="Próxima" width="10" height="10" border="0" /></a>';
        } else{
            $prox_pagina = '<a><img src="img/p_n.gif" alt="Próxima" width="10" height="10" border="0" /></a>';
        }*/
		
		//Última página
		if($pag_atual < ($qtd_paginas-1)) {
			$ultima_pagina = '<button type="button" onclick="location.href=\''.$page_base.'qtdpag='.$qtd_resultado_por_pagina.'&amp;pag='.($qtd_paginas).$urlFiltro.'\'">Última &raquo;</button>';
		} else {
			$ultima_pagina = '<button type="button" style="visibility:hidden">Última &raquo;</button>';
		}
		
        $paginacao = 
                    $primeira_pagina/*.'
                    '.$pag_anterior.'
            '*/;
        if($qtd_paginas > 1){
            while($cont < $qtd_paginas){
                if($pag_atual == $cont){
                    $paginacao .= '<span>'.($cont+1).'</span>';
                } else{
                    $paginacao .= '<a href="'.$page_base.'qtdpag='.$qtd_resultado_por_pagina.'&amp;pag='.($cont+1).$urlFiltro.'" title="Ir para a página '.($cont+1).'">'.($cont+1).'</a>';
                }
                $cont++;
            }
        } else {
            $paginacao .= '<span></span>';
        }
        $paginacao .= /*'
                    '.$prox_pagina.'
                    '.*/$ultima_pagina;
			
		/**** Montagem tabela *****/
        $linha_registro = $pag_atual * $qtd_resultado_por_pagina;
        $tabela = "";

        if(!$pers->getDbNumRows() > 0){
            $tabela = '
                <tr>
                    <td colspan="8" style="text-align:center;"><b>Nenhum registro encontrado!</b></td>
                </tr>
            ';
        } else{			
			
            for($cont = 0; $cont < $qtd_resultado_por_pagina ; $cont++){
                if($linha_registro < $qtd_registros){
                    $pers->bCarregaRegistroPorLinha($linha_registro);
                    $res = $pers->getDbArrayDados();
                    if($res != NULL){
                        $contato = new Contato($res['id_contato']);
                    }
					
					if($cont % 2 == 0){
						$cor_linha_tabela = "tableColor1";
					}else{
						$cor_linha_tabela = "tableColor2";
					}
					
                    $tabela .= '
						<tr class="'.$cor_linha_tabela.'">
							<td onclick="editar_plano('.$res['id_plano_saude'].')" class="numeroPLano">'.$res['id_plano_saude'].'</td>
							<td onclick="editar_plano('.$res['id_plano_saude'].')" class="nomePLano">'.utf8_encode($res['nome']).'</td>'
							//<td onclick="editar_plano('.$res['id_plano_saude'].')" class="codigoPLano">'.utf8_encode($res['codigo']).'</td>
							.'<td onclick="editar_plano('.$res['id_plano_saude'].')" class="telefonePLano">'.utf8_encode($contato->getTelefoneFixo()).'</td>
							<td onclick="editar_plano('.$res['id_plano_saude'].')" class="telefonePLano">'.utf8_encode($contato->getTelefoneComercial()).'</td>
							<td onclick="editar_plano('.$res['id_plano_saude'].')" class="celularPLano">'.utf8_encode($contato->getTelefoneCelular()).'</td>
							<td onclick="editar_plano('.$res['id_plano_saude'].')" class="emailPLano">'.utf8_encode($contato->getEmail()).'</td>
							<td class="opcoesPlano">
								<span style="display:block; margin:auto; width:52px;">
									<a class="ir editar" onclick="editar_plano('.$res['id_plano_saude'].')"  title="Editar dados do plano de saúde">Editar</a>
									<a class="ir excluir" onclick="remover_plano('.$res['id_plano_saude'].');" title="Excluir plano de saúde">Excluir</a>
								</span>
							</td>
						</tr>
                    ';
                    $linha_registro++;
                }
            }
        }
	}
?>

<?php include_once("include/header.php") ?>
<?php include_once("include/topo.php") ?>
<?php include_once("include/menu.php") ?>

    <div id="conteudo">
    	<?php include_once("include/filtro_busca_planos.php") ?>
        <div id="dropshadow">
        <div id="breadcrumb">
                    <ul>
                        <li><span class="breadcrumbEsquerda bcrumbSelect"></span><h2 class="bcrumbAtivo bcrumbSelect">planos de saúde</h2><span class="breadcrumbDireita bcrumbSelect"></span></li>
                    </ul>
        </div> <!--Fecha div breadcrumb-->


        <div id="container" class="clearfix">
        	<p id="totalRegistros">Total de registros: <?php echo $qtd_registros ?></p>
        
            <h3 class="tituloBox">Filtros</h3>
            <div class="boxLista">
                <form class="listarResultados" action="#" method="post" name="grid">
                    <p>
                        <label id="resultadosPagina" for="qnt_pag">Resultados por Página:</label>
			<select onchange="qtdpag();" id="qnt_pag" name="qnt_pag">
                            <option <?php if($qtd_resultado_por_pagina==10) echo "selected='selected'"; ?> value="10">10</option>
                            <option <?php if($qtd_resultado_por_pagina==20) echo "selected='selected'"; ?> value="20">20</option>
                            <option <?php if($qtd_resultado_por_pagina==30) echo "selected='selected'"; ?> value="30">30</option>
                        </select>
            
                    </p>
                </form>
            </div><!--fecha boxLista-->
            <table title="Lista de Planos de Saúde" summary="Lista completa dos planos de saúde" class="habilitaHoverTabela">
              <caption class="hidden">Lista de Planos de Saúde</caption>
              <thead>
                  <tr>
                    <th class="numeroPLano">Código</th>
                    <th>Plano de Saúde</th>
                    <!--<th class="codigoPLano">Código</th>-->
                    <th class="telefonePLano">Telefone Fixo</th>
                    <th class="telefonePLano">Telefone Fixo</th>
                    <th class="celularPLano">Celular</th>
                    <th class="emailPLano">E-mail</th>
                    <th class="opcoesPlano">Opções</th>
                  </tr>
              </thead>
              <tbody>
				  <?php echo $tabela; ?>
              </tbody>
            </table>
            
            <?php include_once("include/paginacao_tabela.php") ?>
            
		<?php include_once("include/footer.php") ?>
        
        <script type="text/javascript">
            function editar_plano(valor){
                    location.href = "cadastro_plano_de_saude.php?acao=editar&id=" + valor;
                }
                function remover_plano(valor){
                if(confirm('Deseja realmente remover este plano de saúde?')){
                    xhSendPostPlano('../controladores/planoSaudeGravar.php?acao=excluir&id=' + valor, document.bs_field);
                }
            }
            
            function do_readyStateChangePlano(){
                if (xhReq.readyState != 4)  { return; }   
                    sAux = xhReq.responseText;
                    aAux = sAux.split("|;|");
                    if(aAux[1]!='')
                        alert(aAux[1]);
                    else
                        location.reload(true);
            }
        
            function xhSendPostPlano(url,form){
                var form_string = getObj(form);  
                xhReq.open("POST", url, true);
                xhReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');
                xhReq.onreadystatechange = do_readyStateChangePlano;
                xhReq.send(form_string);
            }
        
            function qtdpag(){
                if($('qnt_pag')!=null)
                    //location.href = "planos_de_saude.php?qtdpag="+document.grid.qnt_pag.value;
                    location.href = "planos_de_saude.php?qtdpag="+$('qnt_pag').value+"&pag="+"<?php echo $pag_atual+1 ?>"<?php if($filtro != ""){echo "+'&filtro='+'".$filtro."'";} if($chave != ""){echo "+'&chave='+'".$chave."'";} ?>;
            }
        </script>
    </body>
</html>