<?php
	if($_GET['acao'] != "recarrega_foto" && $_GET['acao'] != "exclui_fotos"){
		echo $_GET['div_destino'].'|;|';
	}
	
	include_once("../classes/classPersistencia.php");
	include_once("../classes/classPlanoSaude.php");
	include_once("../classes/classPaciente.php");
	include_once("../classes/classDentistaEncaminhador.php");
	include_once ("../classes/classPaciente.php");
	include_once("../classes/classCombo.php");
	include_once ("../funcoes/common.php");
    
	if(isset($_GET['acao'])){
		if($_GET['acao'] == "recarrega_combo"){
			carrega_combobox($_GET['combo']);
		}
        elseif($_GET['acao'] == "recarrega_foto"){
            carrega_foto($_GET['tipo'],$_GET['caminho_foto']);
        }elseif($_GET['acao'] == "exclui_fotos"){
        	apaga_fotos($_GET['id'],$_GET['id_destino']);
        }
	}
	
	function apaga_fotos($id,$div){
		$caminho = "../documentos/pacientes/".$id."/foto/";
        unlinkRecursive($caminho, 1);
        
        if(!file_exists("../documentos/pacientes/temp/"))
                    mkdir("../documentos/pacientes/temp/");
                // abre o diretório
                $dir  = opendir("../documentos/pacientes/temp/");
                // monta os vetores com os itens encontrados na pasta
                while ($nome_itens = readdir($dir)){
                    $itens[] = $nome_itens;
                }
                // ordena o vetor de itens
                sort($itens);
                // percorre o vetor para fazer a separacao entre arquivos e pastas
                foreach ($itens as $listar)
                {
                // retira "./" e "../" para que retorne apenas pastas e arquivos
                    if ($listar!="." && $listar!=".." && $listar!="Thumbs.db" && $listar!=".svn")
                    {
                    // checa se o tipo de arquivo encontrado é uma pasta
                        if (!is_dir($listar) && extensao($listar)=='jpg'){
                            unlink("../documentos/pacientes/temp/".$listar);
                        }
                    }
                }
                $pac = new Paciente($id);
                $pac->setCaminhoFoto("");
                $pac->bUpdate();
                echo 'fotoajax|;|<img name="img_foto" alt="Retrato do Paciente" src="img/usuario_foto.png" style="width:63px; height:63px;" />';
	}

    //Tipo de captura e caminho da foto que já estava anteriormente
	function carrega_foto($tipo,$caminho_foto){
        if(!file_exists("../documentos/pacientes/temp/"))
            mkdir("../documentos/pacientes/temp/");
        // abre o diretório
        $dir  = opendir("../documentos/pacientes/temp/");
        // monta os vetores com os itens encontrados na pasta
		
        while ($nome_itens = readdir($dir)) {
            $itens[] = $nome_itens;
        }
        // ordena o vetor de itens
        sort($itens);
        // percorre o vetor para fazer a separacao entre arquivos e pastas
        foreach ($itens as $listar) {
        // retira "./" e "../" para que retorne apenas pastas e arquivos
           if ($listar!="." && $listar!=".." && $listar!="Thumbs.db" && $listar!=".svn")
           {
				// checa se o tipo de arquivo encontrado é uma pasta
				if (!is_dir($listar) && extensao($listar)=='jpg') {
					$fototemp = "../documentos/pacientes/temp/".$listar;
				}
           }
        }
        
        if($tipo == 'input_file'){
            if($_FILES['inputFile']['error'] == 0){
				$caminho = "../documentos/pacientes/temp";
				$vet_file = explode(".", $_FILES['inputFile']['name']);
				$qtd_posicao = sizeof($vet_file);
				$novo_nome = createName().".".$vet_file[$qtd_posicao-1];
				copy($_FILES['inputFile']['tmp_name'], $caminho."/".$novo_nome);
				if(file_exists($caminho."/".$novo_nome)){
					$caminho_foto = $caminho."/".$novo_nome;
				}
            }
        }
        else if($tipo == 'web_cam'){
            if(isset($fototemp) && file_exists($fototemp)){
                $caminho_foto = $fototemp;
            }
        }
        echo '<img name="img_foto" src="'.$caminho_foto.'" style="width:63px; height:63px;" />';
    }

	function carrega_combobox($tipo){
		$combo = new Combo();
		
		$paciente = new Paciente();
		
		if($tipo == "plano_saude"){
			echo '<label for="plano">Plano de Sa&uacute;de</label>';

			$sSqlPlanoSaude = "SELECT id_plano_saude, codigo, nome FROM planosaude";
			$combo->bAddItemCombo("-1","Nome do Plano de Sa&uacute;de");
			echo $combo->sGetHTML( $sSqlPlanoSaude , 'plano', 'id_plano_saude', 'nome', $paciente->getIdPlanoSaude(), '' ,  'style="width:270px;"' );

			echo '<button class="espacamentoEsquerda" type="button" onclick="cadastro_plano();">Cadastrar novo Plano de Saúde</button>';
			
		}
		elseif ($tipo == "dentista_encaminhador"){
			echo '<label for="convenio">Dentista Indicador</label>';

			$sSqlDentistaEncaminhador = "SELECT id_dentista_encaminhador, nome FROM dentista_encaminhador";
			$combo->bAddItemCombo("-1","Nome do Dentista"); 
			echo $combo->sGetHTML($sSqlDentistaEncaminhador,'dentista_encaminhador','id_dentista_encaminhador','nome',$paciente->getIdDentistaEncaminhador(),'' ,  'style="width:270px;"' );

			echo '<button class="espacamentoEsquerda" type="button" onclick="cadastro_dentista()">Cadastrar novo dentista indicador</button>';
		}
	}
	
?>
