<?php
	include_once("classPersistencia.php");

	class ErDentistaEspecialidade extends Persistencia
	{
		private $iId;
		private $iIdDentista;
		private $iIdEspecialidade;
		
		public function __construct($er_id = 0) {
			$this->iId = $er_id;
			$this->iIdDentista = 0;
			$this->iIdEspecialidade = 0;
		}

		public function toString() {
			echo '### CONTATO ###<br>';
			echo 'ID...........:'.$this->iId.'<br>';
			echo 'ID DENTISTA..:'.$this->iIdDentista.'<br>';
			echo 'ID ESPECIALID:'.$this->iIdEspecialidade.'<br>';
		}
	## MÉTODO GET's DA CLASSE ## 
		public function getId()	{
			return $this->iId;
		}
		public function getIdDentista() {
			return $this->iIdDentista;
		}
		public function getIdEspecialidade() {
			return $this->iIdEspecialidade;
		}
	## MÉTODO SET's DA CLASSE ## 
		public function setId($id)	{
			$this->iId = $id;
		}
		public function setId($id_dentista)	{
			$this->iIdDentista = $id_dentista;
		}
		public function setId($id_especialidade)	{
			$this->iIdEspecialidade = $id_especialidade;
		}

	}
?>