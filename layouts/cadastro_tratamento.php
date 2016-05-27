<?php
	//Antiga página tratamentos_cadastro.php
    session_start();

    if((!isset($_SESSION['USUARIO'])) && ($_SESSION['USUARIO']['VALIDA'] != true)){
        header("Location: ../layouts/login.php?tipo=2");
    } else {
		include_once("../classes/classTratamento.php");
		include_once("../classes/classMatchCode.php");
		include_once("../classes/classPaciente.php");
		include_once("../classes/classCombo.php");
		include_once("../classes/classPersistencia.php");
		include_once("../classes/classConfiguracao.php");
			
		$action_form = "../controladores/tratamentoGravar.php?tipo=novo";
		$data_hoje = date("d/m/Y");
	/************************************************************************************
	 * INICIO - CARREGA OS DADOS DO PACIENTE
	/************************************************************************************/

                if(isset($_SESSION['PACIENTE']['ID'])){
                    $id_paciente = $_SESSION['PACIENTE']['ID'];
		} else {
			$id_paciente = 0;
		}
	
		$paciente = new Paciente($id_paciente);
	
	/************************************************************************************
	 * FIM - CARREGA OS DADOS DO PACIENTE
	/************************************************************************************/
	
		$config = new Configuracao(1);
		$combo = new Combo();
		$combo->setClassSelect('iw458');
		$combo->setClassOption('','');
		/*
		$combo->bAddItemCombo("0","EM TRATAMENTO");
		$combo->bAddItemCombo("1","CONCLUÍDO");
		$combo->bAddItemCombo("2","CANCELADO");
		$combo_status = $combo->sGetHTML('','status','','','0','');
		*/
		$combo->bClearCombo();
		$sSql = "SELECT DISTINCT tipo FROM match_code WHERE id_especialidade=".$config->getIdEspecialidade();
		$combo_tipo = $combo->sGetHTML($sSql,'tipo_tratamento','tipo','tipo','TRATAMENTO', 'onchange="carrega_sub_tipo(this.value, \'div_sub_tipo\')"');
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

<script type="text/javascript">
            /* Chama Teeth Marker One-by-One */
            window.addEvent('domready',function(){ teeth_obo_mark(); tabs_remove_picture(); slide_blocks(); });

                conj_match_codes = new Array();

            function carrega_sub_tipo(valor, div_id){
                        caminho = '../controladores/AJAX.tratamento_cadastro.php?div_id=' + div_id + '&tipo=' + valor;
                                        xhSendPost(caminho);
            }

            function voltar() {
                document.cd_field2.submit();
            }

            function envia_dados(){
                    if(valida_data(document.cd_field.data_inicio, null, true)){
                        if((conj_match_codes.length == 0) || (conj_match_codes == null)){
                            alert("Selecione algum sub-tipo para este tratamento / retratamento");
                            return false;
                        }
                        if(document.cd_field.tipo_tratamento.value == -1){
                            alert('Selecione o tipo do tratamento');
                        } else {
                            if(document.cd_field.dente.value == -1){
                                alert('Selecione o dente do tratamento');
                            } else {
                                document.cd_field.conj_match_codes.value = conj_match_codes;
                                document.cd_field.submit();
                            }
                        }
                    }
            }
            function arrayMatchCode(valor, status){
                if(status){
                    conj_match_codes.include(valor);
                } else {
                    conj_match_codes.erase(valor);
                }
            }

            /*function selectAll(value){
                var obj = document.getElementById('dente');
                if(value == 'mark')
                {
                    teeth_obo_mark('mark');
                    obj.value = 100;


                }
                if(value == 'unmark')
                {
                    teeth_obo_mark('unmark');
                    obj.value = -1;
                }
            }*/
        </script>

    <div id="conteudo">
    	<?php include_once("include/dados_paciente.php") ?>
        <div id="dropshadow">
            <div id="breadcrumb">
                <ul>
                    <li><span class="breadcrumbEsquerda"></span><a href="pacientes.php">pacientes</a><span class="breadcrumbDireita"></span>
                        <ul>
                            <li><span class="breadcrumbEsquerda"></span><a href="tratamentos.php">tratamentos</a><span class="breadcrumbDireita"></span>
                                <ul>
                                    <li><span class="breadcrumbEsquerda bcrumbSelect"></span><h2 class="bcrumbAtivo bcrumbSelect">cadastro de tratamento</h2><span class="breadcrumbDireita bcrumbSelect"></span></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div> <!--Fecha div breadcrumb-->
            
            <div id="container" class="clearfix">
                <form action="<?php echo $action_form; ?>" method="post" id="cd_field" name="cd_field">
                    <h3 class="tituloBox">Descrição Tratamento</h3>
                    <div id="cadastroTratamento" class="formularioDividido">
                    <fieldset>
                        <div>
                            <label class="alinhamentoDireita2" for="inicio_tratamento">Data de Início</label>
                            <input id="data_inicio" name="data_inicio" type="text" value="<?php echo $data_hoje;?>" />
                            <input type="hidden" name="status" value="0" />
                        </div>
                        <div>
                            <label class="alinhamentoDireita2" for="tipo_tratamento">Tipo</label>
							<?php echo $combo_tipo;	?>
                        </div>
                        <div id="div_sub_tipo">
                            <script>
                                carrega_sub_tipo(document.cd_field.tipo_tratamento.value, 'div_sub_tipo');
                            </script>
                        </div>
                        <div>
                            <label class="alinhamentoDireita2" for="observacoes_tratamento">Observações</label>
                            <textarea id="sessao" name="sessao" cols="40" rows="10"></textarea>
                        </div>
                    </fieldset>
                    </div>
                    <h3 class="tituloBox">Dente Para Tratamento</h3>
                    <div class="formularioDividido">
                        <fieldset>
                                                      
                            <?php include_once("include/arcada_dentaria.php") ?>

                            <span style="display:table; margin: 20px 0 0 608px;">
                                <button type="button" onclick="teeth_obo_mark('mark', true)">Marcar todos</button>
                                <button type="button" onclick="teeth_obo_mark('unmark', true)" style="margin-left:5px;">Desmarcar todos</button>
                            </span>

                        </fieldset>
                        <input name="dente" id="dente" type="hidden" value="-1" />
                         
                    </div>
                        <input type="hidden" name="conj_match_codes" value="" />
                        <input type="hidden" name="id_paciente" value="<?php echo $id_paciente; ?>" />
                    <p id="botoesFormulario">
                      <button id="botaoNegativo" type="button" onclick="location.href='seleciona_paciente_para_tratamentos.php'">Cancelar</button>
                      <button class="botaoPositivo" onclick="envia_dados();" type="button">Salvar Tratamento</button>
                    </p>             
                </form>
            
            <?php include_once("include/footer.php") ?>
            
		
    </body>
</html>
