<?php

    session_start();

    if((!isset($_SESSION['USUARIO'])) && ($_SESSION['USUARIO']['VALIDA'] != true)){
       header("Location: ../layouts/login.php?tipo=2");
    } 

	include_once("../classes/classAnamnese.php");
	
	$anamnese = new Anamnese();
	if($_POST['id_anamnese'] != 0) {
		$anamnese->getAnamneseById($_POST['id_anamnese']);
	} else {
		$anamnese->getAnamneseByPaciente($_POST['id_pessoa']);
	}
	
	$anamnese->setIdPessoa($_POST['id_pessoa']);
	
	$anamnese->setBoaSaude($_POST['saudavel']);
	$anamnese->setBoaSaudePorque($_POST['saudavel_mais']);
	$anamnese->setVisitasFreqMedico($_POST['visitas_medico']);
	$anamnese->setVisitasFreqMedicoMotivo($_POST['visitas_medico_mais']);
	$anamnese->setMedicacaoRotina($_POST['medicacao_rotina']);
	$anamnese->setMedicacaoRotinaQual($_POST['medicacao_rotina_mais']);
	$anamnese->setAlergia($_POST['alergias']);
	$anamnese->setAlergiaDeQue($_POST['alergias_mais']);
	$anamnese->setDoencasPrevias($_POST['doencas_previas']);
	$anamnese->setDoencasPreviasQual($_POST['doencas_previas_mais']);
	$anamnese->setCirurgiasPrevias($_POST['cirurgias_previas']);
	$anamnese->setCirurgiasPreviasQual($_POST['cirurgias_previas_mais']);
	$anamnese->setHepatite((isset($_POST['doenca_hepatite']) && ($_POST['doenca_hepatite'] == 'S')) ? 'S' : 'N');
	$anamnese->setDiabetis((isset($_POST['doenca_diabetis']) && ($_POST['doenca_diabetis'] == 'S')) ? 'S' : 'N');
	$anamnese->setFebreReumatica((isset($_POST['doenca_febre_reumatica']) && ($_POST['doenca_febre_reumatica'] == 'S')) ? 'S' : 'N');
	$anamnese->setDoencasArterial((isset($_POST['doenca_arterial']) && ($_POST['doenca_arterial'] == 'S')) ? 'S' : 'N');
	$anamnese->setPressaoArterial((isset($_POST['doenca_pressao_arterial']) && ($_POST['doenca_pressao_arterial'] == 'S')) ? 'S' : 'N');
	$anamnese->setAcidFraturasOdont($_POST['acidentes']);
	$anamnese->setAcidFraturasOdontQual($_POST['acidentes_mais']);
	$anamnese->setDificAbertBoca($_POST['dificuldade_abertura_boca']);
	$anamnese->setMuitaSalivacao($_POST['dificuldade_salivacao']);
	$anamnese->setAnsiaVomito($_POST['dificuldade_ansia_vomito']);
	$anamnese->setDorNasCostas($_POST['dificuldade_dor_costas']);
	$anamnese->setTraumatismoDentario($_POST['traumatismo_dentario']);
	$anamnese->setRecomendacaoMedica($_POST['recomendacao_medica']);
	$anamnese->setRestricaoMedicamentos($_POST['restricao_medicamentos']);
	$anamnese->setRestricaoMedicamentosQual($_POST['restricao_medicamentos_mais']);
	$anamnese->setVisitaRegularDent($_POST['visitas_regulares']);
	$anamnese->setUltimoTratOdont((isset($_POST['tempo_ultimo_tratamento']) && ($_POST['tempo_ultimo_tratamento'] != "")) ? $_POST['tempo_ultimo_tratamento'] : 'null');
	$anamnese->setInfAdicionalImportante($_POST['info_adicional']);							

	$anamnese->bUpdate();


    header("Location: ../layouts/pacientes.php");

?>
