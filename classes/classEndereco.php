<?php
	
	include_once("classPersistencia.php");
	
	class Endereco extends Persistencia
	{
		private $iId;
		private $sLogradouro;
		private $iNumero;
		private $sComplemento;
		private $sBairro;
		private $sCidade;
		private $sSiglaEstado;
		private $sCep;
		
		public function __construct( $endereco_id = 0 ) {
			parent::__construct();

			if($endereco_id != 0) {
				$this->getEnderecoById($endereco_id);
			} else {
				$this->iId = $endereco_id;
				$this->sLogradouro = "";
				$this->iNumero = "";
				$this->sComplemento= "";
				$this->sBairro = "";
				$this->sCidade = "";
				$this->sSiglaEstado = "";
				$this->sCep = "";
			}
		}
		
		public function bFetchObject($sql) {
			$this->bExecute($sql);
			$this->bDados();
			
			$res = $this->getDbArrayDados();
	
			$this->setId(utf8_encode($res['id_endereco']));
			$this->setLogradouro(utf8_encode($res['logradouro']));
			$this->setNumero(utf8_encode($res['numero']));
			$this->setComplemento(utf8_encode($res['complemento']));
			$this->setBairro(utf8_encode($res['bairro']));
			$this->setCidade(utf8_encode($res['cidade']));
			$this->setSiglaEstado(utf8_encode($res['sigla_estado']));
			$this->setCep(utf8_encode($res['cep']));
			
		}

		public function getEnderecoById($endereco_id) {
			$sSql = "SELECT * FROM endereco WHERE id_endereco=".$endereco_id;
			$this->bFetchObject($sSql);
		}
		
		public function bUpdate() {
			if(($this->getId()) == 0) {
				#INSERIR UM NOVO ENDERECO NO BANCO DE DADOS
				$sSql = "INSERT INTO endereco (logradouro, numero, complemento, bairro, cidade, sigla_estado, cep) VALUES (";
				$sSql .= " '".utf8_decode($this->getLogradouro())."', ";
				$sSql .= " '".utf8_decode($this->getNumero())."', ";
				$sSql .= " '".utf8_decode($this->getComplemento())."', ";
				$sSql .= " '".utf8_decode($this->getBairro())."', ";
				$sSql .= " '".utf8_decode($this->getCidade())."', ";
				$sSql .= " '".utf8_decode($this->getSiglaEstado())."',";
				$sSql .= " '".utf8_decode($this->getCep())."' )";
				
				if(!$this->bExecute($sSql)) {
					$this->imprimeErro('Ocorreu um erro ao tentar inserir o objeto Endereco');
					return false;
				} else {
					$this->setId(mysql_insert_id());
					return true;
				}
			} else {
				#ALTERAR UM ENDERECO NO BANCO DE DADOS
				$sSql = "UPDATE endereco SET ";
				$sSql .= " logradouro = '".utf8_decode($this->getLogradouro())."', ";
				$sSql .= " numero = '".utf8_decode($this->getNumero())."', ";
				$sSql .= " complemento = '".utf8_decode($this->getComplemento())."', ";
				$sSql .= " bairro = '".utf8_decode($this->getBairro())."', ";
				$sSql .= " cidade = '".utf8_decode($this->getCidade())."', ";
				$sSql .= " sigla_estado = '".utf8_decode($this->getSiglaEstado())."', ";
				$sSql .= " cep = '".utf8_decode($this->getCep())."' ";
				$sSql .= " WHERE id_endereco=".$this->getId();

				if(!$this->bExecute($sSql)) {
					$this->imprimeErro('Ocorreu um erro ao tentar alterar registro de Endereco');
					return false;
				} else {
					return true;
				}
				
			}
		}
		
		public function bDelete() {
			$sSql = "DELETE FROM endereco WHERE id_endereco=".$this->getId();
			if(!$this->bExecute($sSql)) {
			$this->imprimeErro('Ocorreu um erro ao tentar excluir o registro de Endreco');
				return false;
			} else {
				return true;
			}
		}
		
		public function toString() {
			echo '### ENDERECO ###<br>';
			echo 'ID...........:'.$this->iId.'<br>';
			echo 'LOGRADOURO...:'.$this->sLogradouro.'<br>';
			echo 'NUMERO.......:'.$this->iNumero.'<br>';
			echo 'COMPLEMENTO..:'.$this->sComplemento.'<br>';
			echo 'BAIRRO.......:'.$this->sBairro.'<br>';
			echo 'CIDADE.......:'.$this->sCidade.'<br>';
			echo 'ESTADO.......:'.$this->sSiglaEstado.'<br>';
			echo 'CEP..........:'.$this->sCep.'<br>';
		}
		
	
	## MÉTODO GET's DA CLASSE ## 
		public function getId() {
			return $this->iId;
		}
		public function getLogradouro() {
			return $this->sLogradouro;
		}
		public function getNumero() {
			return $this->iNumero;
		}
		public function getComplemento() {
			return $this->sComplemento;
		}
		public function getBairro() {
			return $this->sBairro;
		}
		public function getCidade() {
			return $this->sCidade;
		}
		public function getSiglaEstado() {
			return $this->sSiglaEstado;
		}
		public function getCep() {
			return $this->sCep;
		}
		
	## MÉTODO SET's DA CLASSE ## 
		public function setId($id) {
			$this->iId = $id;
		}
		public function setLogradouro($logradouro) {
			$this->sLogradouro = $logradouro;
		}
		public function setNumero($numero) {
			$this->iNumero = $numero;
		}
		public function setComplemento($complemento) {
			$this->sComplemento = $complemento;
		}
		public function setBairro($bairro) {
			$this->sBairro = $bairro;
		}
		public function setCidade($cidade) {
			$this->sCidade = $cidade;
		}
		public function setSiglaEstado($sigla_estado) {
			$this->sSiglaEstado = $sigla_estado;
		}
		public function setCep($cep) {
			$this->sCep = $cep;
		}
	}
?>