<?php

	include_once("classPersistencia.php");
	
	class Tratamento extends Persistencia
	{	
		private $iId;
		private $iIdPessoa;
		private $dData_inic;
		private $dData_term;
		private $iStatus;
		private $iIdMatchCode;
		private $sDescricao;
		private $sDente;
		private $iSucesso;

		public function __construct($tratamento_id = 0) {
			parent::__construct();
		
			if($tratamento_id != 0) {
				$this->getTratamentoById($tratamento_id);				
			}else{
				$this->iId = $tratamento_id;
				$this->iIdPessoa = 0;
				$this->iStatus = 0;
				$this->iIdMatchCode = 0;
				$this->sDescricao = '';
				$this->iSucesso = 2;
    			$this->dData_term = NULL;
			}
		
		}
		
		public function bFetchObject($sSql) {
			$this->bExecute($sSql);
			$this->bDados();
			
			$res = $this->getDbArrayDados();
			
			$this->setId(utf8_encode($res['id_tratamento']));
			$this->setIdPessoa($res['id_pessoa']);
			$this->setDataInicio(utf8_encode($res['data_inic']));
			$this->setDataTermino(utf8_encode($res['data_term']));
			$this->setStatus(utf8_encode($res['status']));
			$this->setIdMatchCode(utf8_encode($res['id_match_code']));
			$this->setDescricao(utf8_encode($res['descricao']));
			$this->setDente(utf8_encode($res['dente']));
			$this->setSucesso(utf8_encode($res['sucesso']));
  		}
		
		public function getTratamentoById($tratamento_id){
			$sSql = "SELECT * FROM tratamento WHERE id_tratamento=".$tratamento_id;
			
			$this->bFetchObject($sSql);
		}
		
		public function bUpdate() {
			if(($this->getId()) == 0) {
				#INSERIR UM NOVO ENDERECO NO BANCO DE DADOS
				$sSql = "INSERT INTO tratamento (dente, id_pessoa, data_inic, data_term, status, id_match_code, descricao, sucesso) VALUES (";
				$sSql .= " '".utf8_decode($this->getDente())."', ";
				$sSql .= " ".utf8_decode($this->getIdPessoa()).", ";
				$sSql .= " '".utf8_decode($this->getDataInicio())."', ";
				if($this->getDataTermino() == NULL)
				{
					$sSql .= " NULL, ";
				} else {
					$sSql .= " '".utf8_decode($this->getDataTermino())."', ";
				}
				
				$sSql .= " ".utf8_decode($this->getStatus()).", ";
				$sSql .= " ".utf8_decode($this->getIdMatchCode()).", ";
				$sSql .= " '".utf8_decode($this->getDescricao())."', ";
				$sSql .= " ".utf8_decode($this->getSucesso()).") ";

				if(!$this->bExecute($sSql)) {
					$this->imprimeErro('Ocorreu um erro ao tentar inserir o objeto Tratamento');
					return false;
				} else {
					$this->setId(mysql_insert_id());
					return true;
				}
			} else {
				#ALTERAR UM ENDERECO NO BANCO DE DADOS
				$sSql = "UPDATE tratamento SET ";
				$sSql .= " dente = '".utf8_decode($this->getDente())."', ";
				$sSql .= " id_pessoa = '".$this->getIdPessoa()."', ";
				$sSql .= " data_inic = '".utf8_decode($this->getDataInicio())."', ";
				if($this->getDataTermino() != '')
					$sSql .= " data_term = '".utf8_decode($this->getDataTermino())."', ";
				$sSql .= " status = '".utf8_decode($this->getStatus())."', ";
				$sSql .= " id_match_code = '".utf8_decode($this->getIdMatchCode())."', ";
				$sSql .= " descricao = '".utf8_decode($this->getDescricao())."', ";
				$sSql .= " sucesso = '".utf8_decode($this->getSucesso())."' ";
    			$sSql .= " WHERE id_tratamento=".$this->getId();
				
				if(!$this->bExecute($sSql)) {
					$this->imprimeErro('Ocorreu um erro ao tentar alterar registro de Tratamento');
					return false;
				} else {
					return true;
				}
				
			}		
		}
		
		public function bDelete() {
			$sSql = "DELETE FROM tratamento WHERE id_tratamento=".$this->getId();

			if(!$this->bExecute($sSql)) {
				$this->imprimeErro('Ocorreu um erro ao tentar excluir o registro de Tratamento');
				return false;
			} else {
				return true;
			}
		}
			
		
		public function toString() {
			echo '### TRATAMENTO ###<br>';
			echo 'ID............:'.$this->iId.'<br>';
			echo 'PACIENTE......:'.$this->iIdPessoa.'<br>';
			echo 'DATA DE INICIO:'.$this->dData_inic.'<br>';
			echo 'DATA DE TERM..:'.$this->dData_term.'<br>';
			echo 'STATUS........:'.$this->iStatus.'<br>';
			echo 'ID MATCHCODE..:'.$this->iIdMatchCode.'<br>';
			echo 'DESCRICAO.....:'.$this->sDescricao.'<br>';
			echo 'DENTE.........:'.$this->sDente.'<br>';
			echo 'SUCESSO.......:'.$this->iSucesso.'<br>';
		}
		
		#MÉTODOS GET's DA CLASSE
		public function getId() {
			return $this->iId;
		}
				
		public function getIdPessoa() {
			return $this->iIdPessoa;
		}
		
		public function getDataInicio() {
			return $this->dData_inic;
		}
		
		public function getDataTermino() {
			return $this->dData_term;
		}
		
		public function getStatus() {
			return $this->iStatus;
		}
		
		public function getIdMatchCode() {
			return $this->iIdMatchCode;
		}
		
		public function getDescricao() {
			return $this->sDescricao;
		}
		
		public function getDente() {
			return $this->sDente;
		}

		public function getSucesso() {
			return $this->iSucesso;
		}
		
 		#MÉTODOS SET's DA CLASSE
		
		public function setId($id) {
			$this->iId = $id;
		}
		
		public function setIdPessoa($pessoa_id) {
			$this->iIdPessoa = $pessoa_id;
		}
		
		public function setDataInicio($data_de_inicio) {
			$this->dData_inic = $data_de_inicio;
		}
		
		public function setDataTermino($data_de_termino) {
			$this->dData_term = $data_de_termino;
		}
		
		public function setStatus($status) {
			$this->iStatus = $status;
		}
		
		public function setIdMatchCode($match_code_id) {
			$this->iIdMatchCode = $match_code_id;
		}
		
		public function setDescricao($descricao) {
			$this->sDescricao = $descricao;
		}
		
		public function setDente($dente){
			$this->sDente = $dente;
		}
		
		public function setSucesso($sucesso) {
			$this->iSucesso = $sucesso;
		}

	}		
		
?>
