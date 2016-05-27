<?php

	include_once("classPessoa.php");
	
	class Paciente extends Pessoa
	{
		private $iIdPlanoSaude;
		private $iStatus;
		private $sObs;
		private $iIdDentistaEncaminhador;
  	private $dDataCadastro;
		private $sNumCarteira;
		private $sValidadeCarteira;
		private $sCaminhoFoto;
		
		public function __construct($pessoa_id = 0){
			parent::__construct($pessoa_id);
			if($pessoa_id != 0)
			{
				$this->getPacienteById($pessoa_id);
			} else {
				$this->iIdPlanoSaude = 0;
				$this->iStatus = 0;
				$this->sObs = "";
				$this->iIdDentistaEncaminhador = 0;
				$this->dDataCadastro = NULL;
				$this->sNumCarteira = NULL;
				$this->sValidadeCarteira = NULL;
				$this->sCaminhoFoto = "";		
			}

		}
		
		public function getPacienteById($pessoa_id) {
			$sSql = "SELECT * FROM pessoa WHERE id_pessoa=".$pessoa_id;
			parent::bFetchObject($sSql);
			$sSql = "SELECT * FROM paciente WHERE id_pessoa=".$pessoa_id;
			$this->bFetchObject($sSql);
		}
		
		public function bFetchObject($sql) {
			$this->bExecute($sql);
			$this->bDados();
			
			$res = $this->getDbArrayDados();
			if(isset($res['status'])) {
				$this->setIdPlanoSaude(utf8_encode($res['id_plano_saude']));
				$this->setStatus(utf8_encode($res['status']));
				$this->setObs($res['obs']);
				$this->setIdDentistaEncaminhador($res['id_dentista_encaminhador']);
				$this->setDataCadastro($res['data_cadastro']);
				$this->setNumCarteira($res['num_carteira_convenio']);
				$this->setValidadeCarteira($res['validade_carteira']);
				$this->setCaminhoFoto($res['caminho_foto']);
			}
		}
		
		public function bUpdate() {
			if(($this->getId()) == 0) {
				parent::bUpdate();
				#$this->setId(mysql_insert_id());
				#INSERIR UM NOVO USUARIO NO BANCO DE DADOS
				$sSql = "INSERT INTO paciente (id_pessoa, id_plano_saude, status, obs, data_cadastro, num_carteira_convenio, validade_carteira, caminho_foto) VALUES (";
				$sSql .= " ".utf8_decode($this->getId()).", ";
				$sSql .= " ".utf8_decode($this->getIdPlanoSaude()).", ";
				$sSql .= " ".utf8_decode($this->getStatus()).", ";
				$sSql .= " '".utf8_decode($this->getObs())."', ";
				$sSql .= " '".utf8_decode($this->getDataCadastro())."', ";
				$sSql .= " '".utf8_decode($this->getNumCarteira())."', ";
				$sSql .= " '".utf8_decode($this->getValidadeCarteira())."', ";
				$sSql .= " '".utf8_decode($this->getCaminhoFoto())."') ";

				if(!$this->bExecute($sSql)) {
					$this->imprimeErro('Ocorreu um erro ao tentar inserir o objeto Paciente');
					return false;
				} else {
					return true;
				}
			} else {
				parent::bUpdate();
				$sSql = "UPDATE paciente SET ";

				if($this->getDataCadastro() != NULL)
					$sSql .= " data_cadastro = '".utf8_decode($this->getDataCadastro())."', ";
				if($this->getIdPlanoSaude() != NULL)
					$sSql .= " id_plano_saude = ".utf8_decode($this->getIdPlanoSaude()).", ";
				if($this->getValidadeCarteira() != NULL)
					$sSql .= " validade_carteira = '".utf8_decode($this->getValidadeCarteira())."', ";
				if(($this->getIdDentistaEncaminhador() != NULL) && ($this->getIdDentistaEncaminhador() != ""))
					$sSql .= " id_dentista_encaminhador = ".utf8_decode($this->getIdDentistaEncaminhador()).", ";

				$sSql .= " id_pessoa = ".utf8_decode($this->getId()).", ";
				$sSql .= " status = ".utf8_decode($this->getStatus()).", ";
				$sSql .= " obs = '".utf8_decode($this->getObs())."', ";
				$sSql .= " caminho_foto = '".utf8_decode($this->getCaminhoFoto())."', ";
				$sSql .= " num_carteira_convenio = '".utf8_decode($this->getNumCarteira())."' ";

				$sSql .= " WHERE id_pessoa=".$this->getId();

				if(!$this->bExecute($sSql)) {
					$this->imprimeErro('Ocorreu um erro ao tentar alterar registro de Paciente');
					return false;
				} else {
					return true;
				}
				
			}
		}
		
		public function bDelete() {
			$sSqlTratamento = "UPDATE tratamento SET id_pessoa=NULL WHERE id_pessoa=".$this->getId();
			if($this->bExecute($sSqlTratamento)) {
				$sSql = "DELETE FROM paciente WHERE id_pessoa=".$this->getId();
				if(!$this->bExecute($sSql)) {
					$this->imprimeErro('Ocorreu um erro ao tentar excluir o registro de Paciente');
				} else {
					if(parent::bDelete()) {
						return true;
					}
				}
			} else {
				$this->imprimeErro('Ocorreu um erro ao tentar excluir os tratamentos deste Paciente');
			}
			return false;
		}
				
		public function toString() {
			parent::toString();
			echo '### PACIENTE ###<br>';
			echo 'ID PL SAUD:'.$this->iIdPlanoSaude.'<br>';
			echo 'STATUS....:'.$this->iStatus.'<br>';
			echo 'OBS.......:'.$this->sObs.'<br>';
			echo 'DATA CAD..:'.$this->dDataCadastro.'<br>';
			echo 'NUM CART..:'.$this->sNumCarteira.'<br>';
			echo 'VALID CART:'.$this->dValidadeCarteira.'<br>';
			echo 'CAMIN.FOTO:'.$this->sCaminhoFoto.'<br>';
			echo 'ID DE INDI:'.$this->iIdDentistaEncaminhador.'<br>';
		}
		## M�TODO GET's DA CLASSE ## 
		public function getIdPlanoSaude() {
			return $this->iIdPlanoSaude;
		}
		public function getStatus() {
			return $this->iStatus;
		}
		public function getObs() {
			return $this->sObs;
		}
		public function getIdDentistaEncaminhador() {
			return $this->iIdDentistaEncaminhador;
		}
		public function getDataCadastro() {
			return $this->dDataCadastro;
		}
		public function getNumCarteira() {
			return $this->sNumCarteira;
		}
		public function getValidadeCarteira() {
			return $this->sValidadeCarteira;
		}		
		public function getCaminhoFoto() {
			return $this->sCaminhoFoto;
		}		

		## M�TODO SET's DA CLASSE ## 
		public function setIdPlanoSaude($id_plano_saude) {
			$this->iIdPlanoSaude = $id_plano_saude;
		}
		public function setStatus($status) {
			$this->iStatus = $status;
		}
		public function setObs($obs) {
			$this->sObs = $obs;
		}
		public function setIdDentistaEncaminhador($id_dentista_encaminhador) {
			$this->iIdDentistaEncaminhador = $id_dentista_encaminhador;
		}
		public function setDataCadastro($data) {
			$this->dDataCadastro = $data;
		}
		public function setNumCarteira($num_carteira) {
			$this->sNumCarteira = $num_carteira;
		}
		public function setValidadeCarteira($validade) {
			$this->sValidadeCarteira = $validade;
		}
		public function setCaminhoFoto($foto_img) {
			$this->sCaminhoFoto = $foto_img;
		}

	}
?>
