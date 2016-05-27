<?php
	
	include_once("classPersistencia.php");
 	
	class Contato extends Persistencia
	{
		private $iId;
		private $sTelefoneFixo;
		private $sTelefoneComercial;
		private $sTelefoneCelular;
		private $sEmail;
		
		public function __construct($contato_id = 0) {
			parent::__construct();

			if($contato_id != 0) {
				$this->getContatoById($contato_id);
			} else {
				$this->iId = $contato_id;
				$this->sTelefoneFixo = "";
				$this->sTelefoneComercial = "";
				$this->sTelefoneCelular = "";
				$this->sEmail = "";
			}
		}
		
		public function bFetchObject($sql) {
			$this->bExecute($sql);
			$this->bDados();
			
			$res = $this->getDbArrayDados();
					
			$this->setId($res['id_contato']);
			$this->setTelefoneFixo($res['tel_fixo']);
			$this->setTelefoneComercial($res['tel_comercial']);
			$this->setTelefoneCelular($res['tel_celular']);
			$this->setEmail($res['email']);
			
		}
			
		public function getContatoById($contato_id) {
			$sSql = "SELECT * FROM contato WHERE id_contato=".$contato_id;
			$this->bFetchObject($sSql);
		}
		
		public function bUpdate() {
			if(($this->getId()) == 0) {
				#INSERIR UM NOVO ENDERECO NO BANCO DE DADOS
				$sSql = "INSERT INTO contato (tel_fixo, tel_comercial, tel_celular, email) VALUES (";
				$sSql .= " '".utf8_decode($this->getTelefoneFixo())."', ";
				$sSql .= " '".utf8_decode($this->getTelefoneComercial())."', ";
				$sSql .= " '".utf8_decode($this->getTelefoneCelular())."', ";
				$sSql .= " '".utf8_decode($this->getEmail())."' )";
				
				if(!$this->bExecute($sSql)) {
					$this->imprimeErro('Ocorreu um erro ao tentar inserir o objeto Contato');
					return false;
				} else {
					$this->setId(mysql_insert_id());
					return true;
				}
			} else {
				#ALTERAR UM ENDERECO NO BANCO DE DADOS
				$sSql = "UPDATE contato SET ";
				$sSql .= " tel_fixo = '".utf8_decode($this->getTelefoneFixo())."', ";
				$sSql .= " tel_comercial = '".utf8_decode($this->getTelefoneComercial())."', ";
				$sSql .= " tel_celular = '".utf8_decode($this->getTelefoneCelular())."', ";
				$sSql .= " email = '".utf8_decode($this->getEmail())."' ";
				$sSql .= " WHERE id_contato=".$this->getId();

				if(!$this->bExecute($sSql)) {
					$this->imprimeErro('Ocorreu um erro ao tentar alterar registro de Contato');
					return false;
				} else {
					return true;
				}
				
			}
		}
		
		public function bDelete() {
			$sSql = "DELETE FROM contato WHERE id_contato=".$this->getId();

			if(!$this->bExecute($sSql)) {
				$this->imprimeErro('Ocorreu um erro ao tentar excluir o registro de Contato');
				return false;
			} else {
				return true;
			}
		}
		
		public function toString() {
			echo '### CONTATO ###<br>';
			echo 'ID...........:'.$this->iId.'<br>';
			echo 'TEL FIXO.....:'.$this->sTelefoneFixo.'<br>';
			echo 'TEL_COMERCIAL:'.$this->sTelefoneComercial.'<br>';
			echo 'TEL_CELULAR..:'.$this->sTelefoneCelular.'<br>';
			echo 'EMAIL........:'.$this->sEmail.'<br>';
		}
			
	## MÉTODO GET's DA CLASSE ## 
		public function getId()	{
			return $this->iId;
		}
		
		public function getTelefoneFixo() {
			return $this->sTelefoneFixo;
		}
		
		public function getTelefoneComercial() {
			return $this->sTelefoneComercial;
		}
		
		public function getTelefoneCelular() {
			return $this->sTelefoneCelular;
		}
		public function getEmail() {
			return $this->sEmail;
		}

	## MÉTODO SET's DA CLASSE ## 
		public function setId($id) {
			$this->iId = $id;
		}
		public function setTelefoneFixo($tel_fix) {
			$this->sTelefoneFixo = $tel_fix;
		}
		public function setTelefoneComercial($tel_com) {
			$this->sTelefoneComercial = $tel_com;
		}
		public function setTelefoneCelular($tel_cel) {
			$this->sTelefoneCelular = $tel_cel;
		}
		public function setEmail($email) {
			$this->sEmail = $email;
		}
	}
?>
