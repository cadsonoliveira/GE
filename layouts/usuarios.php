<?php
	//Antiga página usuarios_geral.php
    session_start();

    if((!isset($_SESSION['USUARIO'])) && ($_SESSION['USUARIO']['VALIDA'] != true)){
        header("Location: ../layouts/login.php?tipo=2");
    } else {
        include_once ("../classes/classPersistencia.php");

	$qtd_resultado_por_pagina = 10;
    if(isset($_GET['qtdpag']) && $_GET['qtdpag']!=0 && $_GET['qtdpag']!="")
        $qtd_resultado_por_pagina = $_GET['qtdpag'];

	$pag_atual = (isset($_GET['pag'])&&$_GET['pag']!=0) ? ($_GET['pag']-1) : 0;

	$pers = new Persistencia();
	
	$sSql = "SELECT * FROM usuario, pessoa WHERE usuario.id_pessoa = pessoa.id_pessoa";
	$sCondSql = "";
	if(isset($_GET['chave']))
	{
		$sCondSql = " AND (pessoa.nome LIKE '%".$_GET['chave']."%' OR usuario.tipo_acesso LIKE '%".$_GET['chave']."%') ";
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
			'.*/$primeira_pagina/*.'
			'.$pag_anterior.'
		'*/;
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
			'.*/$ultima_pagina/*.'
			</div>
		'*/;

	$linha_registro = $pag_atual * $qtd_resultado_por_pagina;
	$tabela = "";

	if(!$pers->getDbNumRows() > 0) {
		$tabela = '<tr>
						<td colspan="5" style="text-align:center;"><b>Nenhum registro encontrado!</b></td>
				   </tr>';
	} else {	
            for($cont = 0; $cont < $qtd_resultado_por_pagina ; $cont++) {
                if($linha_registro < $qtd_registros){
                    $pers->bCarregaRegistroPorLinha($linha_registro);
                    $res = $pers->getDbArrayDados();

                    $data = $res['ultimo_acesso'];
                    if($data!="0000-00-00 00:00:00"){
                        $data = explode("-", $res['ultimo_acesso']);
                        $dia_hora = explode(" ",$data[2]);
                        $data_acesso = $dia_hora[1].' '.$dia_hora[0].'/'.$data[1].'/'.$data[0];
                    } else
                        $data_acesso = "Usu&aacute;rio ainda n&atilde;o logou";
					
					if($cont % 2 == 0){
							$cor_linha_tabela = "tableColor1";
						}else{
							$cor_linha_tabela = "tableColor2";
						}
					
                    $tabela .= '
                      <tr class="'.$cor_linha_tabela.'">
                        <td onclick="editar_usuario('.$res['id_pessoa'].')" class="numero">'.$res['id_pessoa'].'</td>
                        <td onclick="editar_usuario('.$res['id_pessoa'].')" class="usuario" >'.utf8_encode($res['nome']).'</td>
                        <td onclick="editar_usuario('.$res['id_pessoa'].')" class="tipoAcesso" >'.$res['tipo_acesso'].'</td>
                        <td onclick="editar_usuario('.$res['id_pessoa'].')" class="ultimoAcesso" >'.$data_acesso.'</td>
                        <td class="opcoesUsuarios">
							<span style="display:block; margin:auto; width:58px;">
								<a class="ir editar" href="#" onclick="editar_usuario('.$res['id_pessoa'].')">Editar Usuário</a>
								<a class="ir excluir" href="#" onclick="remover_usuario('.$res['id_pessoa'].');">Remover Usuário</a>
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
        <div id="dropshadow">
            <div id="breadcrumb">
                        <ul>
                            <li><span class="breadcrumbEsquerda"></span><a>configurações</a><span class="breadcrumbDireita"></span>
                                <ul>
                                    <li><span class="breadcrumbEsquerda bcrumbSelect"></span><h2 class="bcrumbAtivo bcrumbSelect">profissionais</h2><span class="breadcrumbDireita bcrumbSelect"></span></li>
                                </ul>
                            </li>
                        </ul>
            </div> <!--Fecha div breadcrumb-->
            
            <div id="container" class="clearfix">
                <p><button class="botaoPositivo floatRight" type="button" onclick="location.href='cadastro_usuario.php'"> Adicionar Profissional</button></p>
                <p id="filtros">Exibição</p>
                <div class="boxLista">
                    <form class="listarResultados" action="#" method="post" name="grid">
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
                
                <table title="Lista de Especialidades" summary="Lista completa de Especialidades">
                  <caption>Lista de Especialidades</caption>
                  <thead>
                      <tr>
                        <th class="numero">Número</th>
                        <th>Usuário</th>
                        <th class="tipoAcesso">Tipo</th>
                        <th class="ultimoAcesso">Último Acesso</th>
                        <th class="opcoesUsuarios">Opções</th>
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
            function remover_usuario(valor){
                paciente_corrente = <?php echo $_SESSION['USUARIO']['ID']; ?>;
                if(paciente_corrente == valor) {
                    alert('Você não pode excluir o seu próprio usuário.');
                }
                else {
                    if(confirm('Deseja realmente remover este usuário?')){
                        location.href="../controladores/usuarioGravar.php?acao=excluir&id=" + valor;
                    }
                }
            }
        
            function editar_usuario(valor){
                location.href = "cadastro_usuario.php?acao=editar&id=" + valor ;
            }
        
            function qtdpag(){
                if($('qnt_pag')!=null)
                    location.href = "usuarios.php?qtdpag="+document.grid.qnt_pag.value;
            }
        </script>                 
    </body>
</html>