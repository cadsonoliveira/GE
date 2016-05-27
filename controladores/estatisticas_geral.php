<?php
	
	include_once ("../classes/classArcadaDentaria.php");
	include_once ("../classes/classMatchCode.php");
	include_once ("../classes/classTratamento.php");
	include_once ("../classes/classPessoa.php");
//classConsulta.php
//classErConsultaTratamento.php

/* -----copiado do controle paciente.php----
	if(isset($_GET['acao']) && ($_GET['acao']=='excluir'))
	{
		$paciente = new Paciente($_GET['id']);

		$paciente->bDelete();
		
	} else {
		if(isset($_POST))
		{
			#CONTATO
			$tel_res	= $_POST['tel_res'];
			$tel_cel	= $_POST['tel_cel'];
			$tel_com	= $_POST['tel_com'];
			$email		= $_POST['mail'];
	
			$contato = new Contato();
			$contato->setTelefoneFixo($tel_res);
			$contato->setTelefoneComercial($tel_com);
			$contato->setTelefoneCelular($tel_cel);
			$contato->setEmail($email);
	
			#ENDERECO
			$logrdo		= $_POST['logrdo'];
			$numro		= $_POST['numro'];
			$compto		= $_POST['compto'];
			$cidade		= $_POST['cidade'];
			$bairro		= $_POST['bairro'];
			$sigla_est	= $_POST['estado'];
			
			if(($_POST['cep'] != "") && ($_POST['cep_comp']))
			{
				$cep = $_POST['cep'].'-'.$_POST['cep_comp'];
			} else {
				$cep = "";
			}
			
			$endereco = new Endereco();
			$endereco->setLogradouro($logrdo);
			$endereco->setNumero($numro);
			$endereco->setComplemento($compto);
			$endereco->setCidade($cidade);
			$endereco->setBairro($bairro);
			$endereco->setSiglaEstado($sigla_est);
			$endereco->setCep($cep);
			
			#PESSOA / PACIENTE
			$nome		= $_POST['nome'];
			if($_POST['data_nasc'] != "") {
				$data 		= explode("/", $_POST['data_nasc']);
				$data_nasc	= $data[2].'-'.$data[1].'-'.$data[0];
			} else {
				$data_nasc = NULL;
			}

			if($_POST['data_cadastro'] != "") {
				$data_cad 		= explode("/", $_POST['data_cadastro']);
				$data_cadastro	= $data_cad[2].'-'.$data_cad[1].'-'.$data_cad[0];
			} else {
				$data_cadastro = NULL;
			}

			$sexo		= $_POST['sexo'];
			$cpf		= $_POST['cpf'].'-'.$_POST['cpf_comp'];
			$rg			= $_POST['rg'];
			
			$plano		= ($_POST['plano'] == -1) ? "NULL" : $_POST['plano'];
			$convenio	= ($_POST['convenio'] == -1) ? "NULL" : $_POST['convenio'];
			$dent_enc	= ($_POST['dentista_encaminhador'] == -1) ? "NULL" : $_POST['dentista_encaminhador'];
			$status		= 0;
			$obs		= "";
	
			$id = 0;
			if(isset($_GET) && isset($_GET['id']))
			{
				$id = $_GET['id'];
			}
	
			$paciente = new Paciente($id);
			$paciente->setNome($nome);
			$paciente->setDataNasc($data_nasc);
			$paciente->setSexo($sexo);
			$paciente->setRg($rg);
			$paciente->setCpf($cpf);
			$paciente->setContato($contato);
			$paciente->setEndereco($endereco);
			
			$paciente->setIdPlanoSaude($plano);
			$paciente->setStatus($status);
			$paciente->setObs($obs);
			$paciente->setDataCadastro($data_cadastro);
			$paciente->setIdDentistaEncaminhador($dent_enc);
			$paciente->setIdConvenio($convenio);
	
			$paciente->bUpdate();
			
		}
	}

	header("Location: ../layouts/pacientes.php");*/

?>