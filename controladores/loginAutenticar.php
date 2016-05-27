<?php

    session_start();
    
    include_once("../classes/classUsuario.php");
    include_once("../classes/classPersistencia.php");

	$pers = new Persistencia();
	
	date_default_timezone_set("America/Sao_Paulo");
	//echo date_default_timezone_get();
	
	$now = time(); 

	if(isset($_POST)){
		$user = addslashes($_POST['user']);
		$pass = md5($_POST['pass']);

		$sSql = "SELECT * FROM usuario WHERE login = '".$user."' AND senha = '".$pass."'";

		$pers->bExecute($sSql);
		if($pers->getDbNumRows() > 0){
			#Se o usuário for válido
			$pers->bDados();
			$vet_resultado = $pers->getDbArrayDados();
			#Instacia o objeto usuario
			$usuario = new Usuario($vet_resultado['id_pessoa']);
			$data_ultimo_acesso = $usuario->getUltimoAcesso();
			#Atualiza a data e hora do último acesso
			date_default_timezone_set('America/Sao_Paulo');
			$data_corrente = date("Y-m-d H:i:s");
			$usuario->setUltimoAcesso($data_corrente);
			$usuario->bUpdate();

			#Autentica a sessao
			$_SESSION['USUARIO']['VALIDA'] = true;
			$_SESSION['USUARIO']['ID'] = $usuario->getId();
			$_SESSION['USUARIO']['NOME'] = $usuario->getNome();
			$_SESSION['USUARIO']['EMAIL'] = $usuario->getContato()->getEmail();
			$_SESSION['USUARIO']['ULTIMO_ACESSO'] = $data_ultimo_acesso;
			header("Location: ../layouts/index.php");
		} else {
			header("Location: ../layouts/login.php?tipo=1");
		}
	} else {
		header("Location: ../layouts/login.php?tipo=3");
		

	}
?>