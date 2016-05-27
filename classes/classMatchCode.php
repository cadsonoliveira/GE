<?php

    include_once("classPersistencia.php");

    class MatchCode extends Persistencia
	{
		private $iId;
		private $sDescricao;
		private $sTipo;
        private $iIdEspecialidade;

		public function __construct($match_code_id = 0){
			parent::__construct();
		
			if($match_code_id != 0) {
				$this->getMatchCodeById($match_code_id);			
			}else{
				$this->iId = $match_code_id;
				$this->sDescricao = '';
				$this->sTipo = '';
				$this->iIdEspecialidade='';
    		}
		
		}
		
		public function bFetchObject($sSql) {
			$this->bExecute($sSql);
			$this->bDados();
			
			$res = $this->getDbArrayDados();
			
			$this->setId(utf8_encode($res['id_match_code']));
			$this->setDescricao(utf8_encode($res['descricao']));
			$this->setTipo(utf8_encode($res['tipo']));
			$this->setIdEspecialidade(utf8_encode($res['id_especialidade']));
   		}
		
		public function getMatchCodeById($match_code_id){
			$sSql = "SELECT * FROM match_code WHERE id_match_code=".$match_code_id;
			
			$this->bFetchObject($sSql);
   		}
		
		public function bUpdate() {
			if(($this->getId()) == 0) {
				#INSERIR UM NOVO ENDERECO NO BANCO DE DADOS
				$sSql = "INSERT INTO match_code (descricao, tipo,id_especialidade) VALUES (";
				$sSql .= " '".utf8_decode($this->getDescricao())."', ";
				$sSql .= " '".utf8_decode($this->getTipo())."', ";
				$sSql .= " '".utf8_decode($this->getIdEspecialidade())."') ";

				
				if(!$this->bExecute($sSql)) {
					$this->imprimeErro('Ocorreu um erro ao tentar inserir o objeto Match Code');
					return false;
				} else {
					$this->setId(mysql_insert_id());
					return true;
				}
			} else {
				#ALTERAR BANCO DE DADOS
				$sSql = "UPDATE match_code SET ";
				$sSql .= " descricao = '".utf8_decode($this->getDescricao())."', ";
				$sSql .= " tipo = '".utf8_decode($this->getTipo())."', ";
				$sSql .= " id_especialidade = '".utf8_decode($this->getIdEspecialidade())."' ";
    			$sSql .= " WHERE id_match_code=".$this->getId();
				
				if(!$this->bExecute($sSql)) {
					$this->imprimeErro('Ocorreu um erro ao tentar alterar registro de Match Code');
					return false;
				} else {
					return true;
				}
				
			}		
		}
		
		public function bDelete() {
			$sSql = "DELETE FROM match_code WHERE id_match_code=".$this->getId();

			if(!$this->bExecute($sSql)) {
				$this->imprimeErro('Ocorreu um erro ao tentar excluir o registro de Match Code');
				return false;
			} else {
				return true;
			}
		}
		
		public function toString() {
			echo '### MATCH CODE ###<br>';
			echo 'ID.........:'.$this->iId.'<br>';
			echo 'DESCRICAO.......:'.$this->sDescricao.'<br>';
			echo 'TIPO..:'.$this->sTipo.'<br>';
			echo 'ID_ESPECIALIDADE..:'.$this->iIdEspecialidade.'<br>';
  		}
		
		#MÉTODOS GET's DA CLASSE
		
		public function getId() {
			return $this->iId;
		}
		
		public function getDescricao() {
			return $this->sDescricao;
		}
		
		public function getTipo() {
			return $this->sTipo;
		}
		
		public function getIdEspecialidade() {
			return $this->iIdEspecialidade;
		}
		
  		#MÉTODOS SET's DA CLASSE
		
		public function setId($codigo) {
			$this->iId = $codigo;
		}
		
		public function setDescricao($descricao) {
			$this->sDescricao = $descricao;
		}
		
		public function setTipo($tipo) {
			$this->sTipo = $tipo;
		}

        public function setIdEspecialidade($id_especialidade) {
			$this->iIdEspecialidade = $id_especialidade;
		}
		
 	}
?>
