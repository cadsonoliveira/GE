<?php
	//Antiga página usuarios_cadastro.php
    session_start();

    if((!isset($_SESSION['USUARIO'])) && ($_SESSION['USUARIO']['VALIDA'] != true)){
        header("Location: ../layouts/login.php?tipo=2");
    } else {
		include_once("../classes/classCombo.php");
		include_once("../classes/classUsuario.php");
	
		$pers = new Persistencia(); //variavel para executar os sql's
		$action_form = "../controladores/usuarioGravar.php";
		$id=0;
	
		if(isset($_GET['id']) && isset($_GET['acao'])){		
				if($_GET['acao']=='editar'){
					$id = $_GET['id'];
					$usuario = new Usuario($id);
					$action_form .= "?acao=editar&id=".$id;
					$tipoAcesso = $usuario->getTipoAcesso();
					if($usuario->getDataNasc() != ""){
							$vet_data = explode("-", $usuario->getDataNasc());
							$data_nasc = $vet_data[2].'/'.$vet_data[1].'/'.$vet_data[0];
					} else {
							$data_nasc = "";
					}
	
					$cpf = substr($usuario->getCpf(), 0, 11);
					$cpf_comp = substr($usuario->getCpf(),12, 13);
	
					$cep = substr($usuario->getEndereco()->getCep(), 0, 5);
					$cep_comp = substr($usuario->getEndereco()->getCep(), 6, 8);
	
					$cro = $usuario->getCRO();
					$especialidades = $usuario->getEspecialidades();
					$camposSenhas='
					<p class="elementosFormulario2">
							<label for="login" class="itensObrigatorios">Login</label>
							<input name="login" id="login" type="text"  maxlength="15" value="'.$usuario->getLogin().'" style="width:215px;"/>
							<button type="button" onclick="altera_senha();">Alterar Senha</button>
					</p>
					';
				}
				
			} else {
				$usuario = new Usuario();
				$tipoAcesso = "";
				$data_nasc = "";
				$cpf = "";
				$cpf_comp = "";
				$cep = "";
				$cep_comp = "";
				$data_cadastro = date("d/m/Y");
				$cro = "";
				
				$especialidades = array();
				$camposSenhas='
					<p class="elementosFormulario2" style="color:#666666">
						<label for="login" class="itensObrigatorios">Login</label>
						<input name="login" id="login" type="text"  maxlength="15" value="'.$usuario->getLogin().'" style="width:215px; margin-right:5px;"/>* Comprimento máximo: 15 caracteres.
					</p>
					<div class="elementosFormulario2">
						<label for="senha" class="itensObrigatorios">Senha</label>
						<input id="senha" name="senha" maxlength="10" type="password" style="width:215px;" />
					</div>
					<div class="elementosFormulario2">
					   <label for="conf_senha" class="itensObrigatorios">Confirmar Senha</label>
					   <input id="conf_senha" name="confirma_senha" maxlength="10" type="password" onchange="valida_senha();" style="width:215px;" />
					</div>
				';
			}
			$mas_chk = '';
			$fem_chk = '';
				
			if($usuario->getSexo()=='F'){
				$mas_chk = '';
				$fem_chk = 'checked="checked"';
			}
			
			if($usuario->getSexo()=='M'){
				$mas_chk = 'checked="checked"';
				$fem_chk = '';
			}
	
			$combo = new Combo();
				$combo->bAddItemCombo("Médico","Médico");
				$combo->bAddItemCombo("Gestor","Gestor");
				$combo->bAddItemCombo("Secretaria","Secret&aacute;ria");
				$combo->bAddItemCombo("Dentista","Dentista");				
			if($tipoAcesso=="")
			   $combo_status = $combo->sGetHTML('','acesso','','',$tipoAcesso,'onchange="altera_display(this.value)"','style="width:223px;"');
			else
			   $combo_status = $combo->sGetHTML('','acesso','','',$tipoAcesso,'disabled', 'style="width:223px;"');
	}

?>

