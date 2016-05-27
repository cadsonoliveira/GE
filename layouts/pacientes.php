<?php
	//Antiga página pacientes_geral.php
    session_start();

    if((!isset($_SESSION['USUARIO'])) && ($_SESSION['USUARIO']['VALIDA'] != true)){
		header("Location: ../layouts/login.php?tipo=2");
    } else {
		
		include_once("../classes/classPersistencia.php");
		include_once("../funcoes/common.php");
		include_once("../funcoes/common_pacientes.php"); 
		include_once("../classes/classPaciente.php");
		
		if(isset($_SESSION['PACIENTE']['ID'])){
			$paciente = new Paciente($_SESSION['PACIENTE']['ID']);
		}
		
		/**** Montagem tabela *****/
		$linha_registro = $pag_atual * $qtd_resultado_por_pagina;
		$tabela = "";
	
		if(!$pers->getDbNumRows() > 0) {
			$tabela = '<tr>
							<td colspan="4" style="text-align:center;">Nenhum registro encontrado!</td>
					   </tr>';
		} else {
			for($cont = 0; $cont < $qtd_resultado_por_pagina ; $cont++){
				if($linha_registro < $qtd_registros){
					$pers->bCarregaRegistroPorLinha($linha_registro);
					$res = $pers->getDbArrayDados();
					
					$sql_ultima_consulta = "SELECT c.data
											FROM tratamento t, er_consulta_tratamento erct, consulta c
											WHERE t.id_pessoa = ".$res['id_pessoa']."
												AND t.id_tratamento = erct.id_tratamento
												AND erct.id_consulta = c.id_consulta
											ORDER BY c.data DESC
											LIMIT 0, 1";
					$pers_cons = new Persistencia();
					$pers_cons->bExecute($sql_ultima_consulta);
					if($pers_cons->getDbNumRows() > 0){
						$pers_cons->bDados();
						$vet_consulta = $pers_cons->getDbArrayDados();
						$ultima_consulta = decodeDate($vet_consulta['data']);
					} else {
						$ultima_consulta = "Nunca consultou";
					}

					if($cont % 2 == 0){
						$cor_linha_tabela = "tableColor1";
					}else{
						$cor_linha_tabela = "tableColor2";
					}
					
					if((isset($_SESSION['PACIENTE']['ID'])) && ($res['id_pessoa'] == $paciente->getId())){
						$marca_linha_paciente_selecionado = 'style="background:#FC9505; color:#FFFFFF;" title="Paciente selecionado"';
						$inseri_funcao_selecionar = "";
					}else{
						$marca_linha_paciente_selecionado = "";
						$inseri_funcao_selecionar = 'onclick="paciente_selecionar('.$res['id_pessoa'].');" title="Selecionar paciente"';
					};
					
					$tabela .= '
						<tr class="'.$cor_linha_tabela.'"'.$marca_linha_paciente_selecionado.'>
							<td '.$inseri_funcao_selecionar.' class="numero">'.$res['id_pessoa'].'</td>
							<td '.$inseri_funcao_selecionar.' class="pacientes">'.utf8_encode($res['nome']).'</td>
							<td '.$inseri_funcao_selecionar.' class="ultimaConsulta">'.$ultima_consulta.'</td>
							<td class="opcoes">
								<span style="display:block; margin:auto; width:158px;">
									<a class="ir editar" onclick="paciente_editar('.$res['id_pessoa'].');" title="Editar dados do paciente">Editar registro</a>
									<a class="ir agendar" onclick="paciente_agenda('.$res['id_pessoa'].');" title="Agendar horário">Agendar</a>
									<a class="ir tratamentos" onclick="paciente_tratamento('.$res['id_pessoa'].');" title="Todos os tratamentos do paciente">Tratamentos</a>
									<a class="ir anammesia" onclick="paciente_anamnese('.$res['id_pessoa'].');" title="Questionário anamnese">Anamnese</a>
									<a class="ir documentos" onclick="paciente_documento('.$res['id_pessoa'].');" title="Documentos do paciente">Documentos</a>
									<a class="ir excluir" onclick="remover_paciente('.$res['id_pessoa'].');" title="Excluir paciente">Excluir</a>
								</span>
							</td>
						</tr>';

					$linha_registro++;
				}
			}
	}
       
?>

<?php include_once("include/header.php") ?>
<?php include_once("include/topo.php") ?>
<?php include_once("include/menu.php") ?>
            
    <div id="conteudo">
        <?php include_once("include/dados_busca_pacientes.php") ?>
        <div id="dropshadow">
            <div id="breadcrumb">
                <ul>
                    <li><span class="breadcrumbEsquerda bcrumbSelect"></span><h2 class="bcrumbAtivo bcrumbSelect">pacientes</h2><span class="breadcrumbDireita bcrumbSelect"></span></li>
                </ul>
            </div> <!--Fecha div breadcrumb-->            
            
            
            <div id="container" class="clearfix">
                <p id="totalRegistros">Total de registros: <?php echo $qtd_registros ?></p>
                <form id="cd_field" action="pacientes.php" method="POST" name="cd_field">
                    <input name="id_paciente" type="hidden" value=""/>
                </form>
				<?php include_once("include/filtro_busca_pacientes.php") ?>
                
                <table title="Lista de Pacientes" summary="Lista completa de pacientes" rules="groups" class="habilitaHoverTabela">
                    <caption class="hidden">Lista de Pacientes</caption>
                    <thead>
                        <tr>
                            <th class="numero">Número</th>
                            <th class="pacientes" style="text-align:center; padding-left:0;">Paciente</th>
                            <th class="ultimaConsulta">Última Consulta</th>
                            <th class="opcoes">Opções</th>
                        </tr>
                    </thead>
                    <tbody>
                    	<?php echo $tabela; ?>
                    </tbody>
                </table>
                
                <?php include_once("include/paginacao_tabela.php") ?>
                
			<?php include_once("include/footer.php") ?>

			<?php
                if((isset($_GET['tipo'])) && ($_GET['tipo']==1)){
                    echo '<script>alert("Não é possível remover usuários que possui documentos no sistema.");</script>';
                    }
                }
            ?>
            
			<script type="text/javascript">
				function paciente_selecionar(valor){
					<?php include("include/funcao_deletar_fotos_ultima_consulta.php") ?>
					var pedido_request = new Request({
					'url':'../controladores/AJAX.configuracao.php?div_id=config&id_paciente='+ valor + '&apagarImagens=' + apagar_imagens,
					'method':'post',
					'noCache':false,
					onComplete:function(a){
							document.cd_field.id_paciente.value = valor;
							document.cd_field.submit();
						}
					});
					pedido_request.send('');
				}
				
				//Função envia página edição do paciente
				function paciente_editar(valor){
					<?php include("include/funcao_deletar_fotos_ultima_consulta.php") ?>
					var pedido_request = new Request({
					'url':'../controladores/AJAX.configuracao.php?div_id=config&id_paciente='+ valor + '&apagarImagens=' + apagar_imagens,
					'method':'post',
					'noCache':false,
					onComplete:function(a){
							location.href ="cadastro_paciente.php?acao=editar&id=" + valor;
						}
					});
					pedido_request.send('');					
				}	
				
				//Função envia página edição do paciente
				function paciente_agenda(valor){
					<?php include("include/funcao_deletar_fotos_ultima_consulta.php") ?>
					var pedido_request = new Request({
					'url':'../controladores/AJAX.configuracao.php?div_id=config&id_paciente='+ valor + '&apagarImagens=' + apagar_imagens,
					'method':'post',
					'noCache':false,
					onComplete:function(a){
							location.href = "agenda.php";
						}
					});
					pedido_request.send('');
				}			
				
				function paciente_tratamento(valor){
					<?php include("include/funcao_deletar_fotos_ultima_consulta.php") ?>
					var pedido_request = new Request({
					'url':'../controladores/AJAX.configuracao.php?div_id=config&id_paciente='+ valor + '&apagarImagens=' + apagar_imagens,
					'method':'post',
					'noCache':false,
					onComplete:function(a){
							location.href ="tratamentos.php";
						}
					});
					pedido_request.send('');
				}	
				
				//Função envia página questionário anamnese do paciente
				function paciente_anamnese(valor){
					<?php include("include/funcao_deletar_fotos_ultima_consulta.php") ?>
					var pedido_request = new Request({
					'url':'../controladores/AJAX.configuracao.php?div_id=config&id_paciente='+ valor + '&apagarImagens=' + apagar_imagens,
					'method':'post',
					'noCache':false,
					onComplete:function(a){
							location.href ="questionario_anamnese.php?acao=editar&id=" + valor;
						}
					});
					pedido_request.send('');
				}
				
				//Função envia página documentos do paciente
				function paciente_documento(valor){
					<?php include("include/funcao_deletar_fotos_ultima_consulta.php") ?>
					var pedido_request = new Request({
					'url':'../controladores/AJAX.configuracao.php?div_id=config&id_paciente='+ valor + '&apagarImagens=' + apagar_imagens,
					'method':'post',
					'noCache':false,
					onComplete:function(a){
							location.href ="documentos_paciente.php";
						}
					});
					pedido_request.send('');
				}	
				
				//Função para exclusão do paciente
				function remover_paciente(valor){
					if(confirm('Deseja realmente remover este paciente?')){
						location.href="../controladores/pacientes.php?acao=excluir&id=" + valor;
					}
				}
			
                function qtdpag(letra){
                    if($('resultados_por_pagina').value !=null)
                        location.href = "pacientes.php?qtdpag="+$('resultados_por_pagina').value+"&pag=1&letra="+letra<?php if($chave != ""){echo "+'&chave='+'".$chave."'";} ?>;
                }
            </script>
    </body>
</html>