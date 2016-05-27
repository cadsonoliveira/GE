<?php

include_once ("../funcoes/common.php");
include_once ("../classes/classDocumentos.php");
include_once ("../classes/classTabela.php");


if($_GET['acao'] == "adicionar") {
    $documento = new Documentos();



    $extensao = explode(".",$_FILES['arquivo']['name']);
    $tam_ext = sizeof($extensao);
    $nome_arquivo = createName().".".strtolower($extensao[$tam_ext-1]);

    $documento->setIdPessoa($_POST['id_pessoa']);
    $documento->setImagemCaminho($nome_arquivo);
    $documento->setObservacoes($_POST['sessao']);
    $documento->setReceituario($_POST['receita']);
    $documento->setDataDocumento(encodeDate($_POST['data_doc']));

    $documento->bUpdate();

    $caminho = "../documentos/pacientes/".$_POST['id_pessoa']."/outros_documentos/".$nome_arquivo;

    //if(!copy($_FILES['arquivo']['tmp_name'], $caminho)) {
        /** O texto comentado permite entrada de prontuário sem imagem ou documento*/
        //echo "ERRO AO COPIAR A IMAGEM";
        //exit();
    //}
    $id_paciente = $_POST['id_pessoa'];
    $sql = "SELECT * FROM documentos WHERE id_pessoa=".$id_paciente;

    $pers = new Persistencia();
    $pers->bExecute($sql);

    $html_tabela_doc = '
                       <table title="Lista de Documentos do paciente" summary="Lista dos documentos do paciente" style="margin-bottom:40px;">
						<thead>
							<tr>
								<th class="numero">Número</th>
								<th>Descrição / Receituário </th>
								<th class="data">Data do documento</th>
								<th class="data">Data de cadastro</th>
								<th class="operacoes">Operações</th>
							</tr>
						</thead>
						<tbody>
                    ';

    $cont = 0;
    while($cont < $pers->getDbNumRows()) {
        $pers->bCarregaRegistroPorLinha($cont);
        $result = $pers->getDbArrayDados();

         if($cont % 2 == 0){
			$cor_linha_tabela = "tableColor1";
		}else{
			$cor_linha_tabela = "tableColor2";
		}
		
		$html_tabela_doc .= '
			<tr class="'.$cor_linha_tabela.'">
				<td class="numero">'.$result['id_documento'].'</td>
				<td class="observacoes">'.utf8_encode($result['observacoes']).'</td>
				<td class="receituario">'.utf8_encode($result['receituario']).'</td>
				<td class="data">'.decodeDate($result['data_documento']).'</td>
				<td class="data">'.decodeDate(substr($result['data_cadastro'], 0, 10)).'</td>
				<td class="operacoes">
					<span style="display:block; margin:auto; width:80px;">
						<a class="visualizarDocumentos ir" href="#div_doc_a" onclick="visualiza_doc(\''.$result['imagem_caminho'].'\','.$id_paciente.', \''.decodeDate($result['data_documento']).'\', \''.decodeDate(substr($result['data_cadastro'],0, 10)).'\')" title="Visualizar Documento">Visualizar Documento</a>
						<a class="download ir" href="#div_doc_a" onclick="download_doc(\''.$result['imagem_caminho'].'\','.$id_paciente.', 1, '.$result['id_documento'].')" title="Download do Documento">Download</a>
						<a class="excluir ir" href="#div_doc_a" onclick="remove_doc('.$result['id_documento'].');" title="Excluir Documento">Excluir Documento</a>
					</span>
				</td>
			</tr>
';
        $cont++;
    }
    $html_tabela_doc .= '</tbody></table>';
    echo $html_tabela_doc;


}

//header("Location: ../layouts/pacientes_documento.php");

?>
