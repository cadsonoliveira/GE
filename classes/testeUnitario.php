<?php

include_once("../classes/classPersistencia.php");
include_once("../classes/classTemplateProntuario.php");
	//header("Location: layouts/login.php");


        
        
     $oPS = new TemplateProntuario();

    ## PREENCHENDO OS DEMAIS ATRIBUTOS DE PLANO DE SAUDE

    $oPS->setInstancia(0);// tem que pegar a isntancia do usuario addslashes($_POST['codigo']));
    $oPS->setUsuarioId(addslashes(0));//$_POST['nome_template']));
    $oPS->setNomeTemplate(addslashes('nome_templateUnitario'));
    $oPS->setEspecialidadeId(addslashes('id_especialidade'));
    $oPS->setMedicamento(addslashes('medicamento'));
    $oPS->setConcentracao(addslashes('concentracao'));
    $oPS->setQuantidade(addslashes('quantidade'));
    $oPS->setInstrucao(addslashes('instrucao'));
    $oPS->setObservacao(addslashes('observacao'));
    $oPS->setCompartilhamento(addslashes('compartilhamento'));
    //$oPS->bUpdate();
    
          
    $oPS2 = new TemplateProntuario(2);
    echo 'Hello World';
    echo $oPS2->getNomeTemplate();
        
?>
