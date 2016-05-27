<?php
	//Antiga página cadastro_paciente.php
    session_start();

    if((!isset($_SESSION['USUARIO'])) && ($_SESSION['USUARIO']['VALIDA'] != true)){
       header("Location: ../layouts/login.php?tipo=2");
    } else {

	include_once("../classes/classPaciente.php");
	include_once("../classes/classCombo.php");
	
	$action_form = "../controladores/pacientes.php";
	
	$id=0;	
	
	if(isset($_GET['id']) && isset($_GET['acao'])){		
		if($_GET['acao']=='editar'){
			$id = $_GET['id'];
			$paciente = new Paciente($id);
			$action_form .= "?id=".$id;
			if($paciente->getDataNasc() != ""){
				$vet_data = explode("-", $paciente->getDataNasc());
				$data_nasc = $vet_data[2].'/'.$vet_data[1].'/'.$vet_data[0];
				$idade = "Idade: ".$paciente->getIdade()." anos.";
			} else {
				$data_nasc = "";
				$idade = "";
			}
			
			$sexo = "";	
			
			if($paciente->getDataCadastro() != NULL) {
				$vet_data_cad = explode("-", $paciente->getDataCadastro());
				$data_cadastro =  $vet_data_cad[2].'/'.$vet_data_cad[1].'/'.$vet_data_cad[0];
			} else {
				$data_cadastro = "";
			}
			$foto = $paciente->getCaminhoFoto();
		}
	} else {
		$paciente = new Paciente();
		$data_nasc = "";
		$idade = "";
		$data_cadastro = date("d/m/Y");
	}

	if(!isset($foto) || ($foto == "")){
		$caminho_foto = "img/usuario_foto.png";	
	} else {
		$caminho_foto = "../documentos/pacientes/".$paciente->getId()."/foto/".$foto;
	}

	$mas_chk = '';
	$fem_chk = '';
		
	if($paciente->getSexo()=='F'){
		$mas_chk = '';
		$fem_chk = 'checked="checked"';
	}
	
	if($paciente->getSexo()=='M'){
		$mas_chk = 'checked="checked"';
		$fem_chk = '';
	}

	// Cria os combobox para seleção de especialidade e médico
	$combo_especialidade = new Combo();
	$combo_medico = new Combo();



	$sSqlMedicos = "SELECT u.id_pessoa, u.tipo_acesso, p.nome, eu.id_especialidade
					FROM `usuario` as u, pessoa as p, especialidade_usuario as eu
					WHERE 
						u.id_pessoa = p.id_pessoa and u.tipo_acesso = 'Medico'  
						and eu.id_pessoa = u.id_pessoa
						and eu.id_especialidade = ";
	$sSqlEspecialidades = "SELECT distinct u.id_especialidade, e.descricao FROM `especialidade_usuario` as u, `especialidade` AS e WHERE e.id_especialidade = u.id_especialidade" ;
	$sSqlPlanoSaude = "SELECT id_plano_saude, codigo, nome FROM planosaude";
	
	$combo = new Combo();
	$combo->setClassOption('','');
	
		
	$combo->bAddItemCombo("-1","SELECIONE A ESPECIALIDADE");
	$combo_especialidade = "&nbsp;".$combo->sGetHTML( $sSqlEspecialidades , 'especialidade', 'id_especialidade', 'descricao', 'onChange="atualiza_medico(this.value)" ', 'style="width:280px;"' );	
	
	}
?>

