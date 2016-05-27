<?php
	//Antiga página relatorios_geral.php
    session_start();

    if((!isset($_SESSION['USUARIO'])) && ($_SESSION['USUARIO']['VALIDA'] != true)){
        header("Location: ../layouts/login.php?tipo=2");
    } else {
		include_once ("../classes/classPersistencia.php");
		include_once("../classes/classPessoa.php");
		include_once("../classes/classPaciente.php");
		include_once("../classes/classTratamento.php");
		include_once("../classes/classCombo.php");
		include_once("../classes/classMatchCode.php");  
		include_once("../classes/classDentistaEncaminhador.php");  
	
		/************************************************************************************
		 *CARREGA OS DADOS DO TRATAMENTO
		/************************************************************************************/
		$id_paciente = 0;
		if(isset($_SESSION['PACIENTE']['ID'])){
			$id_paciente = $_SESSION['PACIENTE']['ID'];
		}
	
		$id_tratamento = -1;
		$status = '';
		$data_inic = '';
		$data_term = '';
		$dente = '';
		$descricao = '';
		$sub_tipo = '';
		$value = '- - MATCH CODE - -';
		$caminho_foto = 'img/no_pic.gif';
	
		$paciente = new Paciente($id_paciente);
	
		if($paciente->getCaminhoFoto()!="")
			$caminho_foto ="../documentos/pacientes/".$id_paciente."/foto/".$paciente->getCaminhoFoto();
		else
			$caminho_foto = 'img/no_pic.gif';
	
		$nome = $paciente->getNome();
	
		if(isset($_GET['tratamento']) && $_GET['tratamento']!=""){
			$id_tratamento = $_GET['tratamento'];
	
			//$action_form = "../controladores/tratamentoGravar.php?tratamento=$id_tratamento&paciente=$id_paciente";
	
			$tratamento = new Tratamento($id_tratamento);
			$vet_data = explode("-", $tratamento->getDataInicio());
			$data_inic = $vet_data[2].'/'.$vet_data[1].'/'.$vet_data[0];
	
			if(($tratamento->getDataTermino() != "0000-00-00")&&($tratamento->getDataTermino() != NULL)){
				$vet_data = explode("-", $tratamento->getDataTermino());
				$data_term = $vet_data[2].'/'.$vet_data[1].'/'.$vet_data[0];
			} else {
				$data_term = "";
			}
	
			$status = $tratamento->getStatus();
			$dente = $tratamento->getDente();
			$id_match_code = $tratamento->getIdMatchCode();
	
			if($id_match_code != NULL){
				$value = $id_match_code;
			}
	
			$match_code = new MatchCode($id_match_code);
			$descricao = $match_code->getDescricao();
			$sub_tipo = $match_code->getNome();
	
		}
	
		/*Combo selecao de pacientes */
		$combo = new Combo();
		$combo->setClassOption('','');
	
		$combo_tratamento = "<h2 class='uid'>SELECIONE O PACIENTE PREVIAMENTE</h2>";
		if($id_paciente != 0){
			$sSql = "SELECT tratamento.id_tratamento, CONCAT(match_code.descricao, ' - ', tratamento.dente) AS descri  FROM tratamento INNER JOIN match_code ON (tratamento.id_match_code=match_code.id_match_code) WHERE id_pessoa=".$id_paciente." AND tratamento.status=0";
			$combo->bAddItemCombo("-1","SELECIONE O TRATAMENTO");
			$combo_tratamento = "&nbsp;".$combo->sGetHTML($sSql,'tratamento','id_tratamento', 'descri','-','onChange="atualiza(this.value,'.$id_paciente.')"','');
		}
	
		/*
		$sSqlPaciente = "SELECT paciente.id_pessoa, pessoa.nome FROM paciente, pessoa WHERE paciente.id_pessoa=pessoa.id_pessoa";
		$combo = new Combo();
		$combo->setClassOption('','');
		$combo->bAddItemCombo("-1","TODOS OS PACIENTES");
		$combo_paciente = $combo->sGetHTML($sSqlPaciente,'paciente','id_pessoa','nome','-','onchange="atualiza('.$id_tratamento.',this.value);" ');
		*/
		
		$dentistaEnc = new DentistaEncaminhador($paciente->getIdDentistaEncaminhador());
		//$dentistaEnc = new Pessoa($paciente->getIdDentistaEncaminhador());
		$enable_dentista = $dentistaEnc->getNome()==""?"disabled":"";
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
                    <li><span class="breadcrumbEsquerda"></span><a href="pacientes.php">pacientes</a><span class="breadcrumbDireita"></span>
                        <ul>
                            <li><span class="breadcrumbEsquerda bcrumbSelect"></span><h2 class="bcrumbAtivo bcrumbSelect">relatório</h2><span class="breadcrumbDireita bcrumbSelect"></span></li>
                        </ul>
                    </li>
                </ul>
            </div> <!--Fecha div breadcrumb-->
            
            <div id="container" class="clearfix">
                <form action="../controladores/relatorioGerar.php" method="post" id="cd_field" name="cd_field">
                    <input name="paciente" id="paciente" type="hidden" value="<?php echo $paciente->getId();?>" />
                    <h3 class="tituloBox">Tratamento</h3>
                    <div class="formularioDividido">
                    <fieldset id="campoTratamento">
                        <?php echo $combo_tratamento; ?>
                     <div id="div_ajax">
                        <p><span class="formularioBold">Dente:</span> <?php echo (isset($dente))?$dente:"-"; ?></p>
                        <p><span class="formularioBold">Matchcode:</span> <?php echo (isset($match_code))?$match_code->getId():"-"; ?></p>
                        <p><span class="formularioBold">Descrição:</span> <?php echo (isset($match_code))?$match_code->getDescricao():"-"; ?></p>
                        <p><span class="formularioBold">Data Início:</span> <?php echo (isset($data_inic))?$data_inic:"-"; ?></p>
                        <p id="dataTermino"><span class="formularioBold">Data Término:</span> <?php $data_term; ?></p>
                        <input name="dente_hd" id="dente_hd" type="hidden" value="<?php echo (isset($dente))?$dente:'-';?>" />
                        <input name="matchcode_hd" id="matchcode_hd" type="hidden" value="<?php echo (isset($match_code))?$match_code->getId():'-'; ?>"  />
                        <input name="dtinicio_hd" id="dtinicio_hd" type="hidden" value="<?php echo (isset($data_inic))?$data_inic:'-'; ?>" />
                        <input name="dtfim_hd" id="dtfim_hd" type="hidden" value="<?php echo (isset($data_term))?$data_term:'-'; ?> " />
                      </div>
                        <label for="comentarios_tatamento">Comentários sobre o tratamento</label>
                        <textarea id="comentarios_tatamento" name="obstratamento" cols="50" rows="4"></textarea>
                        <label for="observacoes_adcionais">Observações adcionais</label>
                        <textarea id="observacoes_adcionais" name="obsadicionais" cols="50" rows="4"></textarea>
                    </fieldset>
                    </div>
                    <h3 class="tituloBox">Destinatários de envio por email</h3>
                    <div class="formularioDividido">
                    <fieldset>
                        <div id="relatoriosDentista">
                        <input id="dentista_indicador" name="enviar_dentista" type="checkbox" value="dentista_indicador" <?php echo $enable_dentista; ?> />
                        <label for="dentista_indicador">Dentista Indicador (<?php echo $dentistaEnc->getNome() ?>)</label>
                        </div>
                       <!-- <div id="relatoriosSecretaria">
                        <input id="secretaria" name="enviar_secretaria" type="checkbox" value="secretaria" />
                        <label for="secretaria">Secretária</label>
                        </div>-->
                        <label for="outro_email">Outro e-mail</label>
                        <input id="outro_email" name="enviar_outro" type="text" />
                        
                    </fieldset>
                    </div> 
                    <p id="botoesFormulario">
                        <button id="botaoNegativo" type="button" onclick="location.href='pacientes.php'">Cancelar</button>
                        <button class="botaoPositivo" type="button" onclick="valida_campos();">Gerar Relatório</button>
                    </p>                 
                </form>
			<?php include_once("include/footer.php") ?>
                
        <script type="text/javascript">
            function atualizaCombo(id_pessoa){
                document.getElementById('div_ajax').innerHTML = 
                ' <p><span class="formularioBold">Dente:</span>-</p>'+
                ' <p><span class="formularioBold">Matchcode:</span>-</p>'+
                ' <p><span class="formularioBold">Descrição:</span>-</p>'+
                ' <p><span class="formularioBold">Data Início:</span>-</p>'+
                ' <p id="dataTermino"><span class="formularioBold">Data Término:</span></p>'
                
                //xhSendPost("AJAX.combo_tratamento.php?id_pessoa="+id_pessoa+"&div_id=div_tratamento");
                }
                
                function atualizaFoto(id_pessoa){
                    xhSendPost("../controladores/AJAX.relatorios_geral.php?paciente="+id_pessoa+"&div_id=foto_ajax&tipo=1");
                }
                
                function atualiza(id_tratamento,id_pessoa){
                    //atualizaFoto(id_pessoa);
                    
                    if(id_tratamento!=-1){
                        xhSendPost("../controladores/AJAX.relatorios_geral.php?paciente="+id_pessoa+"&tratamento="+id_tratamento+"&div_id=div_ajax&tipo=0");        
                }else
                    atualizaCombo(id_pessoa);
                }
                
                function valida_campos(){
                 if(document.cd_field.paciente.value!=-1&&<?php echo $paciente->getId(); ?>!=0)
                    if(document.cd_field.tratamento.value!=-1)
                       document.cd_field.submit();
                    else
                       alert('Informe o tratamento corretamente.');
                 else
                    alert('Informe o paciente corretamente.');
            }
        </script>            
    </body>
</html>
