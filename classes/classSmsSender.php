<?php

	/* Classe para envio de SMS via iAgente **/
	include_once("classPersistencia.php");


	class SMSSender extends Persistencia
	{
		private $telefone = "";
		private $cabecalho = "";
		private $mensagem_base = "&mensagem=";
		private $mensagem = "";
		private $codigo;
		private $string_conexao;
		
		public function __construct($id_instance = 0){
			parent::__construct();
			
			// Vari�veis para envio do sms
			$this->codigo = "&codigosms=";
			$this->mensagem_base = "&mensagem=";
			$sSQL_configSMS = "SELECT instancia, id, string_conexao, cabecalho FROM `sms_config` WHERE instancia = ".$id_instance.";";
			
			$pers = new Persistencia();
			$pers->bExecute($sSQL_configSMS);
			$vet_result = "";
			$num_registros = (($pers->getDbNumRows() % 2) == 0) ? $pers->getDbNumRows()/2 : (int)($pers->getDbNumRows()/2)+1;
			
			$cont = 0;
			while($cont < $pers->getDbNumRows()){   
				$pers->bCarregaRegistroPorLinha($cont);
				$vet_result = $pers->getDbArrayDados();
				$this->string_conexao = utf8_encode(strtolower($vet_result['string_conexao']));
				$this->cabecalho = utf8_encode($vet_result['cabecalho']);
				$cont++;
			}			
		}
		
		public function prontoEnviarSMS()
		{
			if($this->string_conexao == "") {
				return 1;
			}
			return 0;
		}
		
		
		public function enviarSMS($celular = "", $mensagem_enviar = "", $id_sms = "050")
		{
			$url_api = $this->string_conexao;
			$url_api .= $celular.$this->mensagem_base.urlencode($this->cabecalho." - ".$mensagem_enviar).$this->codigo.$id_sms;
		
			echo $url_api;
			phpAlert($url_api);
			
			// concatena a url da api com a vari�vel carregando o conte�do da mensagem
			//$url_api  = "https://www.iagentesms.com.br/webservices/http.php?metodo=envio&usuario=iagente&senha=12345&celular=5199999999&mensagem={$mensagem}";

			// realiza a requisi��o http passando os par�metros informados
			// comentado para economizar SMS
			//$api_http = "simulado";
			$api_http = file_get_contents($url_api);

			// imprime o resultado da requisi��o
			echo $api_http;
			phpAlert($api_http);
		}
		
		## M�TODO GET's DA CLASSE ## 
		public function getMensagem() {
			return $this->mensagem;
		}
		
		public function getCelular() {
			return $this->telefone;
		}

		## M�TODO SET's DA CLASSE ## 
		public function setCelular($cel) {
			$this->telefone = $cel;
		}
		
		public function setMensagem($msg) {
			$this->mensagem = $msg;
		}
		
		
		function phpAlert($msg) {
			echo '<script type="text/javascript">alert("' . $msg . '")</script>';
		}
		
			
	}
?>