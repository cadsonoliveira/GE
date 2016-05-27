<?php

include_once("../classes/classConfiguracao.php");

if($_GET['acao']=='excluir')
{
    $configuracao = new Configuracao($_GET['id']);
    $configuracao->bDelete();
}
else
{
    if($_GET['acao']=='editaTexto')
    {
       $configuracao = new Configuracao(1);
       $configuracao->setTextoIndex($_POST['texto']);
       $configuracao->bUpdate();
    }
    else
    {
       #INSERCAO DE NOVA ESPECIALIDADE
       if($_GET['acao']=='editar')
          $configuracao = new Configuracao(1);
       else
          $configuracao = new Configuracao();
       ## PREENCHENDO OS DEMAIS ATRIBUTOS DE ESPECIALIDADE

       $configuracao->setIdEspecialidade($_GET['id']);
       $configuracao->bUpdate();
    }
}
header("Location: ../layouts/especialidades.php");
?>
