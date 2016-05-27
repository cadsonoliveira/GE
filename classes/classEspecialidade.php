<?php

	include_once("classPersistencia.php");

	class Especialidade extends Persistencia
	{
		private $iId;
  		private $sDescricao;

		public function __construct($especialidade_id = 0) {
			parent::__construct();

			if($especialidade_id != 0) {
				$this->getEspecialidadeById($especialidade_id);
			} else {
				$this->iId = $especialidade_id;
    			$this->sDescricao = "";
			}
		}

		public function bFetchObject($sql) {
			$this->bExecute($sql);
			$this->bDados();

			$res = $this->getDbArrayDados();

			$this->setId(utf8_encode($res['id_especialidade']));
  			$this->setDescricao(utf8_encode($res['descricao']));
		}

		public function getEspecialidadeById($especialidade_id) {
			$sSql= "SELECT * FROM especialidade WHERE id_especialidade=".$especialidade_id;

			$this->bFetchObject($sSql);
		}

		public function bUpdate() {
			if(($this->getId()) == 0) {
				#INSERIR UM NOVA ESPECIALIDADE   NO BANCO DE DADOS
				$sSql = "INSERT INTO especialidade (descricao) VALUES (";
    			$sSql .= " '".utf8_decode($this->getDescricao())."' )";

				if(!$this->bExecute($sSql)) {
					$this->imprimeErro('Ocorreu um erro ao tentar inserir o objeto Especialidade');
					return false;
				} else {
					$this->setId(mysql_insert_id());
					return true;
				}
			} else {
				#ALTERAR UM ENDERECO NO BANCO DE DADOS
				$sSql = "UPDATE especialidade SET ";
    			$sSql .= " descricao = '".utf8_decode($this->getDescricao())."' ";
				$sSql .= " WHERE id_especialidade=".$this->getId();

				if(!$this->bExecute($sSql)) {
					$this->imprimeErro('Ocorreu um erro ao tentar alterar registro de Especialidade');
					return false;
				} else {
					return true;
				}

			}
		}

		public function bDelete() {
			$sSql = "DELETE FROM especialidade WHERE id_especialidade=".$this->getId();

			if(!$this->bExecute($sSql)) {
				$this->imprimeErro('Ocorreu um erro ao tentar excluir o registro de Especialidade');
				return false;
			} else {
				return true;
			}
		}

		public function toString() {
			echo '### CONTATO ###<br>';
			echo 'ID...........:'.$this->iId.'<br>';
			echo 'DESCRICAO.......:'.$this->sDescricao.'<br>';
   		}

	## M�TODO GET's DA CLASSE ##
		public function getId()	{
			return $this->iId;
		}
		public function getDescricao() {
			return $this->sDescricao;
		}

	## M�TODO SET's DA CLASSE ##
		public function setId($id)	{
			$this->iId = $id;
		}
		public function setDescricao($descricao) {
			$this->sDescricao = $descricao;
		}
  	}
?>
