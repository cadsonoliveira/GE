<?php
	session_start();

	if((!isset($_SESSION['USUARIO'])) && ($_SESSION['USUARIO']['VALIDA'] != true)){
		header("Location: ../layouts/login.php?tipo=2");
	} else {
		include_once("../classes/classPaciente.php");
		include_once("../classes/classPersistencia.php");
		include_once("../funcoes/common.php");
		include_once("../funcoes/common_pacientes.php");
		
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
						$inseri_funcao_selecionar = ' onclick="paciente_receituario('.$res['id_pessoa'].');" title="Selecionar paciente para gerar receituário"';
					};
		
					$tabela .= '
					<tr class="'.$cor_linha_tabela.'"'.$marca_linha_paciente_selecionado.$inseri_funcao_selecionar.'>
						<td class="numero">'.$res['id_pessoa'].'</td>
						<td class="pacientes">'.utf8_encode($res['nome']).'</td>
					</tr>';			
					$linha_registro++;
				}
			}
		}
	}
	
	$nome_pac = "";
	if(isset($_SESSION['PACIENTE']['ID'])){
		$paciente = new Paciente($_SESSION['PACIENTE']['ID']);
		$nome_pac = utf8_encode(ucwords(strtolower(utf8_decode($paciente->getNome()))));
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
                <form id="cd_field" action="receituario.php" method="POST" name="cd_field">
                    <input name="id_paciente" type="hidden" value=""/>
                </form>
                
                <?php include_once("include/filtro_busca_pacientes.php") ?>
                
                <table title="Lista de Pacientes" summary="Lista completa de pacientes" class="habilitaHoverTabela">
                    <caption class="hidden">Lista de Pacientes</caption>
                    <thead>
                        <tr>
                            <th class="numero">Número</th>
                            <th class="pacientes" class="alinhamentoPacientes">Paciente</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $tabela; ?>
                    </tbody>
                </table>
            
            <?php include_once("include/paginacao_tabela.php") ?>
                
		<?php include_once("include/footer.php") ?>
        
        <script type="text/javascript">
			function paciente_receituario(valor){
				<?php include_once("include/funcao_deletar_fotos_ultima_consulta.php") ?>
				
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
		
            function qtdpag(letra){
                if($('resultados_por_pagina').value !=null)
                    location.href = "seleciona_paciente_receituario.php?qtdpag="+$('resultados_por_pagina').value+"&pag="+"<?php $pag_atual ?>"+"&letra="+letra<?php if($chave != ""){echo "+'&chave='+'".$chave."'";} ?>;
            }
        </script>
    </body>
</html>