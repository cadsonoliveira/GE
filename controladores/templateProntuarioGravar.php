<?php
include_once("../classes/classPersistencia.php");
include_once("../classes/classTemplateProntuario.php");
//include_once("../classes/classUsuario.php");


if($_GET['acao']=='excluir')
{
    echo 'div_erro|;|';
    $oPS = new TemplateProntuario($_GET['id']);
    $oPS->bDelete();
    
}
else
{
    #INSERCAO DE NOVO TEMPLATE DE PRONTUARIO
    if($_GET['id'])
        $oPS = new TemplateProntuario($_GET['id']);
    else
        $oPS = new TemplateProntuario();
    
    $id_usuario = 0;
    $id_especialidade = 0;
    $id_instancia = 0;
   
    if(isset($_SESSION['USUARIO']['ID'])) {
            $id_usuario = $_SESSION['USUARIO']['ID'];
            $profissionalLogado = new Usuario($id_usuario);
            $id_especialidade = 0;
            $id_instancia = 0;
    }
    else {
        
    }

   

    ## PREENCHENDO OS DEMAIS ATRIBUTOS DO TEMPLATE PRONTUARIO

    $oPS->setInstancia( $id_instancia );
    $oPS->setUsuarioId(  $id_usuario );
    $oPS->setNomeTemplate(addslashes($_POST['nome_template']));
    $oPS->setEspecialidadeId( $id_especialidade ); 
    $oPS->setMedicamento(addslashes($_POST['medicamento']));
    $oPS->setConcentracao(addslashes($_POST['concentracao']));
    $oPS->setQuantidade(addslashes($_POST['quantidade']));  
    $oPS->setInstrucao(addslashes($_POST['instrucao']));
    $oPS->setObservacao(addslashes($_POST['observacao']));
    $oPS->setCompartilhamento(0); /// copiar compartilhamento dos checkbox
    $oPS->bUpdate();
    //header("Location: ../layouts/planos_de_saude.php");
}
?>
