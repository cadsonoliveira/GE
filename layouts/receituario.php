<?php
	//Antiga página receituario_geral.php
	session_start();
	
	include_once("../classes/classPersistencia.php");
	include_once("../classes/classPessoa.php");
	include_once("../classes/classPaciente.php");
	include_once("../classes/classTratamento.php");
	include_once("../classes/classMatchCode.php");
	
	if((!isset($_SESSION['USUARIO'])) && ($_SESSION['USUARIO']['VALIDA'] != true)){
		header("Location: ../layouts/login.php?tipo=2");
	} else {
		/************************************************************************************
		 *CARREGA OS DADOS DO TRATAMENTO
		/************************************************************************************/
	
		$id_paciente = 0;
		if(isset($_SESSION['PACIENTE']['ID'])){
			$id_paciente = $_SESSION['PACIENTE']['ID'];
		}
		$id_tratamento = 0;
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
		$enable_email = $paciente->getContato()->getEmail()==""?"disabled":"";
		$msg_enable_email = $paciente->getContato()->getEmail()==""?"(O paciente não possui e-mail cadastrado)":"";
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
                            <li><span class="breadcrumbEsquerda bcrumbSelect"></span><h2 class="bcrumbAtivo bcrumbSelect">receituário</h2><span class="breadcrumbDireita bcrumbSelect"></span></li>
                        </ul>
                    </li>
                </ul>
            </div> <!--Fecha div breadcrumb-->
            
            <div id="container" class="clearfix">
                <form action="../controladores/receituarioGerar.php" method="post">
                    <input type="hidden" name="paciente" value="<?php echo $paciente->getId(); ?>" />
                    <h3 class="tituloBox">Receita</h3>
                    <div class="formularioDividido">
                    <fieldset>
                        <label class="elementosFormulario" for="medicamentos">Medicamentos</label>
                        <textarea id="medicamentos" name="medicamentos" cols="50" rows="4"></textarea>
                        <label class="elementosFormulario" for="posologia">Posologia</label>
                        <textarea id="posologia" name="posologia" cols="50" rows="4"></textarea>
                    </fieldset>
                    </div>
                    <h3 class="tituloBox">Destinatários de envio por email</h3>
                    <div class="formularioDividido">
                    <fieldset id="receituarioDestinatarios">
                        <div id="receituarioPaciente">
                        <input id="opcaoPaciente" name="enviar_paciente" type="checkbox" value="paciente" <?php echo $enable_email; ?> />
                        <label for="opcaoPaciente">Paciente</label>
                        </div>
                        <!--<div id="receituarioSecretaria">
                        <input id="opcaoSecretaria" name="enviar_secretaria" type="checkbox" value="secretaria" />
                        <label for="opcaoSecretaria">Secretária</label>
                        </div>--> 
                        <label for="outro_email">Outro e-mail</label>
                        <input id="outro_email" name="enviar_outro" type="text" />                    
                    </fieldset>
                    </div>
                    <p id="botoesFormulario">
                        <button id="botaoNegativo" type="button" onclick="location.href='pacientes.php'">Cancelar</button>
                        <button class="botaoPositivo" type="submit" onclick="valida_campos();">Gerar Receita</button>
                    </p>                  
                </form>
			<?php include_once("include/footer.php") ?>
        
        <script type="text/javascript">
            function valida_campos(){
             if(document.cd_field.paciente.value!=-1&&<?php echo $paciente->getId(); ?>!=0)
                   document.cd_field.submit();
             else
                alert('Informe o paciente corretamente.');
            }
            
            function atualiza(id_pessoa){
                xhSendPost("../controladores/AJAX.receituario_geral.php?paciente="+id_pessoa+"&div_id=ajax");
            }
        </script>
    </body>
</html>