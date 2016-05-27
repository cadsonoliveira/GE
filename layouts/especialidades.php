<?php
	//Antiga página configuracao_geral.php
    session_start();

    if((!isset($_SESSION['USUARIO'])) && ($_SESSION['USUARIO']['VALIDA'] != true)){
        header("Location: ../layouts/login.php?tipo=2");
    } else {

    include_once ("../classes/classPersistencia.php");
    include_once ("../classes/classEspecialidade.php");
    include_once ("../classes/classConfiguracao.php");
	
    //echo $_GET['div_id'].'|;|';

    $erro = -1;
    if(isset($_GET['acao']) && $_GET['acao']=='excluir'){
        $persistencia = new Persistencia();
        $sSql = "SELECT * FROM match_code WHERE id_especialidade=".$_GET['id'];

        $persistencia->bExecute($sSql);

        $configuracao = new Configuracao(1);
        $id_especialidade = $configuracao->getIdEspecialidade();

        if($id_especialidade != $_GET['id']){
            if($persistencia->getDbNumRows() > 0){
               echo '<script> alert("Existe Procedimentos Para Esta Especialidade.\nRemova Todos os Procedimentos Antes de Excluir a Especialidade");</script>';
            } else {
                $especialidade = new Especialidade($_GET['id']);
                $especialidade->bDelete();
                $erro = 0;
            }
        } else {
            echo '<script> alert("Não é Possível Excluir a Especialidade Ativa");</script>';
        }
    }
	
    $config = new Configuracao(1);
    $especialidade = new Especialidade($config->getIdEspecialidade());
	
		if(isset($_GET['erro'])){
			echo "<script>erro_excluir('".$_GET['erro']."')</script>";
		}
    }
	
	    $qtd_resultado_por_pagina = 10;
    if(isset($_GET['qtdpag']) && $_GET['qtdpag']!=0 && $_GET['qtdpag']!="")
        $qtd_resultado_por_pagina = $_GET['qtdpag'];

    $pag_atual = (isset($_GET['pag'])&&$_GET['pag']!=0) ? ($_GET['pag']-1) : 0;

    $pers = new Persistencia();

    $sSql = "SELECT * FROM especialidade ORDER BY descricao";
    $pers->bExecute($sSql);


    $qtd_registros = $pers->getDbNumRows();
    $qtd_paginas = ($qtd_registros%$qtd_resultado_por_pagina==0) ? ($qtd_registros/$qtd_resultado_por_pagina) : ((int)($qtd_registros/$qtd_resultado_por_pagina)+1);

    $cont = 0;
    $page_base = 'especialidades.php?';

		/**** Paginação *****/
		
		//Primeira página
		if($pag_atual != 0){
			$primeira_pagina = '<button type="button" onclick="location.href=\''.$page_base.'qtdpag='.$qtd_resultado_por_pagina.'&amp;pag=1\'">&laquo; Primeira</button>';
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
			$ultima_pagina = '<button type="button" onclick="location.href=\''.$page_base.'qtdpag='.$qtd_resultado_por_pagina.'&amp;pag='.($qtd_paginas).'\'">Última &raquo;</button>';
		} else {
			$ultima_pagina = '<button type="button" style="visibility:hidden">Última &raquo;</button>';
		}

		$paginacao = /*'
			<div id="pages">
			'.*/$primeira_pagina;
		
		if($qtd_paginas > 1){
			while($cont < $qtd_paginas){
				if($pag_atual == $cont){
					$paginacao .= '<span>'.($cont+1).'</span>';
				} else {
					$paginacao .= '<a href="'.$page_base.'qtdpag='.$qtd_resultado_por_pagina.'&amp;pag='.($cont+1).'" title="Ir para a página '.($cont+1).'">'.($cont+1).'</a>';
				}
				$cont++;
			}
		} else {
			$paginacao .= '<span></span>';
		}
		$paginacao .= /*'
			'.$prox_pagina.'
			'.*/$ultima_pagina;


    $linha_registro = $pag_atual * $qtd_resultado_por_pagina;
    $tabela = "";

    if(!$pers->getDbNumRows() > 0){
        $tabela = '
            <tr>
                <td colspan="3" style="text-align:center;"><b>Nenhum registro encontrado!</b></td>
            </tr>';
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
                        <td onclick="editar_especialidade('.$res['id_especialidade'].');" class="numero">'.$res['id_especialidade'].'</td>
                        <td onclick="editar_especialidade('.$res['id_especialidade'].');" class="especialidade" >'.utf8_encode($res['descricao']).'</td>
                        <td class="opcoesEspecialidades">
							<span style="display:block; margin:auto; width:58px;">
								<a class="ir editar" onclick="editar_especialidade('.$res['id_especialidade'].');" title="Editar especialidade">Editar
								</a>
								<a class="ir excluir" onclick="remover_especialidade('.$res['id_especialidade'].','.$qtd_resultado_por_pagina.','.($pag_atual+1).');" title="Excluir especialidade">Excluir</a>
							</span>
                        </td>
                    </tr>
                    ';

                $linha_registro++;
            }
        }
    }
?>


