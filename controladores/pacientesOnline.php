<?php
	
    session_start();

	include_once ("../classes/classPersistencia.php");
	include_once ("../classes/classContato.php");
	include_once ("../classes/classEndereco.php");
	include_once ("../classes/classPaciente.php");
	include_once ("../classes/classTratamento.php");
	include_once ("../classes/classAnamnese.php");
	include_once ("../funcoes/common.php");


	if(isset($_GET['acao']) && ($_GET['acao']=='excluir')){
            $pers = new Persistencia();
            $sql = "SELECT * FROM tratamento WHERE id_pessoa=".$_GET['id'];
            $pers->bExecute($sql);

			echo $pers->getDbNumRows();
			
			//A condição aqui está: menor ou igual a 1, pois quando um paciente é criado o sistema cria um consulta automaticamente para 
			//    este usuário.
            if($pers->getDbNumRows() <= 1){
							
				$anamnese = new Anamnese();
				$anamnese->getAnamneseByPaciente($_GET['id']);
				$anamnese->bDelete();
			
                $paciente = new Paciente($_GET['id']);
                $paciente->bDelete();

                $caminho = "../documentos/pacientes/".$_GET['id']."/foto/";
                unlinkRecursive($caminho, 1);

                unset($_SESSION['PACIENTE']);
            } else {
                header("Location: ../layouts/pacientes.php?tipo=1");
				// Identificando qual foi a página anterior
				$pagina_anterior = $_SERVER['HTTP_REFERER'];
				
				if(confirm($pagina_anterior))
				;
                break;
            }

	} else {
            if(isset($_POST)){
            #PESSOA / PACIENTE
                $nome		= addslashes($_POST['nome']);
                if($_POST['data_nasc'] != ""){
                    $data 		= explode("/", $_POST['data_nasc']);
                    $data_nasc	= $data[2].'-'.$data[1].'-'.$data[0];
                } else {
                    $data_nasc = NULL;
                }

                if($_POST['data_cadastro'] != ""){
                    $data_cad 		= explode("/", $_POST['data_cadastro']);
                    $data_cadastro	= $data_cad[2].'-'.$data_cad[1].'-'.$data_cad[0];
                } else {
                    $data_cadastro = NULL;
                }

                $sexo		= $_POST['sexo'];
                $cpf		= ($_POST['cpf'] != "") ? $_POST['cpf'].'-'.$_POST['cpf_comp'] : "";
                $rg			= addslashes($_POST['rg']);

                $plano		= ($_POST['plano'] == -1) ? "NULL" : $_POST['plano'];
                $dent_enc	= ($_POST['dentista_encaminhador'] == -1) ? "NULL" : $_POST['dentista_encaminhador'];
                $status		= 0;
                $obs		= "";
                $num_carteira	= ($_POST['num_carteira_convenio'] == "") ? "" : $_POST['num_carteira_convenio'];
                $validade_carteira	= ($_POST['validade_carteira_convenio'] == "") ? "" : $_POST['validade_carteira_convenio'];

                $id = 0;
                if(isset($_GET) && isset($_GET['id'])){
                    $id = $_GET['id'];
                }

                $paciente = new Paciente($id);

                #CONTATO
                $tel_res	= addslashes($_POST['tel_res']);
                $tel_cel	= addslashes($_POST['tel_cel']);
                $tel_com	= addslashes($_POST['tel_com']);
                $email		= addslashes($_POST['mail']);

                $contato = $paciente->getContato();
                $contato->setTelefoneFixo($tel_res);
                $contato->setTelefoneComercial($tel_com);
                $contato->setTelefoneCelular($tel_cel);
                $contato->setEmail($email);

                #ENDERECO
				if($_POST['logrdo'] != NULL)
					$logrdo		= addslashes($_POST['logrdo']);
				else $logrdo	= ''; 
					
				if($_POST['numro'] != NULL)
					$numro		= addslashes($_POST['numro']);
				else $numro		= '';
				
                $compto		= addslashes($_POST['compto']);
                $cidade		= addslashes($_POST['cidade']);
                $bairro		= addslashes($_POST['bairro']);
                $sigla_est	= addslashes($_POST['estado']);

                if(($_POST['cep'] != "") && ($_POST['cep_comp'])){
                    $cep = addslashes($_POST['cep']).'-'.addslashes($_POST['cep_comp']);
                } else {
                    $cep = "";
                }

                $endereco = $paciente->getEndereco();
                $endereco->setLogradouro($logrdo);
                $endereco->setNumero($numro);
                $endereco->setComplemento($compto);
                $endereco->setCidade($cidade);
                $endereco->setBairro($bairro);
                $endereco->setSiglaEstado($sigla_est);
                $endereco->setCep($cep);

                $paciente->setNome($nome);
                $paciente->setDataNasc($data_nasc);
                $paciente->setSexo($sexo);
                $paciente->setRg($rg);
                $paciente->setCpf($cpf);
                $paciente->setContato($contato);
                $paciente->setEndereco($endereco);

                $paciente->setIdPlanoSaude($plano);
                $paciente->setStatus($status);
                $paciente->setObs($obs);
                $paciente->setNumCarteira($num_carteira);
                $paciente->setValidadeCarteira($validade_carteira);
                $paciente->setDataCadastro($data_cadastro);
                $paciente->setIdDentistaEncaminhador($dent_enc);

                $caminho="";


                if($paciente->getId() == 0){
                    $paciente->bUpdate();
                    $caminho = "../documentos/pacientes/".$paciente->getId()."/";
                    mkdir($caminho);
                    mkdir($caminho."/tratamento/");
                    mkdir($caminho."/foto/");
                    mkdir($caminho."/outros_documentos/");
					
					/* Criando um tratamento tipo consulta
					 * - Justificativa: Para que a secretária não tenha de criar consultas para todos os pacientes
					 *                  já que este procedimento ocorre para todos os pacientes
					 */
					$tratamento = new Tratamento();
	
					$data_inicio = date("Y-m-d");
	
					$tratamento->setIdPessoa($paciente->getId());
					$tratamento->setDataInicio($data_inicio);
					$tratamento->setStatus(0);
					$tratamento->setIdMatchCode(21);
					$tratamento->setDescricao("Consulta padrão");
					$tratamento->setDente(100);
	
					$tratamento->bUpdate();

                } else {
                    if($_FILES['inputFile']['error'] == 0){
                        $caminho = "../documentos/pacientes/".$_GET['id']."/foto/";
                        unlinkRecursive($caminho, 0);
                    }
                    $paciente->bUpdate();
                }

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
                            $fototemp="../documentos/pacientes/temp/".$listar;
                        }
                    }
                }

                if(file_exists($fototemp)){
                    $caminho = "../documentos/pacientes/".$paciente->getId()."/";
                    $pac = new Paciente($paciente->getId());

                    $vet_file = explode(".", $fototemp);
                    $qtd_posicao = sizeof($vet_file);
                    $novo_nome = createName().".".$vet_file[$qtd_posicao-1];
                    if(!is_dir($caminho."foto")){
	                    mkdir($caminho."foto");
                    }
                    copy($fototemp, $caminho."foto/".$novo_nome);
                    $pac->setCaminhoFoto($novo_nome);
                    $pac->bUpdate();
                }
                //Deleta a foto temporária da webcam
                unlinkRecursive('../documentos/pacientes/temp/', false);
				
            }
            $_SESSION['PACIENTE']['ID'] = $paciente->getId();
            $_SESSION['PACIENTE']['NOME'] = $paciente->getNome();
        }
	
	if($_POST['anamnese'] == "sim"){
		header("Location: ../layouts/questionario_anamnese.php");
	} else {
		header("Location: ../layouts/pacientes.php?qtdpag=".$_SESSION['qtd_resultado_por_pagina']."&pag=".$_SESSION['pag_atual']."&letra=".$_SESSION['letra']."");
	}
?>
