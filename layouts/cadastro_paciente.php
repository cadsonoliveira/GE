<?php
	//Antiga página pacientes_cadastro.php
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
			
			$cpf = substr($paciente->getCpf(), 0, 11);
			$cpf_comp = substr($paciente->getCpf(),12, 13);
			
			$cep = substr($paciente->getEndereco()->getCep(), 0, 5);
			$cep_comp = substr($paciente->getEndereco()->getCep(), 6, 8);			
			
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
		$cpf = "";
		$cpf_comp = "";
		$cep = "";
		$cep_comp = "";
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

	$combo = new Combo();
	//$combo->setClassSelect('iw278');
	//$combo->setClassOption('', '');

	$sSqlPlanoSaude = "SELECT id_plano_saude, codigo, nome FROM planosaude";
 	$sSqlDentistaEncaminhador = "SELECT id_dentista_encaminhador, nome FROM dentista_encaminhador";
	}
?>

<?php include_once("include/header.php") ?>
<?php include_once("include/topo.php") ?>
<?php include_once("include/menu.php") ?>

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
                <h3 class="tituloBox">Dados Pessoais</h3>
                <div class="formularioDividido">
                <fieldset class="dadosPaciente">
					<span id="fotoajax">
                        <img name="img_foto" alt="Retrato do Paciente" src="<?php echo $caminho_foto; ?>" style="width:63px; height:63px;" />
                        <!--<button  type="button" onclick="recarrega_foto('input_file');">Nova Foto</button>-->
                    </span>
					<span id="uploadButton" class="espacamentoEsquerda">Nova Foto<input id="inputFile" name="inputFile" size="1" type="file" onchange="if(this.value.toLowerCase().substr(-3,3) != 'jpg'){ alert('Tipo de arquivo n&atilde;o suportado!\nAceito somente arquivos de extens&atilde;o .jpg com formato m&aacute;ximo de 110x110 pixels.'); this.src = ''; }else{ recarrega_foto('input_file'); }" /></span>
					<button id="botaoCapturar" class="espacamentoEsquerda" type="button" onclick="captura_webcam()" >Capturar com Webcam</button>
					<?php if(isset($_GET['id'])) { ?><button id="botaoExcluiFoto" class="espacamentoEsquerda" type="button" onclick="exclui_foto()" >Excluir Foto</button> <?php } ?>
                    <p id="obsImagem" class="clearfix">Obs. Aceito somente arquivos de extens&atilde;o .jpg com formato m&aacute;ximo de 110x110 pixels.</p>  
					
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
						
						<p class="itensObrigatorios" style="margin:15px 0 0 160px;" class="exibicaoContinua" >*Campos em vermelho s&atilde;o obrigat&oacute;rios</p>
					</div>
					<div>
                        <label for="telefone_comercial">Telefone Comercial</label>
                        <input id="telefone_comercial" name="tel_com" type="text" maxlength="14" onkeypress="return Mascara('TEL',this,event);" value="<?php echo $paciente->getContato()->getTelefoneComercial(); ?>" class="tamanho" />
                   
                        <label for="telefone_residencial" class="exibicaoContinua">Telefone residencial</label>
                        <input id="telefone_residencial" name="tel_res" type="text" maxlength="14" onkeypress="return Mascara('TEL',this,event);" value="<?php echo $paciente->getContato()->getTelefoneFixo(); ?>" class="tamanho" />
                    </div>
                    
                    
                    <div>
                        <label for="data_nasc">Nascimento</label>
                        <input id="data_nasc" name="data_nasc" type="text" maxlength="10" onkeypress="return Mascara('DATA',this,event);" onblur="calcular_idade(this.value, 'div_idade');" value="<?php echo $data_nasc; ?>" class="tamanho" />
                        <span id="div_idade"><?php echo $idade; ?></span>
						
						 <p style="margin-left:43px;" class="exibicaoContinua">Data de Cadastro <?php echo "&nbsp;&nbsp;".$data_cadastro; ?></p>
                        <input id="data_cadastro" name="data_cadastro" type="hidden" value="<?php echo $data_cadastro; ?>" />			
                    </div>
                   
           
                    <div>
                        <label>Sexo</label>
                        <input id="masculino" name="sexo" type="radio" value="M" checked="checked"<?php echo $mas_chk; ?> />
                        <label class="campoSexo" for="masculino">Masculino</label>
                        
                        <input id="feminino" name="sexo" type="radio" value="F" <?php echo $fem_chk; ?> />
                        <label class="campoSexo" for="feminino">Feminino</label>
                    </div>
                    
                   
                </fieldset>
                </div>
                <h3 class="tituloBox">Complemento</h3>
                <div class="formularioDividido">
                <fieldset class="dadosPaciente">

                     <div>
                        <label for="cpf">CPF</label>
                        <input id="cpf" name="cpf" type="text" maxlength="11" onkeypress="return Mascara('CPF',this,event)" value="<?php echo $cpf; ?>" class="tamanho" />
                        <input class="ultimosDigitos" name="cpf_comp" id="cpf_comp" type="text" maxlength="2" onkeypress="return Mascara('NUMERAL',this,event)" value="<?php echo $cpf_comp; ?>" />
                    </div>
                    
                    <div>
                        <label for="rg">RG</label>
                        <input id="rg" name="rg" type="text" maxlength="13" onkeypress="return Mascara('STRING',this,event)" value="<?php echo $paciente->getRg(); ?>" style="width:215px;" /> 
                    </div>
                    
                    <div id="div_plano_saude">
                        <label for="plano">Plano de Saúde</label>
                            <?php
                               // $combo->bAddItemCombo("-1","Nome do Plano de Saúde"); // Chamada do metodo para inclusao de novos itens
                                echo $combo->sGetHTML( $sSqlPlanoSaude , 'plano', 'id_plano_saude', 'nome', $paciente->getIdPlanoSaude(), 'style="width:222px;"' );	
                            ?>
                        <!--<button type="button" onclick="cadastro_plano();">Cadastrar novo Plano de Saúde</button>-->
                    </div>
                    
					<div>
                        <label for="numero_carteira">Número da carteira</label>
                        <input id="numero_carteira" name="num_carteira_convenio" type="text" onkeypress="return Mascara('NUMERAL',this,event);" maxlength="20" value="<?php echo $paciente->getNumCarteira(); ?>" class="tamanho" />
                    </div>
                    
                    <div>
                        <label for="validade_carteira">Validade da carteira</label>
                        <input id="validade_carteira" name="validade_carteira_convenio" type="text" onkeypress="return Mascara('DATA',this,event);"
                           maxlength="10" value="<?php echo $paciente->getValidadeCarteira();?>" class="tamanho" />
                    </div>
                  
                </fieldset>
                </div>
                <h3 class="tituloBox">Endereço</h3>
                <div class="formularioDividido">
                <fieldset class="dadosPaciente">
                	<div>
                        <label for="logradouro">Logradouro</label>
                        <input id="logradouro" name="logrdo" type="text" onchange="Mascara('STRING',this,event);" value="<?php echo $paciente->getEndereco()->getLogradouro(); ?>" style="width:417px;" />
                    </div>
                    
                    <div>
                        <label for="numero">Número</label>
                        <input id="numero" name="numro" type="text" maxlength="10" value="<?php echo $paciente->getEndereco()->getNumero(); ?>" class="tamanho" />
                        <label class="exibicaoContinua" for="complemento">Complemento</label>
                        <input id="complemento" name="compto" type="text" onchange="Mascara('STRING',this,event);" value="<?php echo $paciente->getEndereco()->getComplemento(); ?>" class="tamanho" />
                    </div>
                    
                    <div>
                        <label for="bairro">Bairro</label>
                        <input id="bairro" name="bairro" type="text" onchange="Mascara('STRING',this,event);" value="<?php echo $paciente->getEndereco()->getBairro(); ?>" style="width:417px;" />
                    </div>
                    
                    <div>
                        <label for="cidade">Cidade</label>
                        <input id="cidade" name="cidade" type="text" onchange="Mascara('STRING',this,event);" value="<?php echo $paciente->getEndereco()->getCidade(); ?>" style="width:417px;" />
                    </div>
                    <div>
                        <label for="estado">Estado</label>
						<?php
                            $combo->bAddEstadosBrasileiros();
                            if($paciente->getId()==0)
                                    $estado = 'MG';
                            else
                                    $estado = $paciente->getEndereco()->getSiglaEstado();
                            echo $combo->sGetHTML('','estado','uf','estado', $estado );
                        ?>
                    </div>
                    <div>
                        <label for="cep">CEP</label>
                        <input id="cep" name="cep" type="text" maxlength="5" onkeyup="tabProxCampo(this, this.value, 'cep_comp')" value="<?php echo $cep; ?>" class="tamanho" />
                        <input class="ultimosDigitos" name="cep_comp" type="text" maxlength="3" value="<?php echo $cep_comp; ?>" />
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
                <input type="hidden" name="anamnese" id="anamnese" value="false" />                     
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
					if(valor == "dentista_encaminhador"){
						caminho = "../controladores/AJAX.pacientes_cadastro.php?acao=recarrega_combo&div_destino=div_dentista_encaminhador&combo=dentista_encaminhador";
					}
					xhSendPost2(caminho, document.cd_field);
				}
			
				function valida_campos(){
					if (valida_nome(document.cd_field.nome))
					if (valida_data(document.cd_field.data_nasc, null, true))
					if (valida_cpf(document.cd_field.cpf, document.cd_field.cpf_comp))
					if (valida_data(document.cd_field.validade_carteira_convenio, true))
					if (valida_tel(document.cd_field.tel_res))
					if (valida_tel(document.cd_field.tel_cel))
					if (valida_tel(document.cd_field.tel_com))
					if (valida_email(document.cd_field.mail))
					if (valida_numero(document.cd_field.numro))
					if (valida_cep(document.cd_field.cep))
					if (valida_cep(document.cd_field.cep_comp)){
						<?php
							if(!isset($_GET['id'])){
							echo "
								if(confirm('Deseja responder o questionário de anamnese agora?')){
									$('anamnese').set('value', 'sim');
								} else {
									$('anamnese').set('value', 'nao');
								}
								";
							}
						?>
						document.cd_field.submit();
					}
				}
			
				/* Captura Webcam Modal */
				var captura_webcam = function(){
					var md = new Modal(['modal']);
					md.setHeader('Captura via Webcam');
					md.addEvent('exit', function(){
						recarrega_foto('web_cam');
					});
					/* #WARNING
					
					Função a ser executada após Post */
					var fm = new Form('form_e',['','post'],'field','');
				  
					fm.attach(md.win);
					//Enquanto a imagem nao for salva no banco, a solução escolhida foi
					//usar um script quer gere o php.
					fm.injectBlock('<center><iframe name="webcam"  src="../layouts/webcam/webcam.php?id=<?php echo $id; ?>" frameBorder=0 height="232" width="469" scrolling=auto></iframe></center>');
					md.show();
				}
			
				function recarrega_foto(tipo){
					caminho = document.cd_field.img_foto.src;
					micoxUpload(document.cd_field,"../controladores/AJAX.pacientes_cadastro.php?acao=recarrega_foto&tipo="+tipo+"&div_destino=fotoajax&caminho_foto="+caminho,'fotoajax','Carregando...','Erro ao Carregar a Imagem');
					document.cd_field.removeAttribute("target");
					document.cd_field.setAttribute("action","<?php echo $action_form; ?>");
				}
				function exclui_foto(){
					if(confirm("Tem certeza que quer excluir a foto desse paciente?")){
						xhSendPost("../controladores/AJAX.pacientes_cadastro.php?acao=exclui_fotos&id=<?php if(isset($_GET['id'])){echo $_GET['id'];}?>&id_destino=fotoajax");
					}
				}
			</script>
            
    </body>
</html>
