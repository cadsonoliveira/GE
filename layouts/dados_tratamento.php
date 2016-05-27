<?php
	//Antiga página tratamentos_visualizar.php

    session_start();

    if((!isset($_SESSION['USUARIO'])) && ($_SESSION['USUARIO']['VALIDA'] != true)){
        header("Location: ../layouts/login.php?tipo=2");
    } else {
		include_once("../funcoes/common.php");
		include_once("../classes/classPaciente.php");
		include_once("../classes/classEndereco.php");
		include_once("../classes/classPlanoSaude.php");
		include_once("../classes/classMatchCode.php");
		include_once("../classes/classDentistaEncaminhador.php");
		include_once("../classes/classTratamento.php");
		
		$tratamento = new Tratamento($_POST['id_tratamento']);
		$paciente = new Paciente($tratamento->getIdPessoa());
		$matchcode = new MatchCode($tratamento->getIdMatchCode());
		$dent_enc = new DentistaEncaminhador($paciente->getIdDentistaEncaminhador());
		$plano = new PlanoSaude($paciente->getIdPlanoSaude());
		$acao=isset($_GET['acao'])?1:0;
	
		/*******************************************************************************
		*LISTA IMAGENS DO TRATAMENTO                                                   *
		*******************************************************************************/
		if(isset($_POST['id_tratamento'])) {
			$id_tratamento = $tratamento->getId();
			$cam_img_tratamento = '../documentos/pacientes/'.$paciente->getId().'/tratamento/';
			$img_tratamento_HTML = "";
			$persistencia = new Persistencia();
			$sSql = "SELECT caminho FROM imagem WHERE id_tratamento=$id_tratamento";
			$persistencia->bExecute($sSql);
			$persistencia->bDados();
		
			if($persistencia->getDbNumRows() != 0) {
				for($i=0;$i<$persistencia->getDbNumRows();$i++) {
					if($persistencia->bCarregaRegistroPorLinha($i)) {
						$vet = $persistencia->getDbArrayDados();
						$imagens_anteriores[$i] = $vet['caminho'];
					}
				}
		
				foreach($imagens_anteriores as $indice => $imagem ) {
					if(substr($imagem, -3) == 'avi'){
						$img_tratamento_HTML .= '<a href="'.$cam_img_tratamento.$imagem.'" target="blank" title="Visualizar" href="javascript:;" class="view ir">Visualizar</a>';
					} else {
						$img_tratamento_HTML .= '<a onclick="show_full(this)" title="Zoom" class="zoom ir" href="javascript:;">Zoom</a><img src="'.$cam_img_tratamento.$imagem.'">';
					}
				}
			}
		}
	}
	
	/*******************************************************************************
	*                                                                              *
	*******************************************************************************/
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
                        	<li><span class="breadcrumbEsquerda"></span><a href="documentos_paciente.php">documentos do paciente</a><span class="breadcrumbDireita"></span>
                                <ul>
                                    <li><span class="breadcrumbEsquerda bcrumbSelect"></span><h2 class="bcrumbAtivo bcrumbSelect">dados tratamento</h2><span class="breadcrumbDireita bcrumbSelect"></span></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div> <!--Fecha div breadcrumb-->
    
            <div id="container" class="clearfix">
                <h3 class="tituloBox">Tratamento</h3>
                <div id="dadosTratamento" class="formularioDividido">
                <p><strong>Tratamento:</strong> <?php echo $matchcode->getDescricao(); ?></p>
                <p><strong>Dente:</strong> <?php echo $tratamento->getDente(); ?></p>
                <p><strong>Data de Início:</strong> <?php echo decodeDate($tratamento->getDataInicio())."&nbsp;&nbsp;&nbsp;"; ?> <strong>Data de término:</strong> <?php echo decodeDate($tratamento->getDataTermino()); ?></p>
                <p><strong>Descrição:</strong> <?php echo $tratamento->getDescricao(); ?></p>
                <p><strong>Resultado:</strong>
                    <?php
                        switch($tratamento->getSucesso()){
                            case 0:
                                $resultado = "Insucesso";
                                break;
                            case 1:
                                $resultado = "Sucesso";
                                break;
                            case 2:
                                $resultado = "Pendente";
                                break;
                            case 3:
                                $resultado = "Cancelado";
                                break;
                        }
                        echo $resultado;
                      ?>
                </p>
                </div>
                
                <h3 class="tituloBox">Imagens selecionadas</h3>
                <div id="imagensSelecionadas" class="formularioDividido">
                    <?php
                        echo $img_tratamento_HTML;
                      ?>
                </div>
                
                
                <p id="botoesFormulario">
                    <button class="botaoPositivo" type="button" onclick="voltar(<?php echo $acao; ?>);">Voltar</button>
                </p>

                
            
		<?php include_once("include/footer.php") ?>
        
        <script src="js/mouseover_popup.js" type="text/javascript"></script>
        <script type="text/javascript">
                function voltar(acao){
                    if(acao){
                        location.href='documentos_paciente.php';
                    } else {
                        history.back(1);
                    }
                }
        </script>
    </body>
</html>