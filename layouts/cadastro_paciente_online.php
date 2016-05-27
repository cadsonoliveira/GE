<?php
	//Antiga página cadastro_paciente.php
    session_start();

    if((!isset($_SESSION['USUARIO'])) && ($_SESSION['USUARIO']['VALIDA'] != true)){
       header("Location: ../layouts/login.php?tipo=2");
    } else {

	include_once("../classes/classPaciente.php");
	include_once("../classes/classCombo.php");
	
	/* CREIO QUE O SUBMIT VAI PRA ESTA PÁGINA AQUI com a sessão do usuário*/
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

	$combo = new Combo();

	$sSqlPlanoSaude = "SELECT id_plano_saude, codigo, nome FROM planosaude";
	}
?>

<?php include_once("include/header.php") ?>
<?php include_once("include/topo.php") ?>

    <div id="conteudo">
        <div id="dropshadow">
        <div id="breadcrumb">
            <ul>
                <li><span class="breadcrumbEsquerda"></span><a href="pacientes.php" title="lista de pacientes">pacientes</a><span class="breadcrumbDireita"></span>
                    <ul>
                        <li><span class="breadcrumbEsquerda bcrumbSelect"></span><h2 class="bcrumbAtivo bcrumbSelect">cadastro paciente</h2><span class="breadcrumbDireita bcrumbSelect"></span></li>
                    </ul>
                </li>
            </ul>
        </div> <!--Fecha div breadcrumb-->            
            
        <div id="container" class="clearfix">
            <form action="<?php echo $action_form; ?>" method="post" name="cd_field"  enctype="multipart/form-data" id="UploadForm">
                <h3 class="tituloBox">Cadastrar Paciente Online</h3>
                <div class="formularioDividido">
                <fieldset class="dadosPaciente">
					
					<div>
                        <label for="nome" class="itensObrigatorios">Nome Completo</label>
                        <input id="nome" name="nome" type="text" value="<?php echo $paciente->getNome(); ?>" onchange="Mascara('STRING',this,event);" style="width:480px;" />
                    </div>
					
					<div>
                        <label for="email" class="itensObrigatorios"> E-mail</label>
                        <input id="email" name="mail" type="text" maxlength="100" value="<?php echo $paciente->getContato()->getEmail();?>" style="width:480px;" />
                    </div>
					
                    <div>
			<label for="telefone_celular" class="itensObrigatorios">Celular</label>
                        <input id="celular" name="tel_cel" type="text" maxlength="16" onkeypress="return Mascara('CEL',this,event);" value="<?php echo $paciente->getContato()->getTelefoneCelular(); ?>" class="tamanho"/>
						
						
                        <label class="exibicaoContinua">Sexo</label>
						
                        <input id="masculino" name="sexo" type="radio" value="M" checked="" <?php echo $mas_chk; ?> />
                        <label class="campoSexo" for="masculino">Masculino</label>
                        
                        <input id="feminino" name="sexo" type="radio" value="F" <?php echo $fem_chk; ?> />
                        <label class="campoSexo" for="feminino">Feminino</label>
							
						
			<p class="itensObrigatorios" style="margin:15px 0 0 160px;" class="exibicaoContinua" >*Campos em vermelho s&atilde;o obrigat&oacute;rios</p>
                    </div>
					
					
					<!-- Neste ponto, são preenchidos valores default vazios para o formulário -->
						
					<input id="cpf_hidden" name="cpf" type="hidden"   value="" class="tamanho" />
					<input id="rg_hidden" name="rg" type="hidden"   value="" class="tamanho" />
					<input id="dentista_encaminhador_hidden" name="dentista_encaminhador" type="hidden"  value="" class="tamanho" />
					<input id="tel_res_hidden" name=" tel_res" type="hidden"  value="" class="tamanho" />
					<input id="tel_com _hidden" name="tel_com" type="hidden"  value="" class="tamanho" />
					<input id="logrdo_hidden" name="logrdo" type="hidden"  value="" class="tamanho" />
					<input id="numro_hidden" name="numro" type="hidden"  value="" class="tamanho" />
					<input id="compto_hidden" name="compto" type="hidden"  value="" class="tamanho" />
					<input id="cidade_hidden" name="cidade" type="hidden"  value="" class="tamanho" />
					<input id="bairro_hidden" name="bairro" type="hidden"  value="" class="tamanho" />
					<input id="estado_hidden" name="estado" type="hidden"  value="" class="tamanho" />
					<input id="cep_hidden" name="cep" type="hidden"  value="" class="tamanho" />                    
                    
                    <div>
                        <label for="data_nasc">Nascimento</label>
                        <input id="data_nasc" name="data_nasc" type="text" maxlength="10" onkeypress="return Mascara('DATA',this,event);" onblur="calcular_idade(this.value, 'div_idade');" value="<?php echo $data_nasc; ?>" class="tamanho" />
                        <span id="div_idade"><?php echo $idade; ?></span>
						
						 <p style="margin-left:43px;" class="exibicaoContinua">Data de Cadastro <?php echo "&nbsp;&nbsp;".$data_cadastro; ?></p>
                        <input id="data_cadastro" name="data_cadastro" type="hidden" value="<?php echo $data_cadastro; ?>" />			
                    </div>
                            
                   
         
                    <div id="div_plano_saude">
                        <label for="plano">Plano de Saúde</label>
                            <?php
                               // $combo->bAddItemCombo("-1","Nome do Plano de Saúde"); // Chamada do metodo para inclusao de novos itens
                                echo $combo->sGetHTML( $sSqlPlanoSaude , 'plano', 'id_plano_saude', 'nome', $paciente->getIdPlanoSaude(), 'style="width:222px;"' );	
                            ?>
                    </div>
                    
					<div>
                        <label for="numero_carteira">Número da carteira</label>
                        <input id="numero_carteira" name="num_carteira_convenio" type="text" onkeypress="return Mascara('NUMERAL',this,event);" maxlength="20" value="<?php echo $paciente->getNumCarteira(); ?>" class="tamanho" />

                        <label for="validade_carteira"  class="exibicaoContinua">Validade da carteira</label>
                        <input id="validade_carteira" name="validade_carteira_convenio" type="text" onkeypress="return Mascara('DATA',this,event);"
                           maxlength="10" value="<?php echo $paciente->getValidadeCarteira();?>" class="tamanho" />
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
				
                    <button class="botaoPositivo" type="button" onclick="valida_campos();">Salvar Paciente</button>
					
                </p>
                                  
            </form>
            
            <?php include_once("include/footer.php") ?>
            
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
			
				function valida_campos(){
					if (valida_nome(document.cd_field.nome))
					if (valida_data(document.cd_field.data_nasc, null, true))
					if (valida_data(document.cd_field.validade_carteira_convenio, true))
					if (valida_tel(document.cd_field.tel_cel))
					if (valida_email(document.cd_field.mail)){
						document.cd_field.submit();
					}
				}
						
				
			</script>
            
    </body>
</html>
