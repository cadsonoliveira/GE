<?php
    include_once("../classes/classTratamento.php");
    include_once("../classes/classImagem.php");
    include_once("../funcoes/common.php");


    if(($_GET['tipo']=='atualizacao') && isset($_POST)) {
        $caminho_destino = "../documentos/pacientes/";
		
        $imagem = new Imagem();

        $id_tratamento = $_POST['id_tratamento'];
        $id_paciente = $_POST['id_paciente'];
        $descricao = addslashes($_POST['sessao']);
		
		$apagar_imagens = $_POST['apagar_imagens'];
        		
        #DADOS DO TRATAMENTO
        if(isset($_POST['id_tratamento'])&& isset($_POST['id_paciente'])) {
            $imagens = array();
            foreach($_POST as $nome_campo => $valor) {
                $tipo_campo = explode("_",$nome_campo);
                if($tipo_campo[0] == "img") {
                    $imagens[$nome_campo] = $valor;

                }
            }
            $caminho_destino .= $id_paciente.'/tratamento/';

            $tratamento = new Tratamento($id_tratamento);

            $status = $_POST['status'];
            $resultado = $_POST['resultado'];

            #$data = explode("/", $_POST['data_inicio']);
            #$data_inicio = $data[2].'-'.$data[1].'-'.$data[0];
            #$data = explode("/", $_POST['data_termino']);
            #$data_termino = $data[2].'-'.$data[1].'-'.$data[0];
            #$tipo = $_POST['tipo'];
            #$subtipo = $_POST['subtipo'];
            $data_atual = date("Y-m-d");

			if(($resultado == 0) || ($resultado == 1) || ($resultado == 3))
			{
				if(($tratamento->getDataTermino() == "0000-00-00") || ($tratamento->getDataTermino() == NULL)) {
					$data_termino = $data_atual;
					$tratamento->setDataTermino($data_termino);
				}
			}
            $tratamento->setIdPessoa($id_paciente);
            $tratamento->setStatus($status);
            $tratamento->setSucesso($resultado);
            $tratamento->setDescricao($descricao);
            
            $tratamento->bUpdate();

            $i = 0;
            foreach($imagens as $nome => $caminho_origem) {
                $nome = explode("/",$caminho_origem);
                $tam_nome = sizeof($nome);

                $extensao = explode(".", $nome[$tam_nome-1]);
                $tam_ext = sizeof($extensao);

                $nome_imagem = createName().".".$extensao[$tam_ext-1];
                if(!copy($caminho_origem, $caminho_destino.$nome_imagem)) {
                    echo "ERRO AO COPIAR A IMAGEM";
                    exit();
                }
                $i++;
                $imagem->setId(0);
                $imagem->setIdTratamento($id_tratamento);
                $imagem->setIndice(2);
                $imagem->setData($data_atual);
                $imagem->setCaminho($nome_imagem);
                $imagem->setObs($_POST['sessao']);
                $imagem->bUpdate();
            }
			if($apagar_imagens == "true")
			{
				//limpaTemp();
			}
        }

    } else {

        if(($_GET['tipo']=='novo') && isset($_POST)) {

            $vet_trat = explode(",", $_POST['conj_match_codes']);
            foreach($vet_trat as $item)
            {
                $tratamento = new Tratamento();

                $data = explode("/", $_POST['data_inicio']);
                $data_inicio = $data[2].'-'.$data[1].'-'.$data[0];

                $tratamento->setIdPessoa($_POST['id_paciente']);
                $tratamento->setDataInicio($data_inicio);
                $tratamento->setStatus($_POST['status']);
                $tratamento->setIdMatchCode($item);
                $tratamento->setDescricao($_POST['sessao']);
                $tratamento->setDente(strtoupper($_POST['dente']));

                $tratamento->bUpdate();

            }

            
        }
    }
    # - Redirecionando a pagina a partir do metodo POST
    # E necessário que se redirecione desta forma, pois o arquivo: tratamentos_selecao.php
    # 	capta o PACIENTE pelo id_paciente
    echo '
                    <html><head></head><body>
                    <form action="../layouts/tratamentos.php" id="cd_field" name="cd_field" method="post">
                            <input name="id_paciente" id="id_paciente" type="hidden" value="'.$_POST['id_paciente'].'" />
                    </form>
                    <script>
                            document.cd_field.submit();
                    </script>
                    </body></html>
            ';

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


?>
