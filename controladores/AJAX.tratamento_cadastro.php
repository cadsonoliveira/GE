<?php
session_start();
    if((!isset($_SESSION['USUARIO'])) && ($_SESSION['USUARIO']['VALIDA'] != true)){
        exit;
    }
	include_once("../classes/classPersistencia.php");
	include_once("../classes/classMatchCode.php");
	include_once("../classes/classConfiguracao.php");

	$config = new Configuracao(1);
	
	echo $_GET['div_id'].'|;|';

	echo '
	 <label class="alinhamentoDireita2">Sub-Tipo</label>
	 <span style="float:left; width:85%; margin-bottom:15px;">
	';

	$sSql = "SELECT id_match_code, descricao FROM match_code WHERE tipo='".$_GET['tipo']."' AND id_especialidade IN (SELECT id_especialidade FROM especialidade_usuario WHERE id_pessoa = ".$_SESSION['USUARIO']['ID'].") ORDER BY descricao";
	
	$pers = new Persistencia();
	$pers->bExecute($sSql);
	$cont = 0;
	$lista_trat = "";

	$num_registros = (($pers->getDbNumRows() % 2) == 0) ? $pers->getDbNumRows() : (int)($pers->getDbNumRows()/2)+1;
  
	while($cont < $pers->getDbNumRows()){
		$pers->bCarregaRegistroPorLinha($cont);
		$vet_result = $pers->getDbArrayDados();
		if($cont == $num_registros){
			//$lista_trat .= "</ol>";
			//$lista_trat .= "<ol>";
		}
		$desc = utf8_encode(strtolower($vet_result['descricao']));
		$desc = ucfirst($desc);
		$lista_trat .= "
			 <span class='displayInline diminuirLargura'>
				  <label onclick='arrayMatchCode(".$vet_result['id_match_code'].", $(this).getElement(\"input\").get(\"checked\"))'><input type='checkbox' name='".$vet_result['id_match_code']."' value='".$vet_result['id_match_code']."'/>".$desc."</label>
			</span>
			  
		";
		$cont++;
	}
	//$lista_trat .= "</ol></ol>";

	echo $lista_trat;
	
	echo '</span>';
?>