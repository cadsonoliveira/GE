<?php
	include_once("../classes/classPaciente.php");
	
	if(isset($_SESSION['PACIENTE']['ID'])){
		$paciente = new Paciente($_SESSION['PACIENTE']['ID']);
		$foto = $paciente->getCaminhoFoto();
        $nome_pac = $paciente->getNome();
	
		if(!isset($foto) || ($foto == "")){
			$caminho_foto = "img/usuario_foto.png";	
		}else{
			$caminho_foto = "../documentos/pacientes/".$paciente->getId()."/foto/".$foto;
		}
	}else{
		$caminho_foto = "img/usuario_foto.png";
		$nome_pac = "";
	}
?>
<div id="paciente" class="clearfix">
    <img src="<?php echo $caminho_foto; ?>" style="width:80px; height:80px;" alt="Foto <?php echo $nome_pac; ?>" />
    <p style="font-size:1.571em; font-weight:bold; margin-top:6px; display: block;">Paciente</p>
    <p style="font-size:1.214em; display: inline;"><a href="cadastro_paciente.php?acao=editar&amp;id=<?php echo $paciente->getId() ?>" title="Editar dados do paciente"><?php if($nome_pac == ""){echo("Nenhum paciente selecionado");}else{echo $nome_pac;};?></a></p>
    <p>
        <a <?php
        	if($pagina == "questionario_anamnese.php"){
				echo 'class="selectDadosPaciente"';
			}else{
				echo 'href="questionario_anamnese.php?acao=editar&amp;id='.$paciente->getId().'"';
			};		
		?> title="Questionário anamnese">Questionário anamnese</a> - 
        <?php
        	if($pagina != "agenda.php"){
				echo '<a href="agenda.php" title="Agendar consulta com o paciente">Agendar</a> -';
			};		
		?>
         <?php
        	if($pagina != "tratamentos.php"){
				echo '<a href="tratamentos.php" title="Tratamentos do paciente">Tratamentos</a> -';
			};		
		?>    
        <a <?php
        	if($pagina == "documentos_paciente.php"){
				echo 'class="selectDadosPaciente"';
			}else{
				echo 'href="documentos_paciente.php"';
			};		
		?>
         title="Documentos do paciente">Documentos</a>
    </p>
        <a 
        	<?php
        	if($pagina == "tratamentos.php" || $pagina == "atualizacao_tratamento.php" || $pagina == "cadastro_tratamento.php" ){
				echo 'href="seleciona_paciente_para_tratamentos.php"';
            }else if($pagina == "agenda.php"){
				echo 'href="seleciona_paciente_para_agendamento.php"';
            }else if($pagina == "relatorio.php"){
				echo 'href="seleciona_paciente_relatorio.php"';
            }else if($pagina == "receituario.php"){
				echo 'href="seleciona_paciente_receituario.php"';
            }else{
				echo 'href="pacientes.php"';
			}
			?>
			 title="Trocar Paciente" id="trocarPaciente">Trocar paciente</a>
</div> <!--Fecha paciente-->