<?php include_once("include/header.php") ?>
<?php include_once("include/topo.php") ?>
<?php include_once("include/menu.php") ?>

    <div id="conteudo">
        <div id="dropshadow">
            <div id="breadcrumb">
                <ul>
                    <li><span class="breadcrumbEsquerda"></span><a>configurações</a><span class="breadcrumbDireita"></span>
                        <ul>
                            <li><span class="breadcrumbEsquerda bcrumbSelect"></span><h2 class="bcrumbAtivo bcrumbSelect">especialidades</h2><span class="breadcrumbDireita bcrumbSelect"></span></li>
                        </ul>
                    </li>
                </ul>
            </div> <!--Fecha div breadcrumb-->
		
        <div id="container" class="clearfix">
            <p><button class="botaoPositivo floatRight" type="button" onclick="nova_especialidade();">Adicionar Especialidades</button></p>
            
            <p id="filtros">Exibição</p>
            <div class="boxLista">
                <form class="listarResultados" action="#" method="post"  name="grid">
                    <p>
                        <label id="resultadosPagina" for="resultados_por_pagina">Resultados por Página:</label>
                        <select onchange="qtdpag();" id="qnt_pag" name="qnt_pag">
                            <option value="10" <?php if($qtd_resultado_por_pagina==10) echo "selected='selected'"; ?>>10</option>
                            <option value="20" <?php if($qtd_resultado_por_pagina==20) echo "selected='selected'"; ?>>20</option>
                            <option value="30" <?php if($qtd_resultado_por_pagina==30) echo "selected='selected'"; ?>>30</option>
                        </select>
                    </p>
                </form>
            </div><!--fecha busca-->
            
            <p id="totalRegistros">Total de registros: <?php echo $qtd_registros ?></p>
            
            <table title="Lista de Especialidades" summary="Lista completa de Especialidades" class="habilitaHoverTabela">
              <caption class="hidden">Lista de Especialidades</caption>
              <thead>
                  <tr>
                    <th class="numero">Número</th>
                    <th>Especialidade</th>
                    <th class="opcoesEspecialidades">Opções</th>
                  </tr>
              </thead>
              <tbody>
				<?php
					echo $tabela;
                ?>
              </tbody>
            </table>
            
            <?php include_once("include/paginacao_tabela.php") ?>
            
		<?php include_once("include/footer.php") ?>
        
        <script type="text/javascript">
            var nova_especialidade = function(){
                var md = new Modal(['modal']);
                md.setHeader('Adicionar Especialidade');
        
                var fm = new Form('form_e',['../controladores/especialidadeGravar.php','post'],'field','');
                fm.addEvent('success',function(){
                    (function(){this.fadeAndRemove()}.bind(this)).delay(500);
                    //xhSendPost("../controladores/AJAX.configuracao_geral.php?div_id=tabela_dados&qntdpag="+document.grid.qnt_pag.value);
                    //altera_pagina(document.grid.qnt_pag.value,1);
                    location.href="especialidades.php?qtdpag="+document.grid.qnt_pag.value+"&pag="+pag;
                }.bind(md));
                
                fm.attach(md.win);
        
                descricao = fm.newField('Descrição','descricao',550,'text', '', 100);
        
            fm.addEvent('request',function(a){
                    if(this.value.trim() == ""){
                        a.cancel();
                        a.failure();
                        alert('Informe um nome válido');
                    }
            }.bind(descricao));
        
                fm.attachSendBar(['Cadastrar Especialidade','mb']);
                md.show();
            }
        
            function remover_especialidade(valor,qtd_pag,pag){
                if(confirm('Deseja realmente remover esta especialidade?')){
                    //var myHTMLRequest = new Request.HTML().get('../controladores/AJAX.configuracao_geral.php?div_id=tabela_dados&acao=excluir&id=' + valor + '&qtdpag=' + qtd_pag +'&pag=' + pag);
                    //xhSendPost('../controladores/AJAX.configuracao_geral.php?div_id=tabela_dados&acao=excluir&id=' + valor + '&qtdpag=' + qtd_pag +'&pag=' + pag);
                    //erro_excluir(document.grid.erro.value);
                    //alert(document.grid.erro.value);
                    //altera_pagina(qtd_pag, pag);
                    location.href="../controladores/especialidadeGravar.php?acao=excluir&id=" + valor;
                    
                }
            }
        
            function selecionar_especialidade(valor,descricao){
                location.href="../controladores/configuracaoGravar.php?acao=editar&id=" + valor;
                alert('Especialidade \''+descricao+'\' selecionada com sucesso.');
        
            }
        
            function editar_especialidade(valor){
                location.href = "editar_especialidade.php?id=" + valor;
            }
        
            function qtdpag(){
                if($('qnt_pag')!=null)
                    location.href = "especialidades.php?qtdpag="+document.grid.qnt_pag.value;
            }
        
            function altera_pagina(qtd_pag, pag){
                xhSendPost("../controladores/AJAX.configuracao_geral.php?div_id=tabela_dados&qtdpag=" + qtd_pag +"&pag=" + pag);
            }
        
            function erro_excluir(erro){
                switch (erro){
                    case "1":
                        alert("Não é Possível Excluir a Especialidade Ativa");
                        break;
                    case "2":
                        alert("Existe Procedimentos Para Esta Especialidade.\nRemova Todos os Procedimentos Antes de Excluir a Especialidade");
                        break;
                }
            }
        </script>
    </body>
</html>