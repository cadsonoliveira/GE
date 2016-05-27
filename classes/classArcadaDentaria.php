<?php
	
	include_once("classPersistencia.php");
 	
	class ArcadaDentaria extends Persistencia
	{
		private $iId;
		private $iIdPessoa;
		private $cDente;
		private $sObs;
		
		public function __construct($arcada_dentaria_id = 0) {
			parent::__construct();

			if($arcada_dentaria_id != 0) {
				$this->getArcadaDentariaById($arcada_dentaria_id);
			} else {
				$this->iId = $arcada_dentaria_id;
				$this->iIdPessoa = NULL;
				$this->cDente = NULL;
				$this->sObs = "";
			}
		}
		
		public function bFetchObject($sql) {
			$this->bExecute($sql);
			$this->bDados();
			
			$res = $this->getDbArrayDados();
					
			$this->setId($res['id_arcada_dentaria']);
			$this->setIdPessoa($res['id_pessoa']);
			$this->setDente($res['dente']);
			$this->setObs($res['obs']);
			
		}
			
		public function getArcadaDentariaById($arcada_dentaria_id) {
			$sSql = "SELECT * FROM arcada_dentaria WHERE id_arcada_dentaria=".$arcada_dentaria_id;
			$this->bFetchObject($sSql);
		}
		
		public function bUpdate() {
			if(($this->getId()) == 0) {
				#INSERIR UM NOVO ENDERECO NO BANCO DE DADOS
				$sSql = "INSERT INTO arcada_dentaria (id_pessoa, dente, obs) VALUES (";
				$sSql .= " ".$this->getIdPessoa().", ";
				$sSql .= " '".$this->getDente()."', ";
				$sSql .= " '".$this->getObs()."') ";

				if(!$this->bExecute($sSql)) {
					$this->imprimeErro('Ocorreu um erro ao tentar inserir o objeto Arcada Dentaria');
					return false;
				} else {
					$this->setId(mysql_insert_id());
					return true;
				}
			} else {
				#ALTERAR UM ENDERECO NO BANCO DE DADOS
				$sSql = "UPDATE arcada_dentaria SET ";
				$sSql .= " id_pessoa = ".$this->getIdPessoa().", ";
				$sSql .= " dente = '".$this->getDente()."', ";
				$sSql .= " obs = '".$this->getObs()."', ";
				$sSql .= " WHERE id_arcada_dentaria = ".$this->getId();

				if(!$this->bExecute($sSql)) {
					$this->imprimeErro('Ocorreu um erro ao tentar alterar registro de Arcada Dentaria');
					return false;
				} else {
					return true;
				}
			}
		}
		
		public function bDelete() {
			$sSql = "DELETE FROM arcada_dentaria WHERE id_arcada_dentaria=".$this->getId();

			if(!$this->bExecute($sSql)) {
				$this->imprimeErro('Ocorreu um erro ao tentar excluir o registro de Arcada Dentaria');
				return false;
			} else {
				return true;
			}
		}
		
		public function toString() {
			echo '### ARCADA DENTARIA ###<br>';
			echo 'ID...........:'.$this->iId.'<br>';
			echo 'ID_PESSOA....:'.$this->iIdPessoa.'<br>';
			echo 'DENTE........:'.$this->cDente.'<br>';
			echo 'OBS..........:'.$this->sObs.'<br>';
		}
			
	## MÉTODO GET's DA CLASSE ## 
		public function getId()	{
			return $this->iId;
		}
		
		public function getIdPessoa() {
			return $this->iIdPessoa;
		}
		
		public function getDente() {
			return $this->cDente;
		}
		
		public function getObs() {
			return $this->sObs;
		}


	## MÉTODO SET's DA CLASSE ## 
		public function setId($id) {
			$this->iId = $id;
		}
		public function setIdPessoa($id_pessoa){
			$this->iIdPessoa = $id_pessoa;
		}
		public function setDente($dente) {
			$this->cDente= $dente;
		}
		public function setObs($obs) {
			$this->sObs = $obs;
		}
	}
?>