<?php include_once("include/header.php") ?>
<?php include_once("include/topo.php") ?>
<?php include_once("include/menu.php") ?>

    <div id="conteudo">
        <div id="dropshadow">
            <div id="breadcrumb">
                <ul>
                    <li><span class="breadcrumbEsquerda"></span><a>cadastro</a><span class="breadcrumbDireita"></span>
                        <ul>
                            <li><span class="breadcrumbEsquerda"></span><a href="usuarios.php">profissionais</a><span class="breadcrumbDireita"></span>
                                <ul>
                                    <li><span class="breadcrumbEsquerda bcrumbSelect"></span><h2 class="bcrumbAtivo bcrumbSelect">cadastro profissionais</h2><span class="breadcrumbDireita bcrumbSelect"></span></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div> <!--Fecha div breadcrumb-->
    
            <div id="container" class="clearfix">
                <form action="<?php echo $action_form; ?>" method="post" name="cd_field">
                    <fieldset class="dadosUsuario">
                        <h3 class="tituloBox">Dados Pessoais</h3>
                        <div class="formularioDividido">
                            <div class="elementosFormulario2">
                                <label for="nome_completo" class="itensObrigatorios">Nome Completo</label>
                                <input id="nome_completo" name="nome" type="text" onchange="Mascara('STRING',this,event);" value="<?php echo $usuario->getNome(); ?>" style="width:480px" />
                                <input name="id_usuario" type="hidden" value="<?php echo $id; ?>" />
                            </div>
                            
                            <div class="elementosFormulario2">
                                <label for="data_nasc">Nascimento</label>
                                <input id="data_nasc" name="data_nasc" type="text" maxlength="10" onkeypress="return Mascara('DATA',this,event);" value="<?php echo $data_nasc; ?>" />
                            </div>
                            
                            <div class="elementosFormulario2">
                                <label>Sexo</label>
                                <input id="masculino" name="sexo" type="radio" value="M" checked="checked" <?php echo $mas_chk; ?> />
                                <label for="masculino" class="campoSexo">Masculino</label>
                                <input id="feminino" name="sexo" type="radio" value="F" <?php echo $fem_chk; ?> />
                                <label for="feminino" class="campoSexo">Feminino</label>
                            </div>
                            
                            <div class="elementosFormulario2">
                                <label for="cpf">CPF</label>
                                <input id="cpf" name="cpf" type="text"  maxlength="11" onkeypress="Mascara('CPF',this,event)" onKeyup="tabProxCampo(this, this.value, 'cpf_comp')" value="<?php echo $cpf; ?>" />
                                <input name="cpf_comp" type="text" maxlength="2" value="<?php echo $cpf_comp; ?>" class="ultimosDigitos" />
                            </div>
                            
                            <div class="elementosFormulario2">
                                <label for="rg">RG</label>
                                <input id="rg" name="rg" type="text" maxlength="13" onkeypress="Mascara('RG',this,event);" value="<?php echo $usuario->getRg(); ?>" style="width:215px;" /> 
                            </div>
                            <p class="itensObrigatorios" style="margin:15px 0 0 160px;">*Campos em vermelho são obrigatórios</p>
                        </div><!--fecha formularioDividido-->  
                               
                    </fieldset>
                    
                    <fieldset class="dadosUsuario">
                        <h3 class="tituloBox">Contato</h3>
                        <div class="formularioDividido">
                            <div class="elementosFormulario2">
                                <label for="tel_res">Telefone residencial</label>
                                <input id="tel_res" name="tel_res" type="text" maxlength="14" onkeypress="return Mascara('TEL',this,event);" value="<?php echo $usuario->getContato()->getTelefoneFixo(); ?>" />
                                <label for="tel_cel" class="exibicaoContinua">Celular</label>
                                <input id="tel_cel" name="tel_cel" type="text" maxlength="16" onkeypress="return Mascara('CEL',this,event);" value="<?php echo $usuario->getContato()->getTelefoneCelular(); ?>" />
                            </div>
                            
                            <div class="elementosFormulario2">
                                <label for="tel_com">Telefone Comercial</label>
                                <input id="tel_com" name="tel_com" type="text" maxlength="14" onkeypress="return Mascara('TEL',this,event);" value="<?php echo $usuario->getContato()->getTelefoneComercial(); ?>" />
                            </div>
                            
                            <div class="elementosFormulario2">
                                <label for="mail" class="itensObrigatorios">E-mail</label>
                                <input id="mail" name="mail" type="text" value="<?php echo $usuario->getContato()->getEmail(); ?>" style="width:370px;" />
                            </div>
                            <p class="itensObrigatorios" style="margin:15px 0 0 160px;">*Campos em vermelho são obrigatórios</p>
                        </div><!--fecha formularioDividido-->
                    </fieldset>
                    
                   
                    <fieldset class="dadosUsuario">
                        <h3 class="tituloBox">Acesso</h3>
                        <div class="formularioDividido">
                            <div class="elementosFormulario2">
                                <label for="tipo_de_acesso" class="itensObrigatorios">Tipo de Acesso</label>
                                <?php echo $combo_status;?>
                            </div>
                            
                            <div id="especialidadesUsuario" class="elementosFormulario2">
                            	<input name='especialidades' type='hidden' value='<?php echo join(",",$especialidades);?>'>
                                <label class="itensObrigatorios">Especialidade(s)</label>
                                <span style="float:left; width:85%; margin-bottom:15px;">
                                <?php
									$pers = new Persistencia();
									$sql = "SELECT * FROM especialidade";
									$pers->bExecute($sql);
									$pers->bDados();
									while( $data = $pers->getDbArrayDados()){
									$id = (int) $data['id_especialidade'];
									$desc = utf8_encode($data['descricao']);
                                ?>
                                <span class="displayInline diminuirLargura">
                                <input id="option<?php echo $id; ?>" name="especialidades[]" type="checkbox" value="<?php echo $id; ?>" <?php echo in_array($id,$especialidades)?"checked":""; ?>/>
                                <label for="option<?php echo $id;?>" class="campoEspecialidade"><?php echo $desc; ?></label>
                                </span>
                                <?php
									$pers->bDados();
									} 
                                ?>
                                </span>
                            </div>
                            
                            <div class="elementosFormulario2">
                                <label id="label_cro" for="input_cro" class="itensObrigatorios">CRM</label>
                                <input id="input_cro" name="cro" type="text" maxlength="20" style="width:215px;" value="<?php echo $cro; ?>" />
                            </div>
                            <?php echo $camposSenhas?>
                            <p class="itensObrigatorios" style="margin:15px 0 0 160px;">*Campos em vermelho são obrigatórios</p>
                        </div><!--fecha formularioDividido-->
                    </fieldset>
					
					
					
					    <fieldset class="dadosUsuario">
                        <h3 class="tituloBox">Horários para Atendimento</h3>
                        <div class="formularioDividido">
                            <div class="elementosFormulario2">
                                <label for="logrdo">Horarios</label>
                                <input id="logrdo" name="logrdo" type="text" onchange="Mascara('STRING',this,event);" value="<?php echo $usuario->getEndereco()->getLogradouro(); ?>" style="width:417px;" />
                            </div>
                            
                           
                            
                            <div class="elementosFormulario2">
                                <label for="cep">outra coisa</label>
                                <input id="cep" name="cep" type="text" maxlength="5" onkeyup="tabProxCampo(this, this.value, 'cep_comp')" value="<?php echo $cep; ?>" />
                                <input class="ultimosDigitos" name="cep_comp" type="text" maxlength="3" value="<?php echo $cep_comp; ?>" />
                            </div>
							
                        </div><!--fecha formularioDividido-->          
                    </fieldset>
					
					 <fieldset class="dadosUsuario">
                        <h3 class="tituloBox">Convênios atendidos</h3>
                        <div class="formularioDividido">
                            <div class="elementosFormulario2">
                                <label for="logrdo">Horarios</label>
                                <input id="logrdo" name="logrdo" type="text" onchange="Mascara('STRING',this,event);" value="<?php echo $usuario->getEndereco()->getLogradouro(); ?>" style="width:417px;" />
                            </div>
                            
                           
                            
                            <div class="elementosFormulario2">
                                <label for="cep">outra coisa</label>
                                <input id="cep" name="cep" type="text" maxlength="5" onkeyup="tabProxCampo(this, this.value, 'cep_comp')" value="<?php echo $cep; ?>" />
                                <input class="ultimosDigitos" name="cep_comp" type="text" maxlength="3" value="<?php echo $cep_comp; ?>" />
                            </div>
							
                        </div><!--fecha formularioDividido-->          
                    </fieldset>
					
					
				    <fieldset class="dadosUsuario">
                        <h3 class="tituloBox">Endereço</h3>
                        <div class="formularioDividido">
                            <div class="elementosFormulario2">
                                <label for="logrdo">Logradouro</label>
                                <input id="logrdo" name="logrdo" type="text" onchange="Mascara('STRING',this,event);" value="<?php echo $usuario->getEndereco()->getLogradouro(); ?>" style="width:417px;" />
                            </div>
                            
                            <div class="elementosFormulario2">
                                <label for="numro">Número</label>
                                <input id="numro" name="numro" type="text" maxlength="10" value="<?php echo $usuario->getEndereco()->getNumero(); ?>" />
                                <label for="compto" class="exibicaoContinua">Complemento</label>
                                <input id="compto" name="compto" type="text" onchange="Mascara('STRING',this,event);" maxlength="45" value="<?php echo $usuario->getEndereco()->getComplemento(); ?>" />
                            </div>
                            
                            <div class="elementosFormulario2">
                                <label for="bairro">Bairro</label>
                                <input id="bairro" name="bairro" type="text" onchange="Mascara('STRING',this,event);" value="<?php echo $usuario->getEndereco()->getBairro(); ?>" style="width:417px;" />
                            </div>
                            
                            <div class="elementosFormulario2">
                                <label for="cidade">Cidade</label>
                                <input id="cidade" name="cidade" type="text" onchange="Mascara('STRING',this,event);" value="<?php echo $usuario->getEndereco()->getCidade(); ?>" style="width:417px;"/>
                            </div>
                            
                            <div class="elementosFormulario2">
                                <label for="estado">Estado</label>
                                    <?php
                                        $combo->bAddEstadosBrasileiros();
                                        if($usuario->getId()==0)
                                            $estado = 'MG';
                                        else
                                            $estado = $usuario->getEndereco()->getSiglaEstado();
                                        echo $combo->sGetHTML('','estado','uf','estado', $estado );
                                    ?>
                            </div>
                            
                            <div class="elementosFormulario2">
                                <label for="cep">CEP</label>
                                <input id="cep" name="cep" type="text" maxlength="5" onkeyup="tabProxCampo(this, this.value, 'cep_comp')" value="<?php echo $cep; ?>" />
                                <input class="ultimosDigitos" name="cep_comp" type="text" maxlength="3" value="<?php echo $cep_comp; ?>" />
                            </div>
                        </div><!--fecha formularioDividido-->          
                    </fieldset>
 
					
                    
                    <p id="botoesFormulario">
                        <button id="botaoNegativo" type="button" onclick="location.href='usuarios.php'">Cancelar</button>
                        <button class="botaoPositivo" type="button" onclick="valida_campos();">Salvar Usuário</button>
                    </p> 
                </form>
            
			<?php include_once("include/footer.php") ?>
        
        <script type="text/javascript">
            /* Alteracao de senha*/
            var altera_senha = function(){
                var md = new Modal(['modal']);
                md.setHeader('Alteração de Senha');
        
                var fm = new Form('form_e',['../controladores/usuarioGravar.php?acao=alterarSenha','post'],'field','');
        
                fm.addEvent('success',function(){
                    this[1].status_bar.highlight('#0F0','#FFF');
                    this[1].status_bar.set('html','Verificando...');
                    
                    data = JSON.decode(arguments[0]);
                    
                    if(data['success']=='1'){
                        this[1].status_bar.set('html','Senha Alterada!');
                        (function(){this.fadeAndRemove()}.bind(this[0])).delay(500);
                        //window.location = 'usuarios_geral.php';
                    } else {
                        this[1].status_bar.highlight('#F00','#FFF');
                        this[1].status_bar.set('html','Erro!');
                        alert('A senha antiga foi não confere!\nTente Novamente.');
                        this[1].form.getElements('input').set('value','');
                    }
                }.bind([md,fm]));
                fm.attach(md.win);
        
                senha_antiga = fm.newField('Senha Antiga','senha_antiga',200 ,'password');
                nova_senha = fm.newField('Nova Senha','senha',200 ,'password');
                confirma_senha = fm.newField('Confirmar Nova Senha','conf_senha',200 ,'password');
                confirma_senha.addEvent('change', function () {
                    if(this.value != nova_senha.value){
                        alert('Senha não confere com a digitada inicialmente');
                        this.value='';
                        this.focus();
                    }
                });
        
                fm.newField('','id',200 ,'hidden',document.cd_field.id_usuario.value);
                fm.newField('','noredir',1,'hidden',1);
                fm.injectBlock('Utilize números e caracteres para aumentar a segurança de sua senha.');
                fm.attachSendBar(['Alterar Senha','mb']);
                md.show();
            }
        
            function valida_campos(){
               if (valida_nome(document.cd_field.nome))
               if (valida_data(document.cd_field.data_nasc))
               if (valida_cpf(document.cd_field.cpf, document.cd_field.cpf_comp))
               if (valida_tel(document.cd_field.tel_res))
               if (valida_tel(document.cd_field.tel_cel))
               if (valida_tel(document.cd_field.tel_com))
               if (valida_email2(document.cd_field.mail))
               if (valida_numero(document.cd_field.numro, "Preencha o campo número corretamente"))
               if (valida_cep(document.cd_field.cep))
               if (valida_cep(document.cd_field.cep_comp))
               if (valida_cro())
               if (valida_login())
               if (valida_senha())
                 document.cd_field.submit();
            }
        
            function valida_cro(){
                if(( $('acesso').get('value') == "Dentista") && ($('input_cro').get('value').trim() == "" ) ){
                    alert('Preencha o campo CRO');
                    $('input_cro').focus();
                } 
				else {
                    return true;
                }
            }
			
            function valida_crm(){
                if(( $('acesso').get('value') == "Médico") && ($('input_cro').get('value').trim() == "" ) ){
                    alert('Preencha o campo CRM');
                    $('input_cro').focus();
                }  else {
                    return true;
                }
            }
        
            function valida_email2(campo){
                if(campo.value.trim() == ""){
                    alert('O campo e-mail é obrigatório, pois é utilizado pelo sistema');
                    campo.focus();
                    return false;
                } else {
                   return valida_email(document.cd_field.mail);
                }
            }
        
            function valida_login(){
                if($('login').get('value').trim() == "")
                {
                    alert('Informe um login valido');
                    $('login').focus();
                    return false;
                }
                return true;
            }
        
            function valida_senha(){
                if(document.getElementById('senha')){
                    if(document.cd_field.senha.value.trim() == ''){
                        alert('Preencha o campo senha');
                        document.cd_field.senha.focus();
                        return false;
                    }
                    else{
                        if((document.cd_field.senha.value != document.cd_field.conf_senha.value) || (document.cd_field.conf_senha.value.trim() == '')){
                            alert('Confirme a senha corretamente.');
                            document.cd_field.conf_senha.focus();
                            document.cd_field.conf_senha.value='';
                            return false;
                        } else {
                            return true;
                        }
                    }
                } else {
                    return true;
                }
            }
        
            function altera_display(tipo_acesso){
				
                if (tipo_acesso != "Médico"){
                    $('label_cro').set('style', "display:none;");
                    $('input_cro').set('style', "display:none;");
                    $('especialidadesUsuario').set('style', "display:none;");
					
                } else {
                    $('label_cro').set('style', "");
                    $('input_cro').set('style', "");
                    $('especialidadesUsuario').set('style', "");
                }
            }
			
		if($('acesso').value != "Médico"){
                    $('label_cro').set('style', "display:none;");
                    $('input_cro').set('style', "display:none;");
					$('especialidadesUsuario').set('style', "display:none;");
					
                }

        </script>
    </body>
</html>