<?php
    session_start();
    include_once("../classes/classPaciente.php");

    $paciente = new Paciente($_GET['id_paciente']);

    $_SESSION['PACIENTE']['ID'] = $paciente->getId();
    $_SESSION['PACIENTE']['NOME'] = $paciente->getNome();
	//print_r ($_SESSION['PACIENTE']);
	
	function limpaTemp() {
        $diretorio = "../temp/";
        $ponteiro_diretorio = opendir($diretorio);

        while ($nome_itens = readdir($ponteiro_diretorio)) {
            $itens[] = $nome_itens;
        }
        $i=0;
        foreach ($itens as $listar) {
            if(!is_dir($diretorio.$listar) && substr($listar, 0) != "/") {
                unlink($diretorio.$listar);
            }
        }
    }
	
	//var_dump($_GET);
	
	if (isset($_GET['apagarImagens']) && $_GET['apagarImagens'] == 'true'){
		//echo $_GET['apagarImagens'];
		limpaTemp();
	}
?>