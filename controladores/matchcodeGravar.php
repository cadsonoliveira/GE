<?php
	include_once("../classes/classMatchCode.php");
	
	$header = "";
	if(isset($_GET['acao']) && $_GET['acao']=='excluir'){
		$match_code = new MatchCode($_GET['id']);
		$match_code->bDelete();
		$header = "?id=".$_GET['id_especialidade'];
	}else{
		#INSERCAO DE NOVO MATCH CODE
		if(isset($_POST['id']) && $_POST['id'])
		   $match_code = new MatchCode($_POST['id']);
		else
		   $match_code = new MatchCode();
	
		## PREENCHENDO OS DEMAIS ATRIBUTOS DE MATCH CODE
	
		$match_code->setDescricao(addslashes($_POST['descricao']));
		$match_code->setTipo($_POST['tipo']);
		$match_code->setIdEspecialidade($_POST['id_especialidade']);
		$match_code->bUpdate();
		
	}
	header("Location: ../layouts/editar_especialidade.php".$header);
?>