<?php include_once("include/header.php") ?>
<?php include_once("include/topo.php") ?>

    <div id="conteudo">
		<?php include_once("include/dados_paciente_online.php") ?>
        <div id="dropshadow">
        <div id="breadcrumb">
            <ul>
                <li><span class="breadcrumbEsquerda"></span><a href="pacientes.php" title="lista de pacientes">agendamento</a><span class="breadcrumbDireita"></span>
                    <ul>
                        <li><span class="breadcrumbEsquerda bcrumbSelect"></span><h2 class="bcrumbAtivo bcrumbSelect">online</h2><span class="breadcrumbDireita bcrumbSelect"></span></li>
                    </ul>
                </li>
            </ul>
        </div> <!--Fecha div breadcrumb-->            
            
					
			
					
					
			<div id="container" class="clearfix">
				<input name="paciente" id="paciente" type="hidden" value="<?php echo $paciente->getId();?>" />
				<h3 class="tituloBox">Agende você mesmo</h3>
				<div class="formularioDividido">
				<fieldset id="campoEspecialidade" for="id_especialidade">
				<label  class="itensObrigatorios" for="especialidade">Especialidades</label>
                                    <?php echo $combo_especialidade; ?>
				 <div id="div_ajax">
				 
				    <div id="div_medicos">
                        <label class="itensObrigatorios" for="medicos">Médicos</label>
                            <?php
                                $combo->bAddItemCombo("SELECIONE ESPECIALIDADE","SELECIONE ESPECIALIDADE");
                                //'','acesso','','',$tipoAcesso,'disabled', 'style="width:223px;"');
                                echo "&nbsp;". $combo->sGetHTML( '' , 'medicos', 'id_pessoa', 'nome', 'style="width:222px;"' );
							   // + document.cd_field.id_especialidade.value
							   $especialidade =   2;//$("combo_especialidade").val(); // 2;//$combo_especialidade.value; //$_POST['combo_especialidade']; 
							   $sqlMedicosFiltro = $sSqlMedicos.$especialidade;
							     // <label for="teste" class="itensObrigatorios">Teste</label>  
                               //echo "&nbsp;". $combo->sGetHTML( $sqlMedicosFiltro , 'medicos', 'id_pessoa', 'nome', 'style="width:222px;"' );
                            ?>
                    </div>
				 	
		</div>
	  
		</fieldset>
		</div>
				
         
				
                <p id="botoesFormulario">
                <?php
					if(!isset($_SESSION['letra'])){
						$_SESSION['letra'] = "";
					}
					
					if(!isset($_SESSION['qtd_resultado_por_pagina'])){
						$_SESSION['qtd_resultado_por_pagina'] = "10";
					}
					
					if(!isset($_SESSION['pag_atual'])){
						$_SESSION['pag_atual'] = "1";
					}
				
					echo '<button id="botaoNegativo" type="button" onclick="location.href=\'pacientes.php?qtdpag='.$_SESSION['qtd_resultado_por_pagina'].'&amp;pag='.$_SESSION['pag_atual'].'&amp;letra='.$_SESSION['letra'].'\'">Cancelar</button>';
		?>
				
                    <button class="botaoPositivo" type="button" onclick="continuar();">Continuar</button>
					
                </p>
                                  
            </form>
            
            <?php include_once("include/footer.php") ?>
			
			
			
			
			<script type="text/javascript">
				jQuery(document).ready(function(){
				 
					$("especialidade").change(function(){
						  var x = $("especialidade").val(); // recebe o valor da especialidade
						
						  alert(x);
					});
				 
				});
			</script>
			
			
			
			
			
			
			
            
			<script type="text/javascript" src="js/micoxUpload.js"></script>
			<script type="text/javascript">
				/* RECARREGANDO COMBOBOX
				 * Função utilizada quando o usuário cadastra: (plano de saude / convenio / dentista indicador)
				 * 		pela tela de cadastro de pacientes. Então é necessário recarregar o combobox para que o
				 *		item cadastrado esteja disponível para o usuário selecioná-lo.
				 * A atualização dos itens do combobox é feita através de uma conexão AJAX.
				 */
				 
				function recarrega_combo(valor){
					caminho = "";
					/*alert(valor);*/
					if(valor == "plano_saude"){		
						caminho = "../controladores/AJAX.pacientes_cadastro.php?acao=recarrega_combo&div_destino=div_plano_saude&combo=plano_saude";
					}
					xhSendPost2(caminho, document.cd_field);
				}
			
				function continuar(){
						document.cd_field.submit();
					}
				}
			
			    // Atualiza os médicos disponíveis após alterar a especialidade
				function atualizar_medicos(){
				  var especialidade = $('#especialidade').val();  //codigo da especialidade escolhida
				  //se encontrou a especialidade
				  if(especialidade){
					  
					 $combo_especialidade->bClearCombo();
					 
					 if (isset($combo_especialidade->array_itens)){
						array_splice($combo_especialidade->array_itens,0);
					  
					//var url = 'ajax_buscar_cidades.php?estado='+estado;  //caminho do arquivo php que irá buscar as cidades no BD
					//$.get(url, function(dataReturn) {
					//  $('#load_cidades').html(dataReturn);  //coloco na div o retorno da requisicao
					//});
					
				  }
				}
				
				 function atualiza_medico(id_especialidade){
					 $sSQL = $sSqlMedicos.id_especialidade;
					//$combo_medico->bLoadSqlCombo($sSqlEspecialidades,'id_especialidade', 'descricao',0);
					//$combo_resultado = new Combo();
					//$combo_resultado =
                                        //echo "&nbsp;".$combo_medico->sGetHTML($sSQL,'id_especialidade', 'descricao',0,'style="width:200px;"');
                                        //echo "&nbsp;". $combo->sGetHTML
					   alert("chamou atualiza medico");
							//$combo_medico->bClearCombo();
							//$combo_medico("0","INSUCESSO");
							//$combo_medico->bAddItemCombo("1","SUCESSO");
							//$combo_medico->bAddItemCombo("2","PENDENTE");
							//$combo_medico->bAddItemCombo("3","CANCELADO");
                       
                                }
				
			
				
			</script>
            
    </body>
</html>
