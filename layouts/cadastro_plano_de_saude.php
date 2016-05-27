<?php
	//Antiga página planos_cadastro.php

    session_start();

    include_once("../classes/classPlanoSaude.php");
    include_once("../classes/classContato.php");

    if((!isset($_SESSION['USUARIO'])) && ($_SESSION['USUARIO']['VALIDA'] != true)){
       header("Location: ../layouts/login.php?tipo=2");
    } else {
        $action_form = "../controladores/planoSaudeGravar.php";

        $id=0;

        if(isset($_GET['id']) && isset($_GET['acao'])){
            if($_GET['acao']=='editar'){
                $id = $_GET['id'];
                $plano = new PlanoSaude($id);
                $contato = new Contato($plano->getIdContato());
                $action_form .= "?id=".$id;
            }
        }
        else{
            $plano = new PlanoSaude();
            $contato = new Contato();
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
                <li><span class="breadcrumbEsquerda"></span><a href="planos_de_saude.php" title="lista de planos de saúde">planos de saúde</a><span class="breadcrumbDireita"></span>
                    <ul>
                        <li><span class="breadcrumbEsquerda bcrumbSelect"></span><h2 class="bcrumbAtivo bcrumbSelect">cadastro de plano de saúde</h2><span class="breadcrumbDireita bcrumbSelect"></span></li>
                    </ul>
                </li>
            </ul>
        </div> <!--Fecha div breadcrumb-->

        <div id="container" class="clearfix cadastro_plano">
            <form id="cd_field" action="<?php echo $action_form; ?>" method="post"  name="cd_field">
                <fieldset>
                    <h3 class="tituloBox">Dados do Plano</h3>
                    <div class="formularioDividido">
                    	<div class="elementosFormulario2">
                            <label for="nome" class="itensObrigatorios">Nome</label>
                            <input id="nome" name="nome" type="text" onblur="Mascara('STRING',this,event);" value="<?php echo $plano->getNome(); ?>" />
                        </div>
                        
                        <!--<div class="elementosFormulario2">
                            <label for="codigo" class="itensObrigatorios">Código</label>
                            <input id="codigo" name="codigo" type="text" onblur="Mascara('STRING',this,event);" value="<?php //echo $plano->getCodigo(); ?>"/>
                        </div>-->
                        
                        <div class="elementosFormulario2">
                            <label for="telefone_fixo_plano">Telefones Fixos</label>
                            <input id="telefone_fixo_plano" name="tel_fixo" type="text" maxlength="14"  style="display:block; float:left;" class="inputMenor" onkeypress="return Mascara('TEL',this,event);" value="<?php echo $contato->getTelefoneFixo(); ?>"/>
                            <input id="telefone_fixo_plano2" name="tel_com" type="text" maxlength="14" class="inputMenor" onkeypress="return Mascara('TEL',this,event);" value="<?php echo $contato->getTelefoneComercial(); ?>" style="margin-left:10px;"/>
                        </div>
                        
                        <div class="elementosFormulario2">
                            <label for="celular">Celular</label>
                            <input id="celular" name="tel_cel" type="text" class="inputMenor" maxlength="16" onkeypress="return Mascara('CEL',this,event);" value="<?php echo $contato->getTelefoneCelular(); ?>"/>                        
                        </div>
						
                        <label for="email">E-mail</label>
                        <input id="email" name="email" type="text" style="width:390px;" value="<?php echo $contato->getEmail(); ?>"/>
						   	 
                        <div class="elementosFormulario2">
                            <label for="cnpj">CNPJ</label>
                            <input id="cnpj" name="tel_cel" type="text" class="inputMenor" maxlength="16" onkeypress="return Mascara('CPF',this,event)" value="<?php echo $contato->getTelefoneCelular(); ?>"/>                        
                        </div>
						
						<div class="elementosFormulario2">
                            <label for="contato">Contato</label>
                            <input id="contato" name="tel_cel" type="text" class="inputMenor" maxlength="16" onblur="Mascara('STRING',this,event);"  value="<?php echo $contato->getTelefoneCelular(); ?>"/>                        
                        </div>
						 
						<div class="elementosFormulario2">                                    
                        <label for="observacao_documento">Observações</label>
                        <textarea id="observacao_documento" name="sessao" cols="50" rows="4"></textarea>
                        </div>
						
                        <p style="margin:10px 0 0 160px; color:#666;">Procurar o telefone do Plano de Saúde Cadastrado ajudará você e a todos da equipe em agendamentos e pesquisas futuras.</p>
                        <p class="itensObrigatorios" style="margin:2px 0 0 160px;">*Preencha os campos vermelhos obrigatoriamente</p>
                        </div>
						
					
                </fieldset>
                
                
                <p id="botoesFormulario">
                  <button id="botaoNegativo" onclick="location.href='planos_de_saude.php'" type="button">Cancelar</button>
                  <button class="botaoPositivo" type="button" onclick="valida_campos();">Salvar Plano</button>
                </p>                         
            </form>
            
			<?php include_once("include/footer.php") ?>
            
            <script src="js/calendar.js" type="text/javascript"></script>
			<script type="text/javascript">
					function valida_campos(){
						if (valida_nome(document.cd_field.nome))
						//if (valida_nome(document.cd_field.codigo, "Informe o código"))
						if (valida_tel(document.cd_field.tel_fixo))
						if (valida_tel(document.cd_field.tel_cel))
						if (valida_tel(document.cd_field.tel_com))
						if (valida_email(document.cd_field.email))
						document.cd_field.submit();
					}
            
            </script>
    </body>
</html>