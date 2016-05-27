<?php
	//Antiga página tratamentos_selecao.php
    session_start();

    if((!isset($_SESSION['USUARIO'])) && ($_SESSION['USUARIO']['VALIDA'] != true)){
        header("Location: ../layouts/login.php?tipo=2");
    } else {
		include_once("../classes/classPersistencia.php");
		include_once("../classes/classTratamento.php");
		include_once("../classes/classMatchCode.php");
		include_once("../classes/classPaciente.php");
		include_once("../classes/classCombo.php");
		include_once("../funcoes/common.php");
		include_once("../classes/classConfiguracao.php");
	
		$config = new Configuracao(1);
		
		if(isset($_SESSION['PACIENTE']['ID'])){
				$paciente = new Paciente($_SESSION['PACIENTE']['ID']);
		} else {
				header("Location: ../layouts/seleciona_paciente_para_tratamentos.php");
		}
		
		#Carregando a combobox como multi-linhas
		#Status do tratamento:
		#	0 - Em andamento
		#	1 - Concluido
		#	2 - Cancelado
		
		$sSqlTratamentos = "SELECT t.id_tratamento, t.id_match_code, t.dente, concat(t.dente, ' - ', m.descricao) AS dente_descricao
							FROM tratamento t, match_code m
							WHERE t.status=0 AND
								t.id_match_code = m.id_match_code AND
								t.id_pessoa=".$paciente->getId();
		
		$sSqlTratamentos = "SELECT t.id_tratamento, t.id_match_code, t.dente, concat(t.dente, ' - ', m.descricao) AS dente_descricao
							FROM tratamento t, match_code m
							WHERE t.id_match_code = m.id_match_code AND
								  t.id_match_code IN (SELECT id_match_code FROM match_code WHERE id_especialidade=".$config->getIdEspecialidade().") AND
								  t.id_pessoa=".$paciente->getId()." ORDER BY dente";
								
		$combo = new Combo();
		$combo->setSize("19");
		$combo->setClassOption('','');
		$combo_tratamento = $combo->sGetHTML($sSqlTratamentos,'id_tratamento','id_tratamento','dente_descricao','','ondblclick="valida_campos();"','style="width:100%;"');

		/* Carregando lista de dentes que possui algum tratamento */
		$sSqlTratamentos = "SELECT DISTINCT t.dente
								FROM tratamento t
								WHERE t.status=0 AND
										  t.id_pessoa=".$paciente->getId();
	
		$pers = new Persistencia();
		$pers->bExecute($sSqlTratamentos);
		$cont = 0;
		$dentes_em_tratamento = "";
		while($cont < $pers->getDbNumRows()){
			$pers->bCarregaRegistroPorLinha($cont);
			$vet = $pers->getDbArrayDados();
			$dentes_em_tratamento .= strtolower($vet['dente']);
			if($cont < $pers->getDbNumRows()-1){
				$dentes_em_tratamento .= ",";
			}
			$cont++;
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
    <?php include_once("include/dados_paciente.php") ?>
        <div id="dropshadow">
            <div id="breadcrumb">
                <ul>
                    <li><span class="breadcrumbEsquerda"></span><a href="pacientes.php" title="lista de pacientes">pacientes</a><span class="breadcrumbDireita"></span>
                        <ul>
                            <li><span class="breadcrumbEsquerda bcrumbSelect"></span><h2 class="bcrumbAtivo bcrumbSelect">tratamentos</h2><span class="breadcrumbDireita bcrumbSelect"></span></li>
                        </ul>
                    </li>
                </ul>
            </div> <!--Fecha div breadcrumb-->            

            <div id="container" class="clearfix">
                <form action="tratamentos_cadastro.php" id="cd_field2" name="cd_field2" method="post">
                    <input name="id_paciente_aux" id="id_paciente_aux" type="hidden" value="<?php echo $paciente->getId(); ?>" />
                </form>
            
                <form action="atualizacao_tratamento.php" id="cd_field" name="cd_field" method="post">
                    <input name="id_paciente" id="id_paciente" type="hidden" value="<?php echo $paciente->getId(); ?>" />
                    <input name="status_aux" id="status_aux" type="hidden" value="-1" />
                    
                    <h3 class="tituloBox">Filtros</h3>
                    
                    <div id="boxfiltroTratamentos" class="formularioDividido">
                        <p id="ativarFiltros">
                        	<a id="AtivarfiltrarTratamentos" onclick="ativaFiltros();" >Ativar</a>
                        </p>
                        <p style="margin-bottom:20px;">
                            <label id="resultadoTratamento" for="status_field"  class="alinhamentoDireita">Resultado</label>
                            <select id="status_field" onchange="update_campo(this.value);" >
                                <option value="-1">TODOS</option>
                                <option value="2">PENDENTE</option>
                                <option value="1">SUCESSO</option>
                                <option value="3">CANCELADO</option>
                            </select>
                        </p>
                        <p id="filtrarDente"  class="alinhamentoDireita">Filtrar por dente</p>
                        <?php include_once("include/arcada_dentaria.php") ?>
                            <span style="display:table; margin: 20px 0 0 768px;">
                                <button type="button" onclick="teeth_multi_mark('mark', true)">Marcar todos</button>
                                <button type="button" onclick="teeth_multi_mark('unmark', true)" style="margin-left:5px;">Desmarcar todos</button>
                            </span>
                        <input name="dentes" id="dentes" type="hidden" value="" />
                    </div><!--fecha formularioDividido-->
                    
                    <h3 class="tituloBox">Tratamentos</h3>
                    
                    <div class="formularioDividido" id="div_selecao">
						<?php echo $combo_tratamento; ?>
                        <input name="dentes_em_tratamento" id="dentes_em_tratamento" type="hidden" value="<?php echo $dentes_em_tratamento; ?>" />
                    </div><!--fecha formularioDividido-->
                    
                    <p id="botoesFormulario">
                        <button id="botaoNegativo" type="button" onclick="location.href='seleciona_paciente_para_tratamentos.php'">Cancelar</button>
                        <button class="botaoPositivo" type="button" onclick="valida_campos();">Atualizar tratamento</button>
                    </p>
                </form>  
         <?php include_once("include/footer.php") ?>
        
        <script src="js/Fx.Slide.js" type="text/javascript"></script>
        <script type="text/javascript">
            /* Chama Teeth Marker One-by-One */
            window.addEvent('domready',function(){ teeth_multi_mark('runXHSend'); teeth_multi_mark('set','dentes_em_tratamento'); slide_blocks(); });
            
            function valida_campos(){
                if(document.cd_field.id_tratamento.value == ""){
                    alert("Selecione um tratamento");
                } else {
                    document.cd_field.submit();
                }
            }
			
			$('boxfiltroTratamentos').set('style', 'height:20px');
			
			function ativaFiltros(){
                                _status = $('status_field');
                                
				if($('AtivarfiltrarTratamentos').get('html') == "Ativar"){
					$('AtivarfiltrarTratamentos').set('html', 'Desativar');
					$('boxfiltroTratamentos').morph({'height':'360px'});
					$('status_field').set('disabled',false);
					update_campo(_status.get('value'));
				}else{
					$('AtivarfiltrarTratamentos').set('html', 'Ativar');
					$('boxfiltroTratamentos').morph({'height':'20px'});
					update_campo(-1);
				}
			}
            
            function update_campo(valor){
                   $('status_aux').set('value', valor);
                   teeth_multi_mark('update',true);
            }
            
            function adiciona_tratamento(){
                    document.cd_field2.submit();
            }
        </script>
    </body>
</html>