<?php
	class Imagem extends Persistencia
	{
	 	private $iId;
		private $iIdTratamento;
		private $iIndice;
		private $dData;
		private $sCaminho;
		private $sObs;
		
		public function __construct($imagem_id = 0) {
			parent::__construct();
		
			if($imagem_id != 0) {	
				$this->getImagemById($imagem_id);			
			}else{
				$this->iId = $imagem_id;
				$this->iIdTratamento = 0;
				$this->iIndice = 0;
				$this->dData = '';
				$this->sCaminho = '';
				$this->sObs = '';
			}
		}
		
		public function bFetchObject($sSql) {
			$this->bExecute($sSql);
			$this->bDados();
			
			$res = $this->getDbArrayDados();
			
			$this->setId(utf8_encode($res['id_imagem']));
			$this->setIdTratamento(utf8_encode($res['id_tratamento']));
			$this->setIndice(utf8_encode($res['indice']));
			$this->setData(utf8_encode($res['data']));
			$this->setCaminho(utf8_encode($res['caminho']));
			$this->setObs(utf8_encode($res['obs']));
		}
		
		public function getImagemById($imagem_id){
			$sSql = "SELECT * FROM imagem WHERE id_imagem=".$imagem_id;
			
			$this->bFetchObject($sSql);
		}
		
		public function bUpdate() {
			if(($this->getId()) == 0) {
				#INSERIR UM NOVO ENDERECO NO BANCO DE DADOS
				$sSql = "INSERT INTO imagem (id_tratamento, indice, data, caminho, obs) VALUES (";
				$sSql .= " '".utf8_decode($this->getIdTratamento())."', ";
				$sSql .= " '".utf8_decode($this->getIndice())."', ";
				$sSql .= " '".utf8_decode($this->getData())."', ";
				$sSql .= " '".utf8_decode($this->getCaminho())."', ";
				$sSql .= " '".utf8_decode($this->getObs())."' )";
				
				
				if(!$this->bExecute($sSql)) {
					$this->imprimeErro('Ocorreu um erro ao tentar inserir o objeto Imagem');
					return false;
				} else {
					$this->setId(mysql_insert_id());
					return true;
				}
			} else {
				#ALTERAR UM ENDERECO NO BANCO DE DADOS
				$sSql = "UPDATE consulta SET ";
				$sSql .= " id_tratamento = '".utf8_decode($this->getIdTratamento())."', ";
				$sSql .= " indice = '".utf8_decode($this->getIndice())."', ";
				$sSql .= " data = '".utf8_decode($this->getData())."', ";
				$sSql .= " caminho ='".utf8_decode($this->getCaminho())."', ";
				$sSql .= " obs = '".utf8_decode($this->getObs())."' ";
				$sSql .= " WHERE id_consulta=".$this->getId();
				
				if(!$this->bExecute($sSql)) {
					$this->imprimeErro('Ocorreu um erro ao tentar alterar registro de Imagem');
					return false;
				} else {
					return true;
				}
				
			}		
		}
		
		public function bDelete() {
			$sSql = "DELETE FROM imagem WHERE id_imagem=".$this->getId();

			if(!$this->bExecute($sSql)) {
				$this->imprimeErro('Ocorreu um erro ao tentar excluir o registro de Imagem');
				return false;
			} else {
				return true;
			}
		}
	
		public function toString() {
			echo '### IMAGEM ###<br>';
			echo 'ID.........:'.$this->iId.'<br>';
			echo 'TRATAMENTO.:'.$this->iIdTratamento.'<br>';
			echo 'INDICE......:'.$this->iIndice.'<br>';
			echo 'DATA.......:'.$this->dData.'<br>';
			echo 'OBS........:'.$this->sObs.'<br>';
		}
		
		#MÉTODO GET's DA CLASSE
		
		public function getId() {
			return $this->iId;
		}
				
		public function getIdTratamento() {
			return $this->iIdTratamento;
		}
		
		public function getIndice() {
			return $this->iIndice;
		}
		
		public function getData() {
			return $this->dData;
		}
		
		public function getCaminho() {
			return $this->sCaminho;
		}
		
		public function getObs() {
			return $this->sObs;
		}
		
		#MÉTODO SET's DA CLASSE
		
		public function setId($id) {
			$this->iId = $id;
		}

		public function setIdTratamento($id_tratamento) {
			$this->iIdTratamento = $id_tratamento;
		}
		
		public function setIndice($indice) {
			$this->iIndice = $indice;
		}
		
		public function setData($data) {
			$this->dData = $data;
		}
		
		public function setCaminho($caminho) {
			$this->sCaminho = $caminho;
		}
		
		public function setObs($obs) {
			$this->sObs = $obs;
		}
				
	}
?>
