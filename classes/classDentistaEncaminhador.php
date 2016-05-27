<?php

	class DentistaEncaminhador extends Persistencia
	{
		private $iId;
		private $sCRO;
		private $sNome;
		private $iIdContato;
		
		public function __construct($dentista_encaminhador_id = 0) {
			parent::__construct();
			
			if($dentista_encaminhador_id != 0) {			
				$this->getDentistaEncaminhadorById($dentista_encaminhador_id);		
			}else{
				$this->iId = $dentista_encaminhador_id;
				$this->sCRO = '';
				$this->sNome = '';
				$this->iIdContato = 0;
			}
		}
		
		public function bFetchObject($sSql) {
			$this->bExecute($sSql);
			$this->bDados();
			
			$res = $this->getDbArrayDados();
			
			$this->setId(utf8_encode($res['id_dentista_encaminhador']));
			$this->setCRO(utf8_encode($res['CRO']));
			$this->setNome(utf8_encode($res['nome']));
			$this->setIDContato(utf8_encode($res['id_contato']));
		}
		
		public function getDentistaEncaminhadorById($dentista_encaminhador_id){
			$sSql = "SELECT * FROM dentista_encaminhador WHERE id_dentista_encaminhador=".$dentista_encaminhador_id;
			
			$this->bFetchObject($sSql);
		}
		
		public function bUpdate() {
			if(($this->getId()) == 0) {
				#INSERIR UM NOVO ENDERECO NO BANCO DE DADOS
				$sSql = "INSERT INTO dentista_encaminhador (CRO, nome, id_contato) VALUES (";
				$sSql .= " '".utf8_decode($this->getCRO())."', ";
				$sSql .= " '".utf8_decode($this->getNome())."', ";
				$sSql .= " ".utf8_decode($this->getIdContato())." )";
				
				if(!$this->bExecute($sSql)) {
					$this->imprimeErro('Ocorreu um erro ao tentar inserir o objeto Dentista Encaminhador');
					return false;
				} else {
					$this->setId(mysql_insert_id());
					return true;
				}
			} else {
				#ALTERAR UM ENDERECO NO BANCO DE DADOS
				$sSql = "UPDATE convenio SET ";
				$sSql .= " CRO = '".utf8_decode($this->getCRO())."', ";
				$sSql .= " nome = '".utf8_decode($this->getNome())."', ";
				$sSql .= " id_contato = ".utf8_decode($this->getIdContato())." ";
				$sSql .= " WHERE id_convenio=".$this->getId();
				
				if(!$this->bExecute($sSql)) {
					$this->imprimeErro('Ocorreu um erro ao tentar alterar registro de Dentista Encaminhador');
					return false;
				} else {
					return true;
				}
				
			}		
		}
		
		public function bDelete() {
			$sSql = "DELETE FROM dentista_encaminhador WHERE id_dentista_encaminhador=".$this->getId();

			if(!$this->bExecute($sSql)) {
				$this->imprimeErro('Ocorreu um erro ao tentar excluir o registro de Dentista Encaminhador');
				return false;
			} else {
				return true;
			}
		}
		
		public function toString() {
			echo '### DENTISTA ENCAMINHADOR ###<br>';
			echo 'ID.........:'.$this->iId.'<br>';
			echo 'CRO........:'.$this->sCRO.'<br>';
			echo 'NOME.......:'.$this->sNome.'<br>';
			echo 'CONTATO....:'.$this->iIdContato.'<br>';
		}
		
		#MÉTODOS GET's DA CLASSE
		public function getId() {
			return $this->iId;
		}		
		
		public function getCRO() {
			return $this->sCRO;
		}
		
		public function getNome() {
			return $this->sNome;
		}
		
		public function getIdContato() {
			return $this->iIdContato;
		}
		
		#MÉTODOS SET's DA CLASSE		
		public function setId($id) {
			$this->iId = $id;
		}
		
		public function setCRO($CRO) {
			$this->sCRO = $CRO;
		}
		
		public function setNome($nome) {
			$this->sNome = $nome;
		}
		
		public function setIdContato($contato) {
			$this->iIdContato = $contato;
		}
		
	}
?>
