<?php
	
	include_once("classPersistencia.php");
 	
	class Anamnese extends Persistencia
	{
		private $iId;
		private $sIdPessoa;
		private $bBoaSaude;
		private $sBoaSaudePorque;
		private $bVisitasFreqMedico;
		private $sVisitasFreqMedicoMotivo;
		private $bMedicacaoRotina;
		private $sMedicacaoRotinaQual;
		private $bAlergia;
		private $sAlergiaDeQue;
		private $bDoencasPrevias;
		private $sDoencasPreviasQual;
		private $bCirurgiasPrevias;
		private $sCirurgiasPreviasQual;
		private $bHepatite;
		private $bDiabetis;
		private $bFebreReumatica;
		private $bDoencasArterial;
		private $bPressaoArterial;
		private $bAcidFraturasOdont;
		private $sAcidFraturasOdontQual;
		private $bDificAbertBoca;
		private $bMuitaSalivacao;
		private $bAnsiaVomito;
		private $bDorNasCostas;
		private $bTraumatismoDentario;
		private $sRecomendacaoMedica;
		private $bRestricaoMedicamentos;
		private $sRestricaoMedicamentosQual;
		private $bVisitaRegularDent;
		private $iUltimoTratOdont;
		private $sInfAdicionalImportante;
		
		
		
		public function __construct($anamnese_id = 0) {
			parent::__construct();

			if($anamnese_id != 0) {
				$this->getAnamneseById($anamnese_id);
			} else {
				$this->setId($anamnese_id);
				$this->setIdPessoa('');
				$this->setBoaSaude('');
				$this->setBoaSaudePorque('');
				$this->setVisitasFreqMedico('');
				$this->setVisitasFreqMedicoMotivo('');
				$this->setMedicacaoRotina('');
				$this->setMedicacaoRotinaQual('');
				$this->setAlergia('');
				$this->setAlergiaDeQue('');
				$this->setDoencasPrevias('');
				$this->setDoencasPreviasQual('');
				$this->setCirurgiasPrevias('');
				$this->setCirurgiasPreviasQual('');
				$this->setHepatite('');
				$this->setDiabetis('');
				$this->setFebreReumatica('');
				$this->setDoencasArterial('');
				$this->setPressaoArterial('');
				$this->setAcidFraturasOdont('');
				$this->setAcidFraturasOdontQual('');
				$this->setDificAbertBoca('');
				$this->setMuitaSalivacao('');
				$this->setAnsiaVomito('');
				$this->setDorNasCostas('');
				$this->setTraumatismoDentario('');
				$this->setRecomendacaoMedica('');
				$this->setRestricaoMedicamentos('');
				$this->setRestricaoMedicamentosQual('');
				$this->setVisitaRegularDent('');
				$this->setUltimoTratOdont('');
				$this->setInfAdicionalImportante('');
				
				
			}
		}
		
		public function bFetchObject($sql) {
			$this->bExecute($sql);
			$this->bDados();
			
			$res = $this->getDbArrayDados();
			
			if($this->getDbNumRows() > 0)
			{
				$this->setId($res['id_anamnese']);
				$this->setIdPessoa($res['id_pessoa']);
				$this->setBoaSaude($res['boa_saude']);
				$this->setBoaSaudePorque($res['boa_saude_porque']);
				$this->setVisitasFreqMedico($res['visitas_freq_medico']);
				$this->setVisitasFreqMedicoMotivo($res['visitas_freq_medico_motivo']);
				$this->setMedicacaoRotina($res['medicacao_rotina']);
				$this->setMedicacaoRotinaQual($res['medicacao_rotina_qual']);
				$this->setAlergia($res['alergia']);
				$this->setAlergiaDeQue($res['alergia_de_que']);
				$this->setDoencasPrevias($res['doencas_previas']);
				$this->setDoencasPreviasQual($res['doencas_previas_qual']);
				$this->setCirurgiasPrevias($res['cirurgias_previas']);
				$this->setCirurgiasPreviasQual($res['cirurgias_previas_qual']);
				$this->setHepatite($res['hepatite']);
				$this->setDiabetis($res['diabetis']);
				$this->setFebreReumatica($res['febre_reumatica']);
				$this->setDoencasArterial($res['doencas_arterial']);
				$this->setPressaoArterial($res['pressao_arterial']);
				$this->setAcidFraturasOdont($res['acid_fraturas_odont']);
				$this->setAcidFraturasOdontQual($res['acid_fraturas_odont_qual']);
				$this->setDificAbertBoca($res['dific_abert_boca']);
				$this->setMuitaSalivacao($res['muita_salivacao']);
				$this->setAnsiaVomito($res['ansia_vomito']);
				$this->setDorNasCostas($res['dor_nas_costas']);
				$this->setTraumatismoDentario($res['traumatismo_dentario']);
				$this->setRecomendacaoMedica($res['recomendacao_medica']);
				$this->setRestricaoMedicamentos($res['restricao_medicamentos']);
				$this->setRestricaoMedicamentosQual($res['restricao_medicamentos_qual']);
				$this->setVisitaRegularDent($res['visita_regular_dent']);
				$this->setUltimoTratOdont($res['ultimo_trat_odont']);
				$this->setInfAdicionalImportante($res['inf_adicional_importante']);
			}
		}
			
		public function getAnamneseById($anamnese_id) {
			$sSql = "SELECT * FROM anamnese WHERE id_anamnese=".$anamnese_id;
			$this->bFetchObject($sSql);
		}

		public function getAnamneseByPaciente($id_pessoa) {
			$sSql = "SELECT * FROM anamnese WHERE id_pessoa=".$id_pessoa;
			$this->bFetchObject($sSql);
		}

		
		public function bUpdate() {
			if(($this->getId()) == 0) {
				#INSERIR UM NOVO ENDERECO NO BANCO DE DADOS
				$sSql = "INSERT INTO anamnese (id_pessoa, boa_saude, boa_saude_porque, visitas_freq_medico,
					visitas_freq_medico_motivo, medicacao_rotina, medicacao_rotina_qual, alergia, alergia_de_que,
					doencas_previas, doencas_previas_qual, cirurgias_previas, cirurgias_previas_qual, hepatite,
					diabetis, febre_reumatica, doencas_arterial, pressao_arterial, acid_fraturas_odont,
					acid_fraturas_odont_qual, dific_abert_boca, muita_salivacao, ansia_vomito, dor_nas_costas,
					traumatismo_dentario, recomendacao_medica, restricao_medicamentos, restricao_medicamentos_qual,
					visita_regular_dent, ultimo_trat_odont, inf_adicional_importante) VALUES (";

				$sSql .= " ".$this->getIdPessoa().", ";
				$sSql .= " '".$this->getBoaSaude()."', ";
				$sSql .= " '".utf8_decode($this->getBoaSaudePorque())."', ";
				$sSql .= " '".$this->getVisitasFreqMedico()."', ";
				$sSql .= " '".utf8_decode($this->getVisitasFreqMedicoMotivo())."', ";
				$sSql .= " '".$this->getMedicacaoRotina()."', ";
				$sSql .= " '".utf8_decode($this->getMedicacaoRotinaQual())."', ";
				$sSql .= " '".$this->getAlergia()."', ";
				$sSql .= " '".utf8_decode($this->getAlergiaDeQue())."', ";
				$sSql .= " '".$this->getDoencasPrevias()."', ";
				$sSql .= " '".utf8_decode($this->getDoencasPreviasQual())."', ";
				$sSql .= " '".$this->getCirurgiasPrevias()."', ";
				$sSql .= " '".utf8_decode($this->getCirurgiasPreviasQual())."', ";
				$sSql .= " '".$this->getHepatite()."', ";
				$sSql .= " '".$this->getDiabetis()."', ";
				$sSql .= " '".$this->getFebreReumatica()."', ";
				$sSql .= " '".$this->getDoencasArterial()."', ";
				$sSql .= " '".$this->getPressaoArterial()."', ";
				$sSql .= " '".$this->getAcidFraturasOdont()."', ";
				$sSql .= " '".utf8_decode($this->getAcidFraturasOdontQual())."', ";
				$sSql .= " '".$this->getDificAbertBoca()."', ";
				$sSql .= " '".$this->getMuitaSalivacao()."', ";
				$sSql .= " '".$this->getAnsiaVomito()."', ";
				$sSql .= " '".$this->getDorNasCostas()."', ";
				$sSql .= " '".$this->getTraumatismoDentario()."', ";
				$sSql .= " '".utf8_decode($this->getRecomendacaoMedica())."', ";
				$sSql .= " '".$this->getRestricaoMedicamentos()."', ";
				$sSql .= " '".utf8_decode($this->getRestricaoMedicamentosQual())."', ";
				$sSql .= " '".$this->getVisitaRegularDent()."', ";
				$sSql .= " ".$this->getUltimoTratOdont().", ";
				$sSql .= " '".utf8_decode($this->getInfAdicionalImportante())."') ";

				if(!$this->bExecute($sSql)) {
					$this->imprimeErro('Ocorreu um erro ao tentar inserir o objeto Anamnese');
					return false;
				} else {
					$this->setId(mysql_insert_id());
					return true;
				}
			} else {
				#ALTERAR UM ENDERECO NO BANCO DE DADOS
				$sSql = "UPDATE anamnese SET ";
				$sSql .= " id_pessoa = ".$this->getIdPessoa().", ";
				$sSql .= " boa_saude = '".$this->getBoaSaude()."', ";
				$sSql .= " boa_saude_porque = '".utf8_decode($this->getBoaSaudePorque())."', ";
				$sSql .= " visitas_freq_medico = '".$this->getVisitasFreqMedico()."', ";
				$sSql .= " visitas_freq_medico_motivo = '".utf8_decode($this->getVisitasFreqMedicoMotivo())."', ";
				$sSql .= " medicacao_rotina = '".$this->getMedicacaoRotina()."', ";
				$sSql .= " medicacao_rotina_qual = '".utf8_decode($this->getMedicacaoRotinaQual())."', ";
				$sSql .= " alergia = '".$this->getAlergia()."', ";
				$sSql .= " alergia_de_que = '".utf8_decode($this->getAlergiaDeQue())."', ";
				$sSql .= " doencas_previas = '".$this->getDoencasPrevias()."', ";
				$sSql .= " doencas_previas_qual = '".utf8_decode($this->getDoencasPreviasQual())."', ";
				$sSql .= " cirurgias_previas = '".$this->getCirurgiasPrevias()."', ";
				$sSql .= " cirurgias_previas_qual = '".utf8_decode($this->getCirurgiasPreviasQual())."', ";
				$sSql .= " hepatite = '".$this->getHepatite()."', ";
				$sSql .= " diabetis = '".$this->getDiabetis()."', ";
				$sSql .= " febre_reumatica = '".$this->getFebreReumatica()."', ";
				$sSql .= " doencas_arterial = '".$this->getDoencasArterial()."', ";
				$sSql .= " pressao_arterial = '".$this->getPressaoArterial()."', ";
				$sSql .= " acid_fraturas_odont = '".$this->getAcidFraturasOdont()."', ";
				$sSql .= " acid_fraturas_odont_qual = '".utf8_decode($this->getAcidFraturasOdontQual())."', ";
				$sSql .= " dific_abert_boca = '".$this->getDificAbertBoca()."', ";
				$sSql .= " muita_salivacao = '".$this->getMuitaSalivacao()."', ";
				$sSql .= " ansia_vomito = '".$this->getAnsiaVomito()."', ";
				$sSql .= " dor_nas_costas = '".$this->getDorNasCostas()."', ";
				$sSql .= " traumatismo_dentario = '".$this->getTraumatismoDentario()."', ";
				$sSql .= " recomendacao_medica = '".utf8_decode($this->getRecomendacaoMedica())."', ";
				$sSql .= " restricao_medicamentos = '".$this->getRestricaoMedicamentos()."', ";
				$sSql .= " restricao_medicamentos_qual = '".utf8_decode($this->getRestricaoMedicamentosQual())."', ";
				$sSql .= " visita_regular_dent = '".$this->getVisitaRegularDent()."', ";
				$sSql .= " ultimo_trat_odont = ".$this->getUltimoTratOdont().", ";
				$sSql .= " inf_adicional_importante = '".utf8_decode($this->getInfAdicionalImportante())."' ";						
				$sSql .= " WHERE id_anamnese=".$this->getId();

				if(!$this->bExecute($sSql)) {
					$this->imprimeErro('Ocorreu um erro ao tentar alterar registro de Anamnese');
					return false;
				} else {
					return true;
				}
				
			}
		}
		
		public function bDelete() {
			$sSql = "DELETE FROM anamnese WHERE id_anamnese=".$this->getId();

			if(!$this->bExecute($sSql)) {
				$this->imprimeErro('Ocorreu um erro ao tentar excluir o registro de Anamnese');
				return false;
			} else {
				return true;
			}
		}
		
		public function toString() {
			echo '### ANAMNESE ###<br>';
			echo 'ID................:'.$this->getId().'<br>';
			echo 'ID PESSOA.........:'.$this->getIdPessoa().'<br>';
			echo 'BOA SAUDE?........:'.$this->getBoaSaude().'<br>';
			echo 'BOA SAUDE PORQUE..:'.$this->getBoaSaudePorque().'<br>';
			echo 'VISITAS FREQ MED?.:'.$this->getVisitasFreqMedico().'<br>';
			echo 'VIS FREQ MED MOT..:'.$this->getVisitasFreqMedicoMotivo().'<br>';
			echo 'MEDICACAO ROTINA?.:'.$this->getMedicacaoRotina().'<br>';
			echo 'MEDICACAO ROT QUAL:'.$this->getMedicacaoRotinaQual().'<br>';
			echo 'ALERGIA?..........:'.$this->getAlergia().'<br>';
			echo 'ALERGIA DE QUE....:'.$this->getAlergiaDeQue().'<br>';
			echo 'DOENCAS PREVIAS?..:'.$this->getDoencasPrevias().'<br>';
			echo 'DOENC PREV QUAIS?.:'.$this->getDoencasPreviasQual().'<br>';
			echo 'CIRURG PREVIAS....:'.$this->getCirurgiasPrevias().'<br>';
			echo 'CIRURG PREV QUAL..:'.$this->getCirurgiasPreviasQual().'<br>';
			echo 'HEPATITE?.........:'.$this->getHepatite().'<br>';
			echo 'DIABETIS?.........:'.$this->getDiabetis().'<br>';
			echo 'FEBRE REUMATICA?..:'.$this->getFebreReumatica().'<br>';
			echo 'DOENCAS ARTERIAL?.:'.$this->getDoencasArterial().'<br>';
			echo 'PRESSAO ARTERIAL?.:'.$this->getPressaoArterial().'<br>';
			echo 'ACID FRAT ODONT?..:'.$this->getAcidFraturasOdont().'<br>';
			echo 'ACID FRAT OD QUAL.:'.$this->getAcidFraturasOdontQual().'<br>';
			echo 'DIF ABERT BOCA....:'.$this->getDificAbertBoca().'<br>';
			echo 'MUITA SALIVACAO...:'.$this->getMuitaSalivacao().'<br>';
			echo 'ANSIA VOMITO......:'.$this->getAnsiaVomito().'<br>';
			echo 'DOR NAS COSTAS....:'.$this->getDorNasCostas().'<br>';
			echo 'TRAUM DENT........:'.$this->getTraumatismoDentario().'<br>';
			echo 'RECOM MEDICA......:'.$this->getRecomendacaoMedica().'<br>';
			echo 'REST MEDICAMENTOS.:'.$this->getRestricaoMedicamentos().'<br>';
			echo 'REST MEDIC QUAL...:'.$this->getRestricaoMedicamentosQual().'<br>';
			echo 'VISITA REG DENT...:'.$this->getVisitaRegularDent().'<br>';
			echo 'ULTIMO TRAT ODONT.:'.$this->getUltimoTratOdont().'<br>';
			echo 'INF ADIC IMPORT...:'.$this->getInfAdicionalImportante().'<br>';
		}
			
	## MÉTODO GET's DA CLASSE ## 
		public function getId()	{ return $this->iId; }
		public function getIdPessoa() { return $this->sIdPessoa; }     
        public function getBoaSaude() { return $this->bBoaSaude; }
        public function getBoaSaudePorque() { return $this->sBoaSaudePorque; }
        public function getVisitasFreqMedico() { return $this->bVisitasFreqMedico; }
        public function getVisitasFreqMedicoMotivo() { return $this->sVisitasFreqMedicoMotivo; }
        public function getMedicacaoRotina() { return $this->bMedicacaoRotina; }
        public function getMedicacaoRotinaQual() { return $this->sMedicacaoRotinaQual; }
        public function getAlergia() { return $this->bAlergia; }
        public function getAlergiaDeQue() { return $this->sAlergiaDeQue; }
        public function getDoencasPrevias() { return $this->bDoencasPrevias; }
        public function getDoencasPreviasQual() { return $this->sDoencasPreviasQual; }
        public function getCirurgiasPrevias() { return $this->bCirurgiasPrevias; }
        public function getCirurgiasPreviasQual() { return $this->sCirurgiasPreviasQual; }
        public function getHepatite() { return $this->bHepatite; }
        public function getDiabetis() { return $this->bDiabetis; }
        public function getFebreReumatica() { return $this->bFebreReumatica; }
        public function getDoencasArterial() { return $this->bDoencasArterial; }
        public function getPressaoArterial() { return $this->bPressaoArterial; }
        public function getAcidFraturasOdont() { return $this->bAcidFraturasOdont; }
        public function getAcidFraturasOdontQual() { return $this->sAcidFraturasOdontQual; }
        public function getDificAbertBoca() { return $this->bDificAbertBoca; }
        public function getMuitaSalivacao() { return $this->bMuitaSalivacao; }
        public function getAnsiaVomito() { return $this->bAnsiaVomito; }
        public function getDorNasCostas() { return $this->bDorNasCostas; }
        public function getTraumatismoDentario() { return $this->bTraumatismoDentario; }
        public function getRecomendacaoMedica() { return $this->sRecomendacaoMedica; }
        public function getRestricaoMedicamentos() { return $this->bRestricaoMedicamentos; }
        public function getRestricaoMedicamentosQual() { return $this->sRestricaoMedicamentosQual; }
        public function getVisitaRegularDent() { return $this->bVisitaRegularDent; }
        public function getUltimoTratOdont() { return $this->iUltimoTratOdont; }
        public function getInfAdicionalImportante() { return $this->sInfAdicionalImportante; }
		
	## MÉTODO SET's DA CLASSE ## 
		public function setId($id) { $this->iId = $id; }
        public function setIdPessoa($sIdPessoa) { $this->sIdPessoa = $sIdPessoa; }
        public function setBoaSaude($bBoaSaude) { $this->bBoaSaude = $bBoaSaude; }
        public function setBoaSaudePorque($sBoaSaudePorque) { $this->sBoaSaudePorque = $sBoaSaudePorque; }
        public function setVisitasFreqMedico($bVisitasFreqMedico) { $this->bVisitasFreqMedico = $bVisitasFreqMedico; }
        public function setVisitasFreqMedicoMotivo($sVisitasFreqMedicoMotivo) { $this->sVisitasFreqMedicoMotivo = $sVisitasFreqMedicoMotivo; }
        public function setMedicacaoRotina($bMedicacaoRotina) { $this->bMedicacaoRotina = $bMedicacaoRotina; }
        public function setMedicacaoRotinaQual($sMedicacaoRotinaQual) { $this->sMedicacaoRotinaQual = $sMedicacaoRotinaQual; }
        public function setAlergia($bAlergia) { $this->bAlergia = $bAlergia; }
        public function setAlergiaDeQue($sAlergiaDeQue) { $this->sAlergiaDeQue = $sAlergiaDeQue; }
        public function setDoencasPrevias($bDoencasPrevias) { $this->bDoencasPrevias = $bDoencasPrevias; }
        public function setDoencasPreviasQual($sDoencasPreviasQual) { $this->sDoencasPreviasQual = $sDoencasPreviasQual; }
        public function setCirurgiasPrevias($bCirurgiasPrevias) { $this->bCirurgiasPrevias = $bCirurgiasPrevias; }
        public function setCirurgiasPreviasQual($sCirurgiasPreviasQual) { $this->sCirurgiasPreviasQual = $sCirurgiasPreviasQual; }
        public function setHepatite($bHepatite) { $this->bHepatite = $bHepatite; }
        public function setDiabetis($bDiabetis) { $this->bDiabetis = $bDiabetis; }
        public function setFebreReumatica($bFebreReumatica) { $this->bFebreReumatica = $bFebreReumatica; }
        public function setDoencasArterial($bDoencasArterial) { $this->bDoencasArterial = $bDoencasArterial; }
        public function setPressaoArterial($bPressaoArterial) { $this->bPressaoArterial = $bPressaoArterial; }
        public function setAcidFraturasOdont($bAcidFraturasOdont) { $this->bAcidFraturasOdont = $bAcidFraturasOdont; }
        public function setAcidFraturasOdontQual($sAcidFraturasOdontQual) { $this->sAcidFraturasOdontQual = $sAcidFraturasOdontQual; }
        public function setDificAbertBoca($bDificAbertBoca) { $this->bDificAbertBoca = $bDificAbertBoca; }
        public function setMuitaSalivacao($bMuitaSalivacao) { $this->bMuitaSalivacao = $bMuitaSalivacao; }
        public function setAnsiaVomito($bAnsiaVomito) { $this->bAnsiaVomito = $bAnsiaVomito; }
        public function setDorNasCostas($bDorNasCostas) { $this->bDorNasCostas = $bDorNasCostas; }
        public function setTraumatismoDentario($bTraumatismoDentario) { $this->bTraumatismoDentario = $bTraumatismoDentario; }
        public function setRecomendacaoMedica($sRecomendacaoMedica) { $this->sRecomendacaoMedica = $sRecomendacaoMedica; }
        public function setRestricaoMedicamentos($bRestricaoMedicamentos) { $this->bRestricaoMedicamentos = $bRestricaoMedicamentos; }
        public function setRestricaoMedicamentosQual($sRestricaoMedicamentosQual) { $this->sRestricaoMedicamentosQual = $sRestricaoMedicamentosQual; }
        public function setVisitaRegularDent($bVisitaRegularDent) { $this->bVisitaRegularDent = $bVisitaRegularDent; }
        public function setUltimoTratOdont($iUltimoTratOdont) { $this->iUltimoTratOdont = $iUltimoTratOdont; }
        public function setInfAdicionalImportante($sInfAdicionalImportante) { $this->sInfAdicionalImportante = $sInfAdicionalImportante; }		

		
	}
?>
