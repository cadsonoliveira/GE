<?php
        error_reporting (E_ALL & ~ E_NOTICE & ~ E_DEPRECATED);

	date_default_timezone_set("America/Sao_Paulo");
	class Persistencia  {

		private $DbHost; //private
		private $DbUsuario; //private
		private $DbSenha; //private
		private $DbBanco; //private
		private $DbConexao; //private
		private $DbDescricao;
		private $DbResExecute;
		private $DbNumRows;
		private $DbNumFields;
		private $DbArrayDados;
		private $Row;
		  
		public function __construct() { 
			/*
			 * Cria a conexao com o banco
			 */
			$this->DbHost = "localhost";
			$this->DbUsuario = "root";
			$this->DbSenha = "";
			$this->DbBanco = "easysge";
			$this->DbDescricao = "MYSQL";

			$this->DbConexao = mysql_pconnect($this->DbHost, $this->DbUsuario, $this->DbSenha);
			mysql_select_db($this->DbBanco);
		}
		
		public function getRow() {
			return $this->Row;
		}
		
		public function getDbNumRows() {
			$this->bNumRows();
			return $this->DbNumRows;
		}
		public function getDbNumFields() {
			$this->bNumFields();
			return $this->DbNumFields;
		}
		
		public function getDbResExecute() {
			return $this->DbResExecute;
		}
		
		public function getDbDescricao() {
			return $this->DbDescricao;
		}
		
		public function getDbArrayDados() {
			return $this->DbArrayDados;
		}

		public function imprimeErro($mensagem, $error="", $sql="")
		{
			echo "<font color='red'><b>".$mensagem."<b></font>";
			echo "<br>";
			echo "ERRO:<br>\t".$error;
			echo "<br>";
			echo "SQL:<br>\t".$sql;
		}		
		public function bExecute($sql = "") {
			$this->DbResExecute = mysql_query($sql) or die ($this->imprimeErro('Erro ao executar a query.', mysql_error(), $sql));
 			if($this->DbResExecute){
				return true;
			} else {
				return false;
			}
		}
		
		public function bNumRows() {
			$this->DbNumRows = mysql_num_rows($this->DbResExecute);
		}
		public function bNumFields() {
			$this->DbNumFields = mysql_num_fields($this->DbResExecute);
		}
		
		public function bDados() {
			$this->bNumRows();
			if($this->DbNumRows > 0) {
				$this->DbArrayDados = mysql_fetch_array($this->DbResExecute, MYSQL_ASSOC);
			} else {
				$this->DbArrayDados = NULL;
			}		
		}
		
		public function bCarregaRegistroPorLinha($linha)
		{
			mysql_data_seek($this->DbResExecute, $linha);
			if($this->DbNumRows > 0) {
				$this->DbArrayDados = mysql_fetch_array($this->DbResExecute, MYSQL_ASSOC);
				return true;
			} else {
				$this->DbArrayDados = NULL;
				return false;
			}		
		}

	}

?>
