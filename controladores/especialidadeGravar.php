<?php

include_once("../classes/classEspecialidade.php");
include_once("../classes/classConfiguracao.php");
include_once("../classes/classPersistencia.php");

if(isset($_GET['acao']) && $_GET['acao']=='excluir'){
        $persistencia = new Persistencia();
        $sSql = "SELECT * FROM match_code WHERE id_especialidade=".$_GET['id'];

        $persistencia->bExecute($sSql);

        $configuracao = new Configuracao(1);
        $id_especialidade = $configuracao->getIdEspecialidade();

        if($id_especialidade != $_GET['id']){
            if($persistencia->getDbNumRows() > 0){
               echo '<script> alert("Existe Procedimentos Para Esta Especialidade.\nRemova Todos os Procedimentos Antes de Excluir a Especialidade");</script>';
            } else {
                $especialidade = new Especialidade($_GET['id']);
                $especialidade->bDelete();
                $erro = 0;
            }
        } else {
            echo '<script> alert("Não é Possível Excluir a Especialidade Ativa");</script>';
        }
    }
	else{
	    #INSERCAO DE NOVA ESPECIALIDADE
	    if($_GET['id'])
	       $especialidade = new Especialidade($_GET['id']);
	    else
	       $especialidade = new Especialidade();

	    ## PREENCHENDO OS DEMAIS ATRIBUTOS DE ESPECIALIDADE

	    $especialidade->setDescricao($_POST['descricao']);
	    $especialidade->bUpdate();
	}

    header("Location: ../layouts/especialidades.php");

?>
