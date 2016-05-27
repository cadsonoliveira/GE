<?php
    
    echo $_GET['div_id'].'|;|';

    include_once ("../classes/classPersistencia.php");
    include_once("../classes/classEspecialidade.php");

    $action_form = "../controladores/especialidadeGravar.php?acao=editar";

    if(isset($_GET['id']))
        $id = $_GET['id'];
    else
        $id=1;
		
    $action_form .= "&id=".$id;
	
	        $especialidade = new Especialidade($id);
        $descricao_Especialidade = $especialidade->getDescricao();


	include_once("../funcoes/common_especialidades.php");
?>

	<?php include_once("../layouts/include/conteudo_editar_especialidades.php") ?>