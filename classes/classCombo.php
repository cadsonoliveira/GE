<?php
include_once('classPersistencia.php');

class Combo extends Persistencia {
	private $sClassSelect;
	private $sClassOption;
	private $iSize;	
	private $array_itens;
	
	public function setClassSelect($sValue) {
		$this->sClassSelect = $sValue;       
	}
	
	public function setSize($sValue) {
		$this->iSize = $sValue;
	}  
	
	public function setClassOption($sValue, $vValue2){
		$this->sClassOption = $sValue;  
		$this->sSize = $vValue2;
	}
	
	public function Combo() {
		parent::__construct();
		//$this->sClassSelect = "ComboBox";
		$this->sClassOption = "DropDown";
		$this->sSize = 0;
	}
	
	public function bAddEstadosBrasileiros() {
		$this->bClearCombo();
		$this->bAddItemCombo("AC","ACRE");
		$this->bAddItemCombo("AL","ALAGOAS");
		$this->bAddItemCombo("AP","AMAP&Aacute;");
		$this->bAddItemCombo("AM","AMAZONAS");
		$this->bAddItemCombo("BA","BAHIA");
		$this->bAddItemCombo("CE","CEAR&Aacute;");
		$this->bAddItemCombo("DF","DISTRITO FEDERAL");
		$this->bAddItemCombo("ES","ESP&Iacute;RITO SANTO");
		$this->bAddItemCombo("GO","GOI&Aacute;S");
		$this->bAddItemCombo("MA","MARANH&Atilde;O");
		$this->bAddItemCombo("MT","MATO GROSSO");
		$this->bAddItemCombo("MS","MATO GROSSO DO SUL");
		$this->bAddItemCombo("MG","MINAS GERAIS");
		$this->bAddItemCombo("PA","PAR&Aacute;");
		$this->bAddItemCombo("PB","PARA&Iacute;BA");
		$this->bAddItemCombo("PR","PARAN&Aacute;");
		$this->bAddItemCombo("PE","PERNAMBUCO");
		$this->bAddItemCombo("PI","PIAU&Iacute;");
		$this->bAddItemCombo("RJ","RIO DE JANEIRO");
		$this->bAddItemCombo("RN","RIO GRANDE DO NORTE");
		$this->bAddItemCombo("RS","RIO GRANDE DO SUL");
		$this->bAddItemCombo("RO","ROND&Ocirc;NIA");
		$this->bAddItemCombo("RR","RORAIMA");
		$this->bAddItemCombo("SC","SANTA CATARINA");
		$this->bAddItemCombo("SP","S&Atilde;O PAULO");
		$this->bAddItemCombo("SE","SERGIPE");
		$this->bAddItemCombo("TO","TOCANTINS");
	}
	
	public function bAddItemCombo($campo1,$campo2) {
		if (isset($this->array_itens)){
			$i = sizeof($this->array_itens);
		} else {
			$i = 0;
		}
		$this->array_itens[$i] = array("codigo" =>$campo1 , "descricao" => $campo2 );
		return true;
	}

	public function bClearCombo() {
		if (isset($this->array_itens)){
			array_splice($this->array_itens,0);
			return true;
		}else{
			return false;
		}
	}

	private function bLoadSqlCombo($sql,$campo1,$campo2) {
		$this->bExecute($sql);
		$this->bNumRows();
		
		if($this->getDbNumRows() <= 0)
		{
			return false;
		}
		for ($i=0;$i < $this->getDbNumRows();$i++)
		{
		    if(($i >= 0) && ($i < $this->getDbNumRows()))
			{
				$this->Row = $i;
				$this->bCarregaRegistroPorLinha($i);
				$array_dados = $this->getDbArrayDados();
				$this->bAddItemCombo($array_dados[$campo1], utf8_encode($array_dados[$campo2]));
		    }
		}
    	return true;
	}
	
	public function sGetHTML($sql,$nomecampo,$campo1,$campo2,$padrao = "",$evento="", $style="") {
            $this->combo = new php_combo;          //declara um novo objeto
	    if($sql != '')
 			$this->bLoadSqlCombo($sql,$campo1,$campo2); // Adiciona o resultado do sql na matriz
			//$this->combo->html = "<select size='" . $this->iSize . "' class='" . $this->sClassSelect . "' name='$nomecampo' id='$nomecampo' $evento $style >";
			$this->combo->html = "<select name='".$nomecampo."' id='$nomecampo' ".$style." $evento >";
		
			for ($i=0;$i < sizeof($this->array_itens);$i++) {
				if ((strtoupper($padrao)==strtoupper($this->array_itens[$i]["codigo"])) or (strtoupper($padrao) == strtoupper($this->array_itens[$i]["descricao"])))
					$this->combo->html .= "       <option value='".$this->array_itens[$i]["codigo"]."' selected='selected'>".$this->array_itens[$i]["descricao"]."</option>";
			else
					$this->combo->html .= "       <option value='".$this->array_itens[$i]["codigo"]."'>".$this->array_itens[$i]["descricao"]."</option>";
		}
		$this->combo->html .= "</select>";
	    $this->bClearCombo();
		return $this->combo->html; // retorna uma string contendo o combobox montado
	} 
	
	public function setDisable() {
		$this->disabled ();         //declara um novo objeto
	} 
  
	
  
  public function _Combo(){
      unset($this);
  }

//-------------------------------------------------------------------------------

} // fim da classe
class php_combo {
  var $html;  //Propriedade que define o html do combo
}	

	/*
		Metodo .....: montaCombo
		Descricao ..: Montar um combo box com campos da tabela e ou com itens adicionados
		Parametros .: $sql ........: Selecao de registros
			      $nomecampo ..: Nome do campo a ser gerado um combo
	                      $campo1 .....: Nome da coluna de valores
		 	      $campo2 .....: Nome da coluna de dados exibidos na tela
			      $padrao .....: Seleciona um valor default
			      $evento .....: Aciona um evento

		Retorno ....: Retorna uma string contendo o combobox montado

		Metodos Auxiliares :
				bAddItemCombo($campo1,$campo2)
				bDelItemCombo()
			        bOrderCombo()
				bClearCombo()
				bLoadSqlCombo($sql,$campo1,$campo2)
		Uso ........:
				// Preparacao do combo
				bClearCombo();                  // Chamada do metodo para limpeza do combo
				 bAddItemCombo("1","aaaaaaaaa"); // Chamada do metodo para inclusao de novos itens
				 bAddItemCombo("2","bbbbbbbbb");
					  //
		              $sql = "Select codigo,descricao from tabelas"
					  echo montaCombo($sql,"teste","codigo","descricao")

	*/
	
?>
