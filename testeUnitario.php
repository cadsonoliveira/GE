<?php

include_once("../GE_DelRey/classes/classPersistencia.php");
include_once("../GE_DelRey/classes/classTemplateProntuario.php");
	//header("Location: layouts/login.php");


        
        
          $oPS = new TemplateProntuario();

    ## PREENCHENDO OS DEMAIS ATRIBUTOS DE PLANO DE SAUDE

    $oPS->setInstancia(0);// tem que pegar a isntancia do usuario addslashes($_POST['codigo']));
    $oPS->setUsuarioId(addslashes(0));//$_POST['nome_template']));
    $oPS->setNomeTemplate(addslashes('nome_template'));
    $oPS->setEspecialidadeId(addslashes('id_especialidade'));
    $oPS->setMedicamento(addslashes('medicamento'));
    $oPS->setConcentracao(addslashes('concentracao'));
    //$oPS->set(addslashes('quantidade'));
    $oPS->setInstrucao(addslashes('instrucao'));
    $oPS->setObservacao(addslashes('observacao'));
    $oPS->setCompartilhamento(addslashes('compartilhamento'));
    $oPS->bUpdate();
        
?>
