<?php
include_once("../classes/classPersistencia.php");
include_once("../classes/classPlanoSaude.php");
include_once("../classes/classContato.php");

if($_GET['acao']=='excluir')
{
    echo 'div_erro|;|';
    $oPS = new PlanoSaude($_GET['id']);
    if($oPS->existemPacientes())
    echo 'Erro ao excluir registro, verifique se existem pacientes com este plano .';
    else{
        $oPS->bDelete();
    }
}
else
{

    #INSERCAO DE NOVO PLANO DE SAUDE
    ## PREENCHENDO O CONTATO DO PLANO DE SAUDE
    if($_GET['id'])
        $oPS = new PlanoSaude($_GET['id']);
    else
        $oPS = new PlanoSaude();

    if($oPS->getIdContato())
        $oContato = new Contato($oPS->getIdContato());
    else
        $oContato = new Contato();
    $oContato->setTelefoneFixo($_POST['tel_fixo']);
    $oContato->setTelefoneComercial($_POST['tel_com']);
    $oContato->setTelefoneCelular($_POST['tel_cel']);
    $oContato->setEmail($_POST['email']);

    $oContato->bUpdate();

    ## PREENCHENDO OS DEMAIS ATRIBUTOS DE PLANO DE SAUDE
	
	//Para voltar a cadastrar o código do plano descomentar
    //$oPS->setCodigo(addslashes($_POST['codigo']));
    $oPS->setNome(addslashes($_POST['nome']));
    $oPS->setIdContato($oContato->getId());
    $oPS->bUpdate();
    header("Location: ../layouts/planos_de_saude.php");

}
?>