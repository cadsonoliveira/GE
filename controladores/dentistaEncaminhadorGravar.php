<?php
	include_once("../classes/classPersistencia.php");
	include_once("../classes/classDentistaEncaminhador.php");
	include_once("../classes/classContato.php");
	
	#INSERCAO DE NOVO PLANO DE SAUDE
		## PREENCHENDO O CONTATO DO PLANO DE SAUDE
		
	$oContato = new Contato();
	$oContato->setTelefoneFixo($_POST['tel_fixo']);
	$oContato->setTelefoneComercial($_POST['tel_com']);
	$oContato->setTelefoneCelular($_POST['tel_cel']);
	$oContato->setEmail($_POST['email']);

	$oContato->bUpdate();

		## PREENCHENDO OS DEMAIS ATRIBUTOS DE PLANO DE SAUDE
		
	$oDentista = new DentistaEncaminhador();
	$oDentista->setCRO(addslashes($_POST['cro']));
	$oDentista->setNome(addslashes($_POST['nome']));
	$oDentista->setIdContato($oContato->getId());
	
	$oDentista->bUpdate();

